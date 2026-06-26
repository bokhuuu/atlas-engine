<?php

declare(strict_types=1);

namespace App\Modules\AI\Api;

use App\Http\Controllers\Controller;
use App\Modules\AI\Models\AiFeedback;
use App\Modules\AI\Requests\StoreFeedbackRequest;
use Illuminate\Http\JsonResponse;

/**
 * Stores user feedback votes on AI responses.
 *
 * Endpoint: POST /api/v1/ai/feedback
 * Accepts vote (1 or -1), the AI response text and an optional comment.
 * Session ID captured from request header or falls back to IP address.
 * User ID populated from auth when available.
 */
class FeedbackController extends Controller
{
    /**
     * Store a feedback vote for an AI response.
     * Returns the created feedback record ID as confirmation.
     */
    public function store(StoreFeedbackRequest $request): JsonResponse
    {
        $feedback = AiFeedback::create([
            'session_id' => $request->header('X-Session-ID', $request->ip()),
            'user_id' => auth()->id(),
            'response_text' => $request->input('response_text'),
            'vote' => $request->input('vote'),
            'comment' => $request->input('comment'),
        ]);

        return response()->json([
            'message' => 'Feedback recorded.',
            'id' => $feedback->id,
        ], 201);
    }
}
