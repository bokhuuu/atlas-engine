<?php

declare(strict_types=1);

namespace App\Modules\AI\Api;

use App\Events\AiCallCompleted;
use App\Http\Controllers\Controller;
use App\Modules\AI\Agents\PropertyAssistantAgent;
use App\Modules\AI\Models\AiFeedback;
use App\Modules\AI\Models\AiUsageLog;
use App\Modules\AI\Requests\StreamPromptRequest;
use App\Modules\AI\Services\AIFallbackService;
use App\Modules\AI\Services\RateLimitingService;
use App\Modules\AI\Services\TokenBudgetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Handles AI chat requests via standard JSON responses.
 *
 * Uses respond() instead of respondStreamed() so that LarAgent
 * correctly saves conversation history to Redis after each turn.
 * The UI simulates word-by-word streaming by revealing the full
 * response text progressively - the user experience is identical
 * to real streaming but history persistence is guaranteed.
 *
 * Per-user session history is keyed by atlas_assistant_{user_id}
 * and stored in Redis via LarAgent's cache storage driver.
 */
class ChatController extends Controller
{
    public function __construct(
        private RateLimitingService $rateLimiter,
        private TokenBudgetService $tokenBudget,
    ) {}

    /**
     * Process a chat message and return the full AI response as JSON.
     * Checks rate limit and token budget before calling the agent.
     * History is automatically saved to Redis after each respond() call.
     */
    public function chat(StreamPromptRequest $request): JsonResponse
    {
        $user = $request->user();
        $userId = (string) $user->id;
        $message = $request->input('message', '');

        if (! $this->rateLimiter->check('chat', $userId)) {
            return response()->json([
                'error' => 'Rate limit exceeded. Please wait before sending another message.',
            ], 429);
        }

        if (! $this->tokenBudget->check()) {
            return response()->json([
                'error' => 'Monthly token budget exceeded. Please contact your administrator.',
            ], 429);
        }

        $sessionKey = 'atlas_assistant_'.$userId;

        try {
            $agent = new PropertyAssistantAgent($sessionKey);
            $response = $agent->message($message)->respond();

            $this->rateLimiter->increment('chat', $userId);

            AiCallCompleted::dispatch(
                feature: 'chat',
                provider: config('ai.providers.default'),
                model: config('ai.models.agent'),
                promptTokens: $agent->getLastPromptTokens() ?? 0,
                completionTokens: $agent->getLastCompletionTokens() ?? 0,
                userId: (int) $user->id,
            );

            return response()->json(['text' => $response]);
        } catch (\Throwable $e) {
            Log::error('ChatController: agent error', [
                'error' => $e->getMessage(),
                'session' => $sessionKey,
            ]);

            try {
                $fallback = app(AIFallbackService::class);
                $fallbackText = $fallback->generateText(
                    prompt: $message,
                    systemPrompt: 'You are a real estate market assistant for Yerevan, Armenia. '
                        .'The primary AI system is temporarily unavailable. '
                        .'Answer as helpfully as possible with general knowledge, '
                        .'and note that live market data tools are currently unavailable.'
                );

                return response()->json(['text' => $fallbackText]);
            } catch (\Throwable $fallbackException) {
                Log::error('ChatController: fallback also failed', [
                    'error' => $fallbackException->getMessage(),
                ]);

                return response()->json([
                    'error' => 'An error occurred. Please try again.',
                ], 500);
            }
        }
    }

    /**
     * Return the last 20 visible messages for the current user's session.
     * Filters out tool call rows and LarAgent internal messages - only
     * user and assistant rows with actual text content are returned.
     */
    public function history(Request $request): JsonResponse
    {
        $userId = (string) $request->user()->id;
        $sessionKey = 'chatHistory_PropertyAssistantAgent_atlas_assistant_'.$userId;

        $rows = DB::table('laragent_messages')
            ->where('session_key', $sessionKey)
            ->whereIn('role', ['user', 'assistant'])
            ->whereNotNull('content')
            ->orderBy('position', 'asc')
            ->get(['role', 'content']);

        $messages = $rows->map(function ($row) {
            $decoded = json_decode($row->content, true);

            $text = $row->role === 'user'
                ? ($decoded[0]['text'] ?? null)
                : ($decoded['text'] ?? null);

            if ($text === null) {
                return null;
            }

            return ['role' => $row->role, 'content' => $text];
        })
            ->filter()
            ->values()
            ->slice(-20)
            ->values();

        return response()->json(['messages' => $messages]);
    }

    /**
     * Return the current user's AI usage stats.
     * Returns this month and all-time token counts and cost.
     */
    public function usage(Request $request): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $thisMonth = AiUsageLog::where('user_id', $userId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('COALESCE(SUM(total_tokens), 0) as tokens, COALESCE(SUM(cost_usd), 0) as cost')
            ->first();

        $allTime = AiUsageLog::where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(total_tokens), 0) as tokens, COALESCE(SUM(cost_usd), 0) as cost')
            ->first();

        return response()->json([
            'this_month' => [
                'tokens' => (int) $thisMonth->tokens,
                'cost_usd' => round((float) $thisMonth->cost, 6),
            ],
            'all_time' => [
                'tokens' => (int) $allTime->tokens,
                'cost_usd' => round((float) $allTime->cost, 6),
            ],
        ]);
    }

    /**
     * Return the current user's feedback votes as a message_id → vote map.
     * Used to restore feedback state after page reload.
     */
    public function feedback(Request $request): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $votes = AiFeedback::where('user_id', $userId)
            ->whereNotNull('message_id')
            ->pluck('vote', 'message_id');

        return response()->json(['votes' => $votes]);
    }

    /**
     * Return system-wide AI usage stats (admin only).
     */
    public function systemUsage(Request $request): JsonResponse
    {
        $thisMonth = AiUsageLog::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('COALESCE(SUM(total_tokens), 0) as tokens, COALESCE(SUM(cost_usd), 0) as cost')
            ->first();

        $allTime = AiUsageLog::selectRaw('COALESCE(SUM(total_tokens), 0) as tokens, COALESCE(SUM(cost_usd), 0) as cost')
            ->first();

        return response()->json([
            'this_month' => [
                'tokens' => (int) $thisMonth->tokens,
                'cost_usd' => round((float) $thisMonth->cost, 6),
            ],
            'all_time' => [
                'tokens' => (int) $allTime->tokens,
                'cost_usd' => round((float) $allTime->cost, 6),
            ],
        ]);
    }
}
