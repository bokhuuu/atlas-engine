<?php

declare(strict_types=1);

/**
 * Central configuration for all AI-related settings in Atlas.
 * Every model, provider, limit, cost rate and threshold is defined here.
 * Change behavior by setting values in .env — never edit this file directly.
 *
 * To deploy Atlas for a new market: only .env and scraper profiles change.
 * No AI config changes needed unless a different model is required.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Response Cache TTL
    |--------------------------------------------------------------------------
    | How long to cache identical AI responses (seconds).
    | Same prompt = cached response = zero cost on repeat requests.
    */
    'cache_ttl' => env('AI_CACHE_TTL', 3600),

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    | agent: the model powering PropertyAssistantAgent's tool-calling loop.
    | embeddings: used by EmbeddingService (deferred to Thread 6).
    | vision: used by MultiModalService (dormant, deferred to Thread 6).
    */
    'models' => [
        'agent' => env('AI_AGENT_MODEL', 'anthropic/claude-haiku-4-5'),
        'embeddings' => env('AI_EMBEDDINGS_MODEL', 'openai/text-embedding-3-small'),
        'vision' => env('AI_VISION_MODEL', 'google/gemini-flash-1.5'),
        'text' => env('AI_TEXT_MODEL', 'anthropic/claude-haiku-4-5'),
        'tools' => env('AI_TOOLS_MODEL', 'anthropic/claude-haiku-4-5'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Embeddings
    |--------------------------------------------------------------------------
    | search_limit: max documents returned by EmbeddingService::search().
    | Deferred — EmbeddingService is dormant in Thread 4.
    */
    'embeddings' => [
        'search_limit' => env('AI_SEARCH_LIMIT', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    | Atlas uses OpenRouter for all environments — it routes to Anthropic,
    | OpenAI, Google and others from a single API key.
    | Set AI_DEFAULT_PROVIDER=ollama in .env for offline local development.
    */
    'providers' => [
        'default' => env('AI_DEFAULT_PROVIDER', 'openrouter'),
        'production' => env('AI_PRODUCTION_PROVIDER', 'openrouter'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Chain
    |--------------------------------------------------------------------------
    | AIFallbackService tries providers in order if the primary fails.
    | Secondary falls back to a free OpenRouter model to keep the assistant alive.
    */
    'fallback' => [
        [
            'provider' => 'openrouter',
            'model' => env('AI_FALLBACK_PRIMARY_MODEL', 'anthropic/claude-haiku-4-5'),
        ],
        [
            'provider' => 'openrouter',
            'model' => env('AI_FALLBACK_SECONDARY_MODEL', 'openrouter/auto'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limits
    |--------------------------------------------------------------------------
    | Per-feature limits: max calls allowed within ttl window (seconds).
    | chat: 30 calls/hour per user — sufficient for an analyst session
    */
    'rate_limits' => [
        'chat' => ['max' => env('AI_RATE_LIMIT_CHAT', 30),     'ttl' => 3600],
        'text_generation' => ['max' => env('AI_RATE_LIMIT_TEXT', 50),     'ttl' => 3600],
        'embedding' => ['max' => env('AI_RATE_LIMIT_EMBED', 100),   'ttl' => 3600],
        'analysis' => ['max' => env('AI_RATE_LIMIT_ANALYSIS', 20), 'ttl' => 86400],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cost Alert Threshold (USD)
    |--------------------------------------------------------------------------
    | CostAlertListener sends Telegram + email when a single AI call
    | exceeds this amount. Default $0.01 is appropriate for haiku pricing.
    */
    'cost_alert_threshold' => env('AI_COST_ALERT_THRESHOLD', 0.01),

    /*
    |--------------------------------------------------------------------------
    | Model Cost Rates (per token, USD)
    |--------------------------------------------------------------------------
    | Used by UsageTrackingService to calculate cost per AI call.
    | claude-haiku-4-5 via OpenRouter: $0.80/1M input, $4.00/1M output.
    */
    'costs' => [
        'anthropic/claude-haiku-4-5' => ['input' => 0.0000008,  'output' => 0.000004],
        'anthropic/claude-sonnet-4-6' => ['input' => 0.000003,   'output' => 0.000015],
        'openai/text-embedding-3-small' => ['input' => 0.00000002, 'output' => 0],
        'openrouter/auto' => ['input' => 0,           'output' => 0],
    ],

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    | How many times to retry a failed AI call and how long to wait between
    | attempts (milliseconds). Applied uniformly across all services.
    */
    'retry' => [
        'times' => env('AI_RETRY_TIMES', 3),
        'sleep_ms' => env('AI_RETRY_SLEEP_MS', 1000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default System Prompt
    |--------------------------------------------------------------------------
    | Fallback used when no versioned prompt exists in prompt_versions table.
    | The real prompt is seeded on first run via PromptService::create().
    */
    'default_system_prompt' => env('AI_DEFAULT_SYSTEM_PROMPT', 'You are a real estate market intelligence assistant for for the Yerevan real estate market.'),

    /*
    |--------------------------------------------------------------------------
    | Webhook
    |--------------------------------------------------------------------------
    | secret: HMAC-SHA256 key for verifying incoming webhook requests.
    | max_file_size_kb: maximum accepted file size in kilobytes.
    */
    'webhook' => [
        'secret' => env('WEBHOOK_SECRET'),
        'max_file_size_kb' => env('WEBHOOK_MAX_FILE_SIZE_KB', 10240),
    ],

    /*
    |--------------------------------------------------------------------------
    | Max Tokens
    |--------------------------------------------------------------------------
    */
    'max_tokens' => env('AI_MAX_TOKENS', 4096),

    /*
    |--------------------------------------------------------------------------
    | Token Budget
    |--------------------------------------------------------------------------
    | Monthly token limit across all AI calls. Acts as a circuit breaker.
    | Set AI_TOKEN_BUDGET_ENABLED=false to disable during development.
    */
    'token_budget' => [
        'monthly_limit' => env('AI_MONTHLY_TOKEN_BUDGET', 1000000),
        'enabled' => env('AI_TOKEN_BUDGET_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation
    |--------------------------------------------------------------------------
    | chunk_size: number of listings to translate per batch in TranslateNewListings job.
    | Keeps memory usage bounded — each listing makes one API call, so smaller
    | chunks also limit concurrent API pressure.
    | Override via TRANSLATION_CHUNK_SIZE in .env.
    */
    'translation' => [
        'chunk_size' => (int) env('TRANSLATION_CHUNK_SIZE', 50),
    ],

];
