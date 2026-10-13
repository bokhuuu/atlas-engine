<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Auth;

use App\Modules\Scraper\Auth\Contracts\AuthStrategyInterface;
use Laravel\Dusk\Browser;

/**
 * Cookie-based authentication strategy with form login fallback.
 *
 * Flow on authenticate():
 *   1. Check if a cookie file exists for this profile
 *   2. If yes - inject cookies into the browser session and reload
 *   3. Check if the session is actually valid (authCheckSelector present)
 *   4. If cookies were stale or missing - run FormLoginStrategy
 *   5. Save the fresh cookies to disk for next time
 *
 * This means the first run logs in via form, every subsequent run
 * restores the session from disk without touching the login page.
 *
 * Cookie storage: storage/app/scraper/cookies/{profileName}.json
 */
class CookieAuthStrategy implements AuthStrategyInterface
{
    public function __construct(
        private readonly Browser $browser,
        private readonly string $profileName,
        private readonly string $baseUrl,
        private readonly string $authCheckSelector,
        private readonly FormLoginStrategy $loginStrategy,
        private readonly ?string $authDomain = null,
    ) {}

    /**
     * Check if the browser session is currently authenticated.
     *
     * We look for a DOM element that only appears when logged in
     * (e.g. a user menu, avatar, or account link).
     * If it's present - we are logged in.
     */
    public function isAuthenticated(): bool
    {
        return $this->browser->element($this->authCheckSelector) !== null;
    }

    /**
     * Authenticate the browser session.
     *
     * Tries cookie restoration first - much faster than form login.
     * Falls back to form login if cookies are missing or expired.
     * Always saves fresh cookies after successful form login.
     */
    public function authenticate(): void
    {
        if ($this->tryRestoreFromCookies()) {
            return;
        }

        $this->loginStrategy->login();
        $this->saveCookies();
    }

    /**
     * Inject saved cookies into the browser session without any navigation or auth check.
     *
     * Use this in CrawlDetailPageJob — cookies were already refreshed by CrawlIndexPageJob.
     * Navigates to base URL once to set the correct domain context, then injects all cookies.
     * Returns false if no cookie file exists — caller should handle gracefully.
     */
    public function restoreCookiesOnly(): bool
    {
        $path = $this->cookiePath();

        if (! file_exists($path)) {
            return false;
        }

        $cookies = json_decode(file_get_contents($path), true);

        if (empty($cookies)) {
            return false;
        }

        $this->browser->visit($this->baseUrl);

        foreach ($cookies as $cookie) {
            try {
                $this->browser->driver->manage()->addCookie($cookie);
            } catch (\Throwable) {
                // Skip malformed cookies silently
            }
        }

        return true;
    }

    /**
     * Refresh an expired session.
     *
     * Delegates to authenticate() - the same cookie-first logic applies.
     * If cookies are still valid this is nearly instant.
     * If expired, a new form login runs and fresh cookies are saved.
     */
    public function refresh(): void
    {
        $this->authenticate();
    }

    /**
     * Attempt to restore the session from a saved cookie file.
     *
     * Returns true if cookies were loaded AND the session is authenticated.
     * Returns false if the file is missing, empty, or cookies are expired.
     */
    private function tryRestoreFromCookies(): bool
    {
        $path = $this->cookiePath();

        if (! file_exists($path)) {
            return false;
        }

        $cookies = json_decode(file_get_contents($path), true);

        if (empty($cookies)) {
            return false;
        }

        // If an SSO domain is configured, inject its cookies first while on that domain.
        // WebDriver rejects cookies whose domain doesn't match the current page.
        if ($this->authDomain !== null) {
            $this->browser->visit($this->authDomain);

            foreach ($cookies as $cookie) {
                if (empty($cookie['name']) || ! array_key_exists('value', $cookie)) {
                    continue;
                }

                try {
                    $this->browser->driver->manage()->addCookie($cookie);
                } catch (\Throwable) {
                    // Skip cookies that don't belong to this domain
                }
            }
        }

        // Now inject all cookies on the main domain.
        $this->browser->visit($this->baseUrl);

        foreach ($cookies as $cookie) {
            if (empty($cookie['name']) || ! array_key_exists('value', $cookie)) {
                continue;
            }

            try {
                $this->browser->driver->manage()->addCookie($cookie);
            } catch (\Throwable) {
                // Skip cookies that don't belong to this domain
            }
        }

        // Reload with cookies active so the server recognizes the session
        $this->browser->refresh();

        return $this->isAuthenticated();
    }

    /**
     * Dump the current browser cookies to disk.
     *
     * Called immediately after a successful form login.
     * All cookies are stored - the session cookie is among them.
     */
    private function saveCookies(): void
    {
        $path = $this->cookiePath();
        $dir = dirname($path);

        if (! is_dir($dir)) {
            mkdir($dir, 0755, recursive: true);
        }

        $mainCookies = array_map(
            fn ($cookie) => $cookie->toArray(),
            $this->browser->driver->manage()->getCookies(),
        );

        $ssoCookies = [];

        if ($this->authDomain !== null) {
            $this->browser->visit($this->authDomain);
            $ssoCookies = array_map(
                fn ($cookie) => $cookie->toArray(),
                $this->browser->driver->manage()->getCookies(),
            );
        }

        $merged = collect([...$mainCookies, ...$ssoCookies])
            ->keyBy('name')
            ->values()
            ->toArray();

        file_put_contents(
            $path,
            json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        );
    }

    /**
     * Resolve the cookie file path for this profile.
     *
     * Convention: storage/app/scraper/cookies/{profileName}.json
     * Each site has its own file - no cross-contamination.
     */
    private function cookiePath(): string
    {
        return storage_path("app/scraper/cookies/{$this->profileName}.json");
    }
}
