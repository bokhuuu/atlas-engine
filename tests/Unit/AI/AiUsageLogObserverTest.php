<?php

declare(strict_types=1);

use App\Modules\AI\Models\AiUsageLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
});

test('stores monthly cost in cache when log is created', function () {
    AiUsageLog::create([
        'feature' => 'chat',
        'provider' => 'openrouter',
        'model' => 'anthropic/claude-haiku-4-5',
        'prompt_tokens' => 1000,
        'completion_tokens' => 500,
        'total_tokens' => 1500,
        'cost_usd' => 0.0028,
    ]);

    $cached = Cache::get('ai_monthly_cost:'.now()->format('Y-m'));

    expect((float) $cached)->toBe(0.0028);
});

test('accumulates cost across multiple logs', function () {
    AiUsageLog::create([
        'feature' => 'chat',
        'provider' => 'openrouter',
        'model' => 'anthropic/claude-haiku-4-5',
        'prompt_tokens' => 1000,
        'completion_tokens' => 500,
        'total_tokens' => 1500,
        'cost_usd' => 0.0028,
    ]);

    AiUsageLog::create([
        'feature' => 'chat',
        'provider' => 'openrouter',
        'model' => 'anthropic/claude-haiku-4-5',
        'prompt_tokens' => 1000,
        'completion_tokens' => 500,
        'total_tokens' => 1500,
        'cost_usd' => 0.0028,
    ]);

    $cached = Cache::get('ai_monthly_cost:'.now()->format('Y-m'));

    expect((float) $cached)->toBe(0.0056);
});

test('stores monthly token count in cache when log is created', function () {
    AiUsageLog::create([
        'feature' => 'chat',
        'provider' => 'openrouter',
        'model' => 'anthropic/claude-haiku-4-5',
        'prompt_tokens' => 1000,
        'completion_tokens' => 500,
        'total_tokens' => 1500,
        'cost_usd' => 0.0028,
    ]);

    $cached = Cache::get('ai_monthly_tokens:'.now()->format('Y-m'));

    expect((int) $cached)->toBe(1500);
});
