<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\AI\Models\AiFeedback;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Displays AI feedback votes for admin review.
 * Used to identify patterns in thumbs-down responses
 * and improve the system prompt over time.
 */
class AiFeedbackController extends Controller
{
    public function index(Request $request): Response
    {
        $vote = $request->input('vote');

        $feedback = AiFeedback::with('user')
            ->when($vote !== null, fn ($q) => $q->where('vote', (int) $vote))
            ->latest()
            ->paginate(config('larakit.pagination'));

        $summary = [
            'total' => AiFeedback::count(),
            'positive' => AiFeedback::where('vote', 1)->count(),
            'negative' => AiFeedback::where('vote', -1)->count(),
        ];

        return Inertia::render('admin/ai/feedback', [
            'feedback' => $feedback,
            'summary' => $summary,
            'filters' => ['vote' => $vote],
        ]);
    }
}
