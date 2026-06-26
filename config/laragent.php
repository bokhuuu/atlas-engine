<?php

declare(strict_types=1);

use LarAgent\Context\Drivers\CacheStorage;
use LarAgent\Context\Truncation\SimpleTruncationStrategy;
use LarAgent\Drivers\OpenAi\OpenRouter;
use LarAgent\History\InMemoryChatHistory;

/**
 * LarAgent configuration for Atlas.
 *
 * Atlas uses OpenRouter as the sole provider, routing to Anthropic Claude
 * for the agent model. All other providers from the LaraAI kit are removed
 * since Atlas does not use them — add back if needed in future threads.
 *
 * Agent namespace is App\Modules\AI\Agents — used by the ai:chat artisan command.
 * History is stored in the database via laragent_messages table.
 */
return [

    'default_driver' => OpenRouter::class,

    'default_chat_history' => InMemoryChatHistory::class,

    'default_history_storage' => [
        CacheStorage::class,
    ],

    'default_storage' => [
        CacheStorage::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Agent Namespaces
    |--------------------------------------------------------------------------
    | Directories scanned by the ai:chat artisan command to locate agent classes.
    */
    'namespaces' => [
        'App\\Modules\\AI\\Agents\\',
    ],

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    | Atlas uses OpenRouter exclusively. Set OPENROUTER_API_KEY in .env.
    | The model is set per-agent via config('ai.models.agent') in .env.
    */
    'providers' => [
        'openrouter' => [
            'label' => 'openrouter',
            'api_key' => env('OPENROUTER_API_KEY'),
            'model' => env('AI_AGENT_MODEL', 'anthropic/claude-haiku-4-5'),
            'driver' => OpenRouter::class,
            'default_truncation_threshold' => 100000,
            'default_max_completion_tokens' => (int) env('AI_MAX_TOKENS', 4096),
            'default_temperature' => 1,
        ],
    ],

    'default_providers' => null,
    'fallback_provider' => null,

    'mcp_tool_caching' => [
        'enabled' => env('MCP_TOOL_CACHE_ENABLED', false),
        'ttl' => env('MCP_TOOL_CACHE_TTL', 3600),
        'store' => env('MCP_TOOL_CACHE_STORE', null),
    ],

    'mcp_servers' => [],

    'track_usage' => false,

    'default_usage_storage' => null,

    'enable_truncation' => false,

    'truncation_provider' => 'openrouter',

    'default_truncation_strategy' => SimpleTruncationStrategy::class,

    'default_truncation_config' => [
        'keep_messages' => 10,
        'preserve_system' => true,
    ],

    'truncation_buffer' => 0.2,
];
