<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 * Base controller for all Atlas HTTP controllers.
 * Provides shared helpers available to every controller in the application.
 */
abstract class Controller
{
    /**
     * Returns the current market slug from the first URL segment.
     * Falls back to the default market if not present.
     */
    protected function currentMarket(): string
    {
        return request()->segment(1) ?? config('scraper.default_market', 'yerevan');
    }
}
