<?php

declare(strict_types=1);

namespace App\Modules\AI\Api;

use App\Events\AiCallCompleted;
use App\Http\Controllers\Controller;
use App\Modules\AI\Agents\PropertyAssistantAgent;
use App\Modules\AI\Requests\StreamPromptRequest;
use App\Modules\AI\Services\RateLimitingService;
use App\Modules\AI\Services\TokenBudgetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Handles AI chat requests via standard JSON responses.
 *
 * Uses respond() instead of respondStreamed() so that LarAgent
 * correctly saves conversation history to Redis after each turn.
 * The UI simulates word-by-word streaming by revealing the full
 * response text progressively — the user experience is identical
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
                promptTokens: 0,
                completionTokens: 0,
            );

            return response()->json(['text' => $response]);
        } catch (\Throwable $e) {
            Log::error('ChatController: agent error', [
                'error' => $e->getMessage(),
                'session' => $sessionKey,
            ]);

            return response()->json([
                'error' => 'An error occurred. Please try again.',
            ], 500);
        }
    }
}
