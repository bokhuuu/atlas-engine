import { Head, router } from '@inertiajs/react';
import { ThumbsDown, ThumbsUp } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'AI Feedback', href: '/admin/ai-feedback' },
];

interface FeedbackItem {
    id: number;
    vote: 1 | -1;
    response_text: string;
    created_at: string;
    user?: { name: string };
}

interface Paginated {
    data: FeedbackItem[];
    current_page: number;
    last_page: number;
    next_page_url: string | null;
    prev_page_url: string | null;
}

interface Props {
    feedback: Paginated;
    summary: { total: number; positive: number; negative: number };
    filters: { vote: string | null };
}

export default function AiFeedback({ feedback, summary, filters }: Props) {
    const positivePercent =
        summary.total > 0
            ? Math.round((summary.positive / summary.total) * 100)
            : 0;

    const setVoteFilter = (vote: string | null) => {
        router.get('/admin/ai-feedback', vote ? { vote } : {}, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="AI Feedback" />
            <div className="p-6">
                <div className="mb-6">
                    <h1 className="text-2xl font-semibold">AI Feedback</h1>
                    <p className="text-sm text-muted-foreground">
                        Review analyst feedback to improve the AI assistant's
                        system prompt.
                    </p>
                </div>

                {/* Summary */}
                <div className="mb-6 grid grid-cols-3 gap-4">
                    <div className="rounded-lg border p-4 text-center">
                        <p className="text-2xl font-bold">{summary.total}</p>
                        <p className="text-xs text-muted-foreground">
                            Total responses rated
                        </p>
                    </div>
                    <div className="rounded-lg border p-4 text-center">
                        <p className="text-2xl font-bold text-emerald-500">
                            {summary.positive} 👍
                        </p>
                        <p className="text-xs text-muted-foreground">
                            {positivePercent}% positive
                        </p>
                    </div>
                    <div className="rounded-lg border p-4 text-center">
                        <p className="text-2xl font-bold text-red-500">
                            {summary.negative} 👎
                        </p>
                        <p className="text-xs text-muted-foreground">
                            {100 - positivePercent}% negative
                        </p>
                    </div>
                </div>

                {/* Filters */}
                <div className="mb-4 flex gap-2">
                    {[
                        { label: 'All', value: null },
                        { label: '👍 Positive', value: '1' },
                        { label: '👎 Negative', value: '-1' },
                    ].map((f) => (
                        <button
                            key={f.label}
                            onClick={() => setVoteFilter(f.value)}
                            className={`rounded-lg border px-3 py-1.5 text-sm transition-colors ${
                                filters.vote === f.value
                                    ? 'bg-primary text-primary-foreground'
                                    : 'hover:bg-accent'
                            }`}
                        >
                            {f.label}
                        </button>
                    ))}
                </div>

                {/* Table */}
                <div className="rounded-lg border">
                    {feedback.data.length === 0 ? (
                        <div className="p-8 text-center text-sm text-muted-foreground">
                            No feedback recorded yet.
                        </div>
                    ) : (
                        <div className="divide-y divide-border">
                            {feedback.data.map((item) => (
                                <div key={item.id} className="flex gap-4 p-4">
                                    <div className="mt-0.5 shrink-0">
                                        {item.vote === 1 ? (
                                            <ThumbsUp className="h-4 w-4 text-emerald-500" />
                                        ) : (
                                            <ThumbsDown className="h-4 w-4 text-red-500" />
                                        )}
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <p className="text-sm leading-relaxed text-foreground">
                                            {item.response_text}
                                        </p>
                                        <p className="mt-1 text-xs text-muted-foreground">
                                            {item.user?.name ?? 'Unknown'} ·{' '}
                                            {new Date(
                                                item.created_at,
                                            ).toLocaleDateString('en-GB', {
                                                day: 'numeric',
                                                month: 'short',
                                                year: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit',
                                            })}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                {/* Pagination */}
                {feedback.last_page > 1 && (
                    <div className="mt-4 flex justify-center gap-2">
                        <button
                            onClick={() =>
                                feedback.prev_page_url &&
                                router.get(feedback.prev_page_url)
                            }
                            disabled={!feedback.prev_page_url}
                            className="rounded border px-3 py-1 text-sm disabled:opacity-40"
                        >
                            Previous
                        </button>
                        <span className="px-3 py-1 text-sm text-muted-foreground">
                            {feedback.current_page} / {feedback.last_page}
                        </span>
                        <button
                            onClick={() =>
                                feedback.next_page_url &&
                                router.get(feedback.next_page_url)
                            }
                            disabled={!feedback.next_page_url}
                            className="rounded border px-3 py-1 text-sm disabled:opacity-40"
                        >
                            Next
                        </button>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
