<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Illuminate\Console\Command;

/**
 * Fetches the latest USD exchange rates and stores them in the settings table.
 *
 * Runs daily via the scheduler - see App\Console\Kernel or routes/console.php.
 * Can also be triggered manually: php artisan atlas:fetch-exchange-rates
 *
 * Delegates all logic to ExchangeRateService. This command only handles
 * CLI input/output and exit codes.
 */
class FetchExchangeRatesCommand extends Command
{
    protected $signature = 'atlas:fetch-exchange-rates';

    protected $description = 'Fetch latest AMD, AZN and GEL exchange rates and store in settings';

    public function __construct(private readonly ExchangeRateService $exchangeRateService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Fetching exchange rates...');

        $success = $this->exchangeRateService->fetch();

        if (! $success) {
            $this->error('Failed to fetch exchange rates. Check logs for details.');

            return self::FAILURE;
        }

        $this->info('Exchange rates updated successfully.');

        return self::SUCCESS;
    }
}
