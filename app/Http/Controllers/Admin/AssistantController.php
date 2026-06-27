<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Serves the AI Assistant page (Screen 3).
 *
 * Passes no server-side data - the page is fully client-driven,
 * fetching from the SSE stream endpoint and the feedback API directly.
 */
class AssistantController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/assistant');
    }
}
