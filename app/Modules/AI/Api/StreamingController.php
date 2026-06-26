<?php

declare(strict_types=1);

namespace App\Modules\AI\Api;

use App\Http\Controllers\Controller;
use App\Modules\AI\Agents\PropertyAssistantAgent;
use App\Modules\AI\Requests\StreamPromptRequest;
use App\Modules\AI\Services\RateLimitingService;
use App\Modules\AI\Services\TokenBudgetService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Streams AI responses to the browser token by token via Server-Sent Events.
 *
 * Instead of waiting for the full response, the browser receives each word
 * as the AI generates it — same experience as ChatGPT's typing effect.
 *
 * The browser connects, receives chunks as they arrive and the stream closes
 * when the AI finishes. Each chunk arrives as: data: {"text":"..."}\n\n
 * Stream ends with: data: [DONE]\n\n
 *
 * ob_get_level() guard prevents Nginx output buffering from holding chunks.
 * X-Accel-Buffering header tells Nginx to pass chunks through immediately.
 */
class StreamingController extends Controller
{
    public function __construct(
        private RateLimitingService $rateLimiter,
        private TokenBudgetService $tokenBudget,
    ) {}

    /**
     * Open an SSE connection and stream the agent's response to the browser.
     * Checks rate limit and token budget before starting the stream.
     * Uses PropertyAssistantAgent with per-user persistent session history.
     */
    public function stream(StreamPromptRequest $request): StreamedResponse
    {
        $user = $request->user();
        $userId = (string) $user->id;
        $message = $request->input('message', '');

        if (! $this->rateLimiter->check('chat', $userId)) {
            return response()->stream(function () {
                echo 'data: '.json_encode(['error' => 'Rate limit exceeded. Please wait before sending another message.'])."\n\n";
                echo "data: [DONE]\n\n";
                flush();
            }, 200, $this->sseHeaders());
        }

        if (! $this->tokenBudget->check()) {
            return response()->stream(function () {
                echo 'data: '.json_encode(['error' => 'Monthly token budget exceeded. Please contact your administrator.'])."\n\n";
                echo "data: [DONE]\n\n";
                flush();
            }, 200, $this->sseHeaders());
        }

        $sessionKey = 'atlas_assistant_'.$userId;

        return response()->stream(function () use ($message, $userId, $sessionKey) {
            try {
                $agent = new PropertyAssistantAgent($sessionKey);

                $stream = $agent->message($message)->respondStreamed();

                foreach ($stream as $chunk) {
                    if (is_string($chunk)) {
                        echo 'data: '.json_encode(['text' => $chunk])."\n\n";
                    } elseif (isset($chunk->delta)) {
                        echo 'data: '.json_encode(['text' => $chunk->delta])."\n\n";
                    }
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }

                $this->rateLimiter->increment('chat', $userId);
            } catch (\Throwable $e) {
                Log::error('StreamingController: agent error', ['error' => $e->getMessage()]);
                echo 'data: '.json_encode(['error' => 'An error occurred. Please try again.'])."\n\n";
            }

            echo "data: [DONE]\n\n";
            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();
        }, 200, $this->sseHeaders());
    }

    /** Standard SSE response headers. */
    private function sseHeaders(): array
    {
        return [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ];
    }
}
