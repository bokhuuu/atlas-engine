<?php

declare(strict_types=1);

/**
 * Prism PHP configuration for Atlas.
 *
 * Atlas uses OpenRouter as the sole provider — it routes to Anthropic,
 * OpenAI, Google and others from a single API key.
 * All other providers are kept as placeholders for future use.
 *
 * Set OPENROUTER_API_KEY in .env to activate.
 */
return [

    'prism_server' => [
        'middleware' => [],
        'enabled' => env('PRISM_SERVER_ENABLED', false),
    ],

    'request_timeout' => env('PRISM_REQUEST_TIMEOUT', 30),

    'providers' => [
        'openai' => [
            'url' => env('OPENAI_URL', 'https://api.openai.com/v1'),
            'api_key' => env('OPENAI_API_KEY', ''),
            'organization' => env('OPENAI_ORGANIZATION', null),
            'project' => env('OPENAI_PROJECT', null),
        ],

        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY', ''),
            'version' => env('ANTHROPIC_API_VERSION', '2023-06-01'),
            'url' => env('ANTHROPIC_URL', 'https://api.anthropic.com/v1'),
            'default_thinking_budget' => env('ANTHROPIC_DEFAULT_THINKING_BUDGET', 1024),
            'anthropic_beta' => env('ANTHROPIC_BETA', null),
        ],

        'ollama' => [
            'url' => env('OLLAMA_URL', 'http://localhost:11434'),
        ],

        'mistral' => [
            'api_key' => env('MISTRAL_API_KEY', ''),
            'url' => env('MISTRAL_URL', 'https://api.mistral.ai/v1'),
        ],

        'groq' => [
            'api_key' => env('GROQ_API_KEY', ''),
            'url' => env('GROQ_URL', 'https://api.groq.com/openai/v1'),
        ],

        'gemini' => [
            'api_key' => env('GEMINI_API_KEY', ''),
            'url' => env('GEMINI_URL', 'https://generativelanguage.googleapis.com/v1beta/models'),
        ],

        'deepseek' => [
            'api_key' => env('DEEPSEEK_API_KEY', ''),
            'url' => env('DEEPSEEK_URL', 'https://api.deepseek.com/v1'),
        ],

        /*
        |--------------------------------------------------------------------------
        | OpenRouter — Primary provider for Atlas
        |--------------------------------------------------------------------------
        | Routes to Anthropic, Google, OpenAI and others from a single API key.
        | Set OPENROUTER_API_KEY in .env.
        */
        'openrouter' => [
            'api_key' => env('OPENROUTER_API_KEY', ''),
            'url' => env('OPENROUTER_URL', 'https://openrouter.ai/api/v1'),
            'site' => [
                'http_referer' => env('OPENROUTER_SITE_HTTP_REFERER', null),
                'x_title' => env('OPENROUTER_SITE_X_TITLE', null),
            ],
        ],
    ],

];
