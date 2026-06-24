import { useState } from 'react';

export interface ScraperCategory {
    source: string;
    label: string;
    default_pages: number;
    total_listings: number;
}

export interface ScraperRunStatus {
    source: string;
    state: string;
    run_id?: number;
    started_at?: string | null;
    finished_at?: string | null;
    scraped_pages?: number | null;
    saved_listings?: number | null;
    total_listings?: number;
}

interface Props {
    category: ScraperCategory;
    status: ScraperRunStatus;
    onStart: (source: string, pages: number) => void;
    isBusy: boolean;
}

const STATE_COLORS: Record<string, string> = {
    running: 'bg-blue-100 text-blue-700',
    completed: 'bg-green-100 text-green-700',
    failed: 'bg-red-100 text-red-700',
    cancelled: 'bg-gray-100 text-gray-600',
    never_run: 'bg-gray-100 text-gray-500',
};

export default function ScraperCategoryCard({
    category,
    status,
    onStart,
    isBusy,
}: Props) {
    const [pages, setPages] = useState(category.default_pages);

    const isRunning = status.state === 'running';

    // Prefer the polled total (status-all) since it's kept live; fall back
    // to the initial page-load value before the first poll resolves.
    const totalListings = status.total_listings ?? category.total_listings;

    return (
        <div className="flex gap-4 rounded-lg border p-4">
            <div className="flex-1">
                <div className="flex items-center justify-between">
                    <h3 className="text-sm font-medium">{category.label}</h3>
                    <span
                        className={`rounded px-2 py-0.5 text-xs ${STATE_COLORS[status.state] ?? 'bg-gray-100 text-gray-500'}`}
                    >
                        {status.state.replace('_', ' ')}
                    </span>
                </div>

                <div className="mt-3 flex items-center gap-2 text-sm">
                    <label
                        htmlFor={`pages-${category.source}`}
                        className="text-muted-foreground"
                    >
                        Pages:
                    </label>
                    <input
                        id={`pages-${category.source}`}
                        type="number"
                        min={1}
                        value={pages}
                        onChange={(e) => setPages(Number(e.target.value))}
                        disabled={isRunning}
                        className="w-16 rounded border px-2 py-1"
                    />
                </div>

                <div className="mt-3">
                    <button
                        onClick={() => onStart(category.source, pages)}
                        disabled={isRunning || isBusy}
                        className="cursor-pointer rounded bg-primary px-3 py-1 text-sm text-primary-foreground disabled:opacity-50"
                    >
                        Start
                    </button>

                    <a
                        href={`/api/v1/exports/${category.source}`}
                        className="rounded border px-3 py-1 text-sm"
                    >
                        Download
                    </a>
                </div>

                {status.finished_at && (
                    <p className="mt-3 text-xs text-muted-foreground">
                        Last run: {status.scraped_pages} pages,{' '}
                        {status.saved_listings} new listings saved
                    </p>
                )}
            </div>

            <div className="flex w-28 shrink-0 flex-col items-start justify-center border-l pl-4">
                <span className="text-3xl leading-none font-bold">
                    {totalListings.toLocaleString()}
                </span>
                <span className="mt-1 text-xs text-muted-foreground">
                    total listings
                </span>
            </div>
        </div>
    );
}
