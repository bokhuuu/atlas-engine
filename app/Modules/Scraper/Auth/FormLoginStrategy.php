<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Auth;

use Laravel\Dusk\Browser;
use RuntimeException;

/**
 * Handles form-based login for sites that require email + password authentication.
 *
 * Responsibilities:
 *   - Navigate to the login page
 *   - Fill credentials
 *   - Submit the form
 *   - Wait until a logged-in element confirms success
 *
 * No cookie management here - that belongs to CookieAuthStrategy.
 * This class only knows how to fill a form.
 */
class FormLoginStrategy
{
    public function __construct(
        private readonly Browser $browser,
        private readonly string $loginUrl,
        private readonly string $emailSelector,
        private readonly string $passwordSelector,
        private readonly string $submitSelector,
        private readonly string $successSelector,
        private readonly string $email,
        private readonly string $password,
        private readonly ?int $waitSeconds = null,
        private readonly ?string $submitText = null,
    ) {}

    /**
     * Execute the full login flow.
     *
     * Throws RuntimeException if the success element never appears -
     * which means login failed (wrong credentials, site down, layout changed).
     */
    public function login(): void
    {
        $this->browser->visit($this->loginUrl);

        $this->browser->waitFor(
            $this->emailSelector,
            $this->waitSeconds ?? (int) config('scraper.element_wait_timeout_s'),
        );

        $this->browser->type($this->emailSelector, $this->email);
        $this->browser->type($this->passwordSelector, $this->password);

        // Wait for submit button then click — fallback to JS click for React pages
        try {
            $this->browser->waitFor(
                $this->submitSelector,
                $this->waitSeconds ?? (int) config('scraper.element_wait_timeout_s'),
            );
            $this->browser->click($this->submitSelector);
        } catch (\Throwable) {
            // Fallback: find button by text content for React-rendered pages
            if ($this->submitText !== null) {
                $this->browser->script(
                    "Array.from(document.querySelectorAll('button')).find(b => b.textContent.trim() === '{$this->submitText}')?.click()"
                );
            }
        }

        try {
            $this->browser->waitFor(
                $this->successSelector,
                $this->waitSeconds ?? (int) config('scraper.element_wait_timeout_s'),
            );
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Login failed - success selector [{$this->successSelector}] never appeared after submitting form.",
                previous: $e,
            );
        }
    }
}
