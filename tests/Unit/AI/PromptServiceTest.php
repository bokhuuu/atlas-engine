<?php

declare(strict_types=1);

use App\Modules\AI\Services\PromptService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new PromptService;
});

test('creates new version', function () {
    $prompt = $this->service->create('property_assistant', 'You are helpful', 'Initial');

    expect($prompt->version)->toBe(1);
    expect($prompt->is_active)->toBeTrue();
});

test('creates new version and deactivates old', function () {
    $v1 = $this->service->create('property_assistant', 'You are helpful', 'Initial');
    expect($v1->version)->toBe(1);
    expect($v1->is_active)->toBeTrue();

    $v2 = $this->service->create('property_assistant', 'You are expert', 'New');
    expect($v2->version)->toBe(2);
    expect($v2->is_active)->toBeTrue();

    $v1->refresh();
    expect($v1->is_active)->toBeFalse();
});

test('rollback restores previous version', function () {
    $v1 = $this->service->create('property_assistant', 'You are helpful', 'Initial');
    $v2 = $this->service->create('property_assistant', 'You are expert', 'New');

    $this->service->rollback('property_assistant');

    $v1->refresh();
    $v2->refresh();

    expect($v1->is_active)->toBeTrue();
    expect($v2->is_active)->toBeFalse();
});

test('returns all versions ordered by version descending', function () {
    $this->service->create('property_assistant', 'You are helpful', 'Initial');
    $this->service->create('property_assistant', 'You are expert', 'New');
    $this->service->create('property_assistant', 'You are guru', 'Newest');

    $history = $this->service->getHistory('property_assistant');

    expect($history->first()->version)->toBe(3);
    expect($history->last()->version)->toBe(1);
    expect($history)->toHaveCount(3);
});

test('returns active prompt content', function () {
    $this->service->create('property_assistant', 'You are helpful', 'Initial');
    $this->service->create('property_assistant', 'You are expert', 'New');

    $content = $this->service->get('property_assistant', 'Fallback prompt');

    expect($content)->toBe('You are expert');
});

test('returns fallback when no prompt exists', function () {
    $content = $this->service->get('nonexistent_key', 'Fallback prompt');

    expect($content)->toBe('Fallback prompt');
});
