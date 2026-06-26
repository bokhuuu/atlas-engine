<?php

declare(strict_types=1);

use App\Modules\AI\Services\AIFallbackService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Testing\TextResponseFake;
use Prism\Prism\ValueObjects\Usage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new AIFallbackService;
});

test('generateText returns response from available provider', function () {
    Prism::fake([
        TextResponseFake::make()
            ->withText('Median price in Kentron is $2,500/sqm')
            ->withUsage(new Usage(10, 20)),
    ]);

    $result = $this->service->generateText('What is the median price in Kentron?');

    expect($result)->toBe('Median price in Kentron is $2,500/sqm');
});

test('generateText with system prompt returns response', function () {
    Prism::fake([
        TextResponseFake::make()
            ->withText('I can only answer questions about Yerevan real estate.')
            ->withUsage(new Usage(10, 20)),
    ]);

    $result = $this->service->generateText(
        'Tell me about Paris',
        'You are a Yerevan real estate assistant.'
    );

    expect($result)->toBe('I can only answer questions about Yerevan real estate.');
});
