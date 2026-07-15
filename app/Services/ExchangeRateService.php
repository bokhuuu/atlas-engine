<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Fetches and stores USD exchange rates for all Caucasus market currencies.
 *
 * Rates are fetched from open.er-api.com (free, no auth, global CDN -
 * reliable from the Frankfurt VPS). Stored in the settings table so
 * stat queries never depend on an external API being up at query time.
 *
 * getRate() reads from settings first, falls back to config values
 * (approximate hardcoded rates) if settings are empty - e.g. on first
 * boot before atlas:fetch-exchange-rates has run.
 *
 * Supported currencies: AMD (Armenia), AZN (Azerbaijan), GEL (Georgia).
 * USD listings pass through unchanged - no conversion needed.
 */
class ExchangeRateService
{
    private const API_URL = 'https://open.er-api.com/v6/latest/USD';

    private const CURRENCY_KEYS = [
        'AMD' => 'amd_usd_rate',
        'AZN' => 'azn_usd_rate',
        'GEL' => 'gel_usd_rate',
    ];

    private const CONFIG_FALLBACKS = [
        'AMD' => 'scraper.exchange_rates.amd_usd',
        'AZN' => 'scraper.exchange_rates.azn_usd',
        'GEL' => 'scraper.exchange_rates.gel_usd',
    ];

    /**
     * Fetch latest rates from the exchange rate API and store them
     * in the settings table. Returns true on success, false on failure.
     *
     * Failures are logged but never throw - a failed rate fetch must
     * never break a scrape run or scheduled job.
     */
    public function fetch(): bool
    {
        try {
            $response = Http::timeout(10)->get(self::API_URL);

            if (! $response->successful()) {
                Log::warning('ExchangeRateService: API request failed', [
                    'status' => $response->status(),
                ]);

                return false;
            }

            $data = $response->json();

            if (($data['result'] ?? '') !== 'success') {
                Log::warning('ExchangeRateService: API returned non-success result', [
                    'result' => $data['result'] ?? 'unknown',
                ]);

                return false;
            }

            $rates = $data['rates'] ?? [];

            foreach (self::CURRENCY_KEYS as $currency => $settingKey) {
                if (! isset($rates[$currency])) {
                    Log::warning("ExchangeRateService: {$currency} not found in API response");

                    continue;
                }

                $rate = (float) $rates[$currency];

                DB::table('settings')->updateOrInsert(
                    ['key' => $settingKey],
                    [
                        'value' => (string) $rate,
                        'group' => 'market',
                        'type' => 'text',
                        'label' => "{$currency}/USD Exchange Rate",
                        'description' => "Units of {$currency} per 1 USD. Fetched daily from open.er-api.com.",
                        'order' => 0,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('ExchangeRateService: unexpected error', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get the exchange rate for a currency (units per 1 USD).
     *
     * Reads from settings table first. Falls back to config if no stored
     * rate exists yet. USD always returns 1.0 - no conversion needed.
     *
     * To convert a price to USD: price_in_usd = price / getRate('AMD')
     */
    public function getRate(string $currency): float
    {
        $currency = strtoupper($currency);

        if ($currency === 'USD') {
            return 1.0;
        }

        $settingKey = self::CURRENCY_KEYS[$currency] ?? null;

        if ($settingKey) {
            $setting = DB::table('settings')->where('key', $settingKey)->first();

            if ($setting && $setting->value) {
                return (float) $setting->value;
            }
        }

        $configKey = self::CONFIG_FALLBACKS[$currency] ?? null;

        if ($configKey) {
            return (float) config($configKey, 1.0);
        }

        Log::warning("ExchangeRateService: no rate found for currency {$currency}, returning 1.0");

        return 1.0;
    }
}
