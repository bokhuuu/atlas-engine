<?php

declare(strict_types=1);

use App\Modules\AI\Services\RateLimitingService;
use App\Modules\AI\Services\TextGenerationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Testing\TextResponseFake;
use Prism\Prism\ValueObjects\Usage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    $this->service = new TextGenerationService(new RateLimitingService);
});

test('returns generated text', function () {
    Prism::fake([
        TextResponseFake::make()
            ->withText('Median price in Yerevan is $2,055/sqm')
            ->withUsage(new Usage(10, 20)),
    ]);

    $result = $this->service->generate('What is the median price in Yerevan?');

    expect($result)->toBe('Median price in Yerevan is $2,055/sqm');
});

test('tracks usage after generation', function () {
    Prism::fake([
        TextResponseFake::make()
            ->withText('Median price in Yerevan is $2,055/sqm')
            ->withUsage(new Usage(10, 20)),
    ]);

    $this->service->generate('What is the median price in Yerevan?');

    $this->assertDatabaseHas('ai_usage_logs', [
        'feature' => 'text_generation',
        'prompt_tokens' => 10,
        'completion_tokens' => 20,
    ]);
});

test('throws exception when rate limit exceeded', function () {
    Cache::put('ai_rate_limit:user_1:text_generation', 201, 3600);

    expect(fn () => $this->service->generate('Any question', '', 'user_1'))
        ->toThrow(RuntimeException::class, 'Rate limit exceeded');
});

test('returns cached response on identical prompt', function () {
    Prism::fake([
        TextResponseFake::make()
            ->withText('Cached response')
            ->withUsage(new Usage(10, 20)),
    ]);

    $first = $this->service->generate('Same prompt');
    $second = $this->service->generate('Same prompt');

    expect($first)->toBe($second);
});
