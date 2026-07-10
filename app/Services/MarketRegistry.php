<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Provides access to the market registry defined in config/scraper.php.
 * Single source of truth for all market lookups across the application.
 */
class MarketRegistry
{
    /**
     * Returns all markets from config as an associative array keyed by slug.
     */
    public function all(): array
    {
        return config('scraper.markets', []);
    }

    /**
     * Returns all valid market slugs (e.g. ['yerevan', 'baku', 'tbilisi']).
     */
    public function allSlugs(): array
    {
        return array_keys($this->all());
    }

    /**
     * Returns true if the given slug matches a configured market.
     */
    public function isValid(string $slug): bool
    {
        return array_key_exists($slug, $this->all());
    }

    /**
     * Returns the full config array for a given market slug, or null if not found.
     */
    public function fromSlug(string $slug): ?array
    {
        return $this->all()[$slug] ?? null;
    }

    /**
     * Returns the market slug that owns the given source prefix, or null if not found.
     */
    public function fromSourcePrefix(string $prefix): ?string
    {
        foreach ($this->all() as $slug => $market) {
            if (in_array($prefix, $market['source_prefixes'], true)) {
                return $slug;
            }
        }

        return null;
    }

    /**
     * Returns the default market slug from config.
     */
    public function defaultSlug(): string
    {
        return config('scraper.default_market', 'yerevan');
    }
}
