<?php

declare(strict_types=1);

test('health endpoint returns ok status', function () {
    $response = $this->getJson('/api/v1/health');

    $response->assertOk()
        ->assertJsonPath('status', 'ok')
        ->assertJsonStructure(['status', 'checks' => ['database', 'redis', 'queue']]);
});
