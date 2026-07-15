<?php

declare(strict_types=1);

namespace App\Modules\AI\Services;

use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Translates listing descriptions from any language to English.
 *
 * Uses the existing TextGenerationService (Claude via OpenRouter) -
 * no new API key needed. Translation quality for Armenian and Russian
 * is excellent with Claude; Georgian and Azerbaijani also supported.
 *
 * Called by TranslateNewListings job after each scrape completes.
 * Failures are logged but never throw - a failed translation must
 * never block the pipeline or crash a queue worker.
 */
class TranslationService
{
    public function __construct(
        private readonly TextGenerationService $textGenerator,
    ) {}

    /**
     * Translate a listing description to English.
     *
     * Returns the English translation on success, null on failure.
     * Empty or whitespace-only input returns null without an API call.
     */
    public function translate(string $text): ?string
    {
        $text = trim($text);

        if (empty($text)) {
            return null;
        }

        try {
            $prompt = <<<PROMPT
Translate the following real estate listing description to English. 
Preserve all factual details exactly. Do not add or remove information.
Output only the translated text, nothing else.

{$text}
PROMPT;

            $systemPrompt = 'You are a professional real estate translator. '
                .'Translate listing descriptions accurately and concisely. '
                .'Output only the translated text with no preamble or explanation.';

            return $this->textGenerator->generate(
                prompt: $prompt,
                systemPrompt: $systemPrompt,
                userId: 'system',
            );
        } catch (Throwable $e) {
            Log::warning('TranslationService: translation failed', [
                'error' => $e->getMessage(),
                'text_length' => strlen($text),
            ]);

            return null;
        }
    }
}
