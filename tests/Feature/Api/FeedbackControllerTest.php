<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('stores positive feedback', function () {
    $response = $this->postJson('/api/v1/ai/feedback', [
        'vote' => 1,
        'response_text' => 'The median price in Kentron is $2,500/sqm based on 44 listings.',
        'comment' => 'Very helpful!',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['message', 'id']);

    $this->assertDatabaseHas('ai_feedback', [
        'vote' => 1,
        'comment' => 'Very helpful!',
    ]);
});

it('stores negative feedback without comment', function () {
    $response = $this->postJson('/api/v1/ai/feedback', [
        'vote' => -1,
        'response_text' => 'That answer was wrong.',
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('ai_feedback', [
        'vote' => -1,
        'comment' => null,
    ]);
});

it('rejects invalid vote value', function () {
    $response = $this->postJson('/api/v1/ai/feedback', [
        'vote' => 5,
        'response_text' => 'Some response.',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('errors.vote.0', 'Vote must be 1 (positive) or -1 (negative).');
});

it('rejects missing response text', function () {
    $response = $this->postJson('/api/v1/ai/feedback', [
        'vote' => 1,
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('errors.response_text.0', 'Response text is required.');
});
