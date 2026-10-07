<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Support;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Solves reCAPTCHA v3 challenges via CapSolver API.
 *
 * Used by MyhomeScraper to obtain a valid token for the
 * tnet phone reveal API endpoint.
 *
 * CapSolver docs: https://docs.capsolver.com/guide/captcha/ReCaptchaV3.html
 */
class CapSolverService
{
    private const TASK_URL = 'https://api.capsolver.com/createTask';

    private const RESULT_URL = 'https://api.capsolver.com/getTaskResult';

    private const MAX_POLLS = 20;

    private const POLL_INTERVAL_S = 3;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $siteKey,
        private readonly string $pageUrl,
    ) {}

    /**
     * Solve a reCAPTCHA v3 challenge and return the response token.
     *
     * Submits a task to CapSolver, then polls until the token is ready.
     * Throws RuntimeException if the task fails or times out.
     */
    public function solve(): string
    {
        $taskId = $this->createTask();

        return $this->pollForToken($taskId);
    }

    /**
     * Submit a ReCaptchaV3TaskProxyLess task to CapSolver.
     * Returns the taskId string.
     */
    private function createTask(): string
    {
        $response = Http::post(self::TASK_URL, [
            'clientKey' => $this->apiKey,
            'task' => [
                'type' => 'ReCaptchaV3TaskProxyLess',
                'websiteURL' => $this->pageUrl,
                'websiteKey' => $this->siteKey,
                'pageAction' => 'homepage',
            ],
        ]);

        $body = $response->json();

        if (($body['errorId'] ?? 1) !== 0) {
            throw new RuntimeException(
                'CapSolver createTask error: '.($body['errorDescription'] ?? 'unknown')
            );
        }

        return (string) $body['taskId'];
    }

    /**
     * Poll CapSolver until the task is ready and return the gRecaptchaResponse token.
     * Polls up to MAX_POLLS times with POLL_INTERVAL_S seconds between each.
     */
    private function pollForToken(string $taskId): string
    {
        for ($i = 0; $i < self::MAX_POLLS; $i++) {
            sleep(self::POLL_INTERVAL_S);

            $response = Http::post(self::RESULT_URL, [
                'clientKey' => $this->apiKey,
                'taskId' => $taskId,
            ]);

            $body = $response->json();

            if (($body['errorId'] ?? 1) !== 0) {
                throw new RuntimeException(
                    'CapSolver getTaskResult error: '.($body['errorDescription'] ?? 'unknown')
                );
            }

            if (($body['status'] ?? '') === 'ready') {
                $token = $body['solution']['gRecaptchaResponse'] ?? null;

                if ($token === null || $token === '') {
                    throw new RuntimeException('CapSolver returned empty token.');
                }

                return (string) $token;
            }
        }

        throw new RuntimeException('CapSolver timed out after '.(self::MAX_POLLS * self::POLL_INTERVAL_S).'s.');
    }
}
