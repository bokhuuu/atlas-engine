<?php

declare(strict_types=1);

use App\Modules\AI\Services\TextGenerationService;
use App\Modules\AI\Services\TranslationService;

test('returns null for empty text', function () {
    $generator = Mockery::mock(TextGenerationService::class);
    $service = new TranslationService($generator);

    expect($service->translate(''))->toBeNull();
    expect($service->translate('   '))->toBeNull();
});

test('calls text generation service with translation prompt', function () {
    $generator = Mockery::mock(TextGenerationService::class);
    $generator->shouldReceive('generate')
        ->once()
        ->andReturn('Translated text');

    $service = new TranslationService($generator);
    $result = $service->translate('Հայերեն տեքստ');

    expect($result)->toBe('Translated text');
});

test('returns null when text generation fails', function () {
    $generator = Mockery::mock(TextGenerationService::class);
    $generator->shouldReceive('generate')
        ->once()
        ->andThrow(new Exception('API error'));

    $service = new TranslationService($generator);
    $result = $service->translate('Հայերեն տեքստ');

    expect($result)->toBeNull();
});
