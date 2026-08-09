<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    /**
     * Clear the current user's chat history for this market.
     * Deletes all laragent_messages for the session key derived from
     * the authenticated user ID and current market slug.
     */
    public function clearHistory(Request $request): JsonResponse
    {
        $market = app('market') ?? 'yerevan';
        $userId = $request->user()->id;
        $sessionKey = "chatHistory_PropertyAssistantAgent_atlas_assistant_{$userId}_{$market}";

        DB::table('laragent_messages')
            ->where('session_key', $sessionKey)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Chat history cleared.']);
    }
}
