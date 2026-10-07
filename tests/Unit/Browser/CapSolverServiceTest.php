<?php

declare(strict_types=1);

use App\Modules\Scraper\Support\CapSolverService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
});

it('returns a token when CapSolver resolves on first poll', function () {
    Http::fake([
        'api.capsolver.com/createTask' => Http::response([
            'errorId' => 0,
            'taskId' => 'task-abc-123',
        ]),
        'api.capsolver.com/getTaskResult' => Http::response([
            'errorId' => 0,
            'status' => 'ready',
            'solution' => ['gRecaptchaResponse' => 'valid-token-xyz'],
        ]),
    ]);

    $service = new CapSolverService(
        apiKey: 'test-key',
        siteKey: '6LeziPEpAAAAAHuR9vWBVCrfklSbWt8zixM4jAbM',
        pageUrl: 'https://www.myhome.ge',
    );

    expect($service->solve())->toBe('valid-token-xyz');
});

it('throws when createTask returns an error', function () {
    Http::fake([
        'api.capsolver.com/createTask' => Http::response([
            'errorId' => 1,
            'errorDescription' => 'Invalid API key',
        ]),
    ]);

    $service = new CapSolverService(
        apiKey: 'bad-key',
        siteKey: '6LeziPEpAAAAAHuR9vWBVCrfklSbWt8zixM4jAbM',
        pageUrl: 'https://www.myhome.ge',
    );

    expect(fn () => $service->solve())
        ->toThrow(RuntimeException::class, 'Invalid API key');
});

it('throws when getTaskResult returns an error', function () {
    Http::fake([
        'api.capsolver.com/createTask' => Http::response([
            'errorId' => 0,
            'taskId' => 'task-abc-123',
        ]),
        'api.capsolver.com/getTaskResult' => Http::response([
            'errorId' => 1,
            'errorDescription' => 'Task failed',
        ]),
    ]);

    $service = new CapSolverService(
        apiKey: 'test-key',
        siteKey: '6LeziPEpAAAAAHuR9vWBVCrfklSbWt8zixM4jAbM',
        pageUrl: 'https://www.myhome.ge',
    );

    expect(fn () => $service->solve())
        ->toThrow(RuntimeException::class, 'Task failed');
});

it('throws when token is empty in solution', function () {
    Http::fake([
        'api.capsolver.com/createTask' => Http::response([
            'errorId' => 0,
            'taskId' => 'task-abc-123',
        ]),
        'api.capsolver.com/getTaskResult' => Http::response([
            'errorId' => 0,
            'status' => 'ready',
            'solution' => ['gRecaptchaResponse' => ''],
        ]),
    ]);

    $service = new CapSolverService(
        apiKey: 'test-key',
        siteKey: '6LeziPEpAAAAAHuR9vWBVCrfklSbWt8zixM4jAbM',
        pageUrl: 'https://www.myhome.ge',
    );

    expect(fn () => $service->solve())
        ->toThrow(RuntimeException::class, 'CapSolver returned empty token.');
});
