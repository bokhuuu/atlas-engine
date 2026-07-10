<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\MarketRegistry;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reads the first URL segment to determine the current market context.
 * Uses segment(1) instead of a route parameter to avoid Laravel's route
 * model binding interference with other route parameters like {user}.
 * Stamps the resolved market slug onto the request and service container.
 * Returns 404 for unknown market slugs.
 */
class MarketMiddleware
{
    /**
     * Create a new middleware instance.
     */
    public function __construct(private readonly MarketRegistry $registry) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->segment(1);

        if (! $slug || ! $this->registry->isValid($slug)) {
            abort(404);
        }

        $request->attributes->set('market', $slug);

        app()->instance('market', $slug);

        return $next($request);
    }
}
