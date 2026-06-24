import { Head } from '@inertiajs/react';
import { useCallback, useEffect, useRef, useState } from 'react';

import ScraperCategoryCard from '@/components/scraper/ScraperCategoryCard';
import type {
    ScraperCategory,
    ScraperRunStatus,
} from '@/components/scraper/ScraperCategoryCard';
import AppLayout from '@/layouts/app-layout';
import { fetchJson, postJson } from '@/lib/scraper-api';

import type { BreadcrumbItem } from '@/types';

interface Stats {
    total_users: number;
    active_users: number;
    inactive_users: number;
    trashed_users: number;
    total_activities: number;
    today_activities: number;
}

interface Props {
    stats: Stats;
    scraperCategories: ScraperCategory[];
    grandTotalListings: number;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/admin/dashboard' },
];

const POLL_INTERVAL_MS = 5000;

export default function Dashboard({
    scraperCategories,
    grandTotalListings,
}: Props) {
    const [statuses, setStatuses] = useState<Record<string, ScraperRunStatus>>(
        {},
    );
    const [sharedPages, setSharedPages] = useState(1);
    const [isBusy, setIsBusy] = useState(false);

    const statusesRef = useRef(statuses);
    statusesRef.current = statuses;

    const refreshStatuses = useCallback(async () => {
        try {
            const data = await fetchJson<{ statuses: ScraperRunStatus[] }>(
                '/api/v1/scrape/status-all',
            );
            const map: Record<string, ScraperRunStatus> = {};

            data.statuses.forEach((s) => {
                map[s.source] = s;
            });

            setStatuses(map);
        } catch {
            // Polling failure is non-fatal - next interval tries again.
        }
    }, []);

    useEffect(() => {
        refreshStatuses();
    }, [refreshStatuses]);

    useEffect(() => {
        const interval = setInterval(() => {
            const anyRunning = Object.values(statusesRef.current).some(
                (s) => s.state === 'running',
            );

            if (anyRunning) {
                refreshStatuses();
            }
        }, POLL_INTERVAL_MS);

        return () => clearInterval(interval);
    }, [refreshStatuses]);

    const handleStart = async (source: string, pages: number) => {
        setIsBusy(true);

        try {
            await postJson('/api/v1/scrape/start', { source, pages });
            await refreshStatuses();
        } finally {
            setIsBusy(false);
        }
    };

    const handleStartAll = async () => {
        setIsBusy(true);

        try {
            await postJson('/api/v1/scrape/start-all', { pages: sharedPages });
            await refreshStatuses();
        } finally {
            setIsBusy(false);
        }
    };

    const statusList = Object.values(statuses);
    const liveGrandTotal =
        statusList.length > 0
            ? statusList.reduce((sum, s) => sum + (s.total_listings ?? 0), 0)
            : grandTotalListings;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="p-6">
                <div className="mb-6 flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">
                            Scraper Panel
                        </h1>
                    </div>
                    <div className="text-right">
                        <p className="text-2xl leading-none font-bold">
                            {liveGrandTotal.toLocaleString()}
                        </p>
                        <p className="text-xs text-muted-foreground">
                            total listings across all categories
                        </p>
                    </div>
                </div>

                <div className="mb-6 flex items-center gap-3 rounded-lg border p-4">
                    <label className="text-sm text-muted-foreground">
                        All Categories
                    </label>
                    <input
                        type="number"
                        min={1}
                        value={sharedPages}
                        onChange={(e) => setSharedPages(Number(e.target.value))}
                        className="w-16 rounded border px-2 py-1"
                    />
                    <button
                        onClick={handleStartAll}
                        disabled={isBusy}
                        className="cursor-pointer rounded bg-primary px-3 py-1 text-sm text-primary-foreground disabled:opacity-50"
                    >
                        Start All
                    </button>

                    <a
                        href="/api/v1/exports"
                        className="rounded border px-3 py-1 text-sm"
                    >
                        Download All
                    </a>
                </div>

                <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    {scraperCategories.map((category) => (
                        <ScraperCategoryCard
                            key={category.source}
                            category={category}
                            status={
                                statuses[category.source] ?? {
                                    source: category.source,
                                    state: 'never_run',
                                }
                            }
                            onStart={handleStart}
                            isBusy={isBusy}
                        />
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
