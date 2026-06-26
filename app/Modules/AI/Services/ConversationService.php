<?php

declare(strict_types=1);

namespace App\Modules\AI\Services;

use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\SystemMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

/**
 * Manages back-and-forth chat conversations with memory.
 *
 * AI is stateless - it remembers nothing between calls.
 * This service stores every message in the DB and replays
 * the full history on each turn so the AI has context.
 *
 * Use for simple chat and Q&A. For tool calling or autonomous
 * agents use LarAgent instead.
 *
 * Dormant in Thread 4 — Atlas uses PropertyAssistantAgent via LarAgent instead.
 */
class ConversationService
{
    public function __construct(
        private RateLimitingService $rateLimiter
    ) {}

    /**
     * Handle one full conversation turn.
     * Accepts full message history, sends to AI, returns response text.
     * Caller is responsible for storing messages.
     */
    public function chat(array $messages, string $userId = 'default'): string
    {
        if (! $this->rateLimiter->check('chat', $userId)) {
            throw new \RuntimeException('Rate limit exceeded for chat');
        }

        $prismMessages = collect($messages)->map(fn ($m) => match ($m['role']) {
            'user' => new UserMessage($m['content']),
            'assistant' => new AssistantMessage($m['content']),
            'system' => new SystemMessage($m['content']),
        })->all();

        $response = Prism::text()
            ->using(Provider::from(config('ai.providers.default')), config('ai.models.text'))
            ->withMessages($prismMessages)
            ->asText();

        $this->rateLimiter->increment('chat', $userId);

        return $response->text;
    }
}
