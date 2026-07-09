import { Head } from '@inertiajs/react';
import { useCallback, useEffect, useRef, useState } from 'react';
import {
    LineChart,
    Line,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
    ResponsiveContainer,
} from 'recharts';
import ScraperCategoryCard from '@/components/scraper/ScraperCategoryCard';
import type {
    ScraperCategory,
    ScraperRunStatus,
} from '@/components/scraper/ScraperCategoryCard';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useMarket } from '@/hooks/use-market';
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

interface TrendPoint {
    date: string;
    new_listings: number;
    cumulative_listings: number;
    encountered: number;
}

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

    const [trendSource, setTrendSource] = useState<string>(
        scraperCategories[0]?.source ?? '',
    );
    const [trendData, setTrendData] = useState<TrendPoint[]>([]);
    const [trendLoading, setTrendLoading] = useState(false);

    const [anyRunning, setAnyRunning] = useState(false);

    const fetchTrend = useCallback(async (source: string) => {
        if (!source) {
            return;
        }

        setTrendLoading(true);

        try {
            const data = await fetchJson<{ series: TrendPoint[] }>(
                `/api/v1/scrape/trend?source=${encodeURIComponent(source)}`,
            );
            setTrendData(data.series);
        } catch {
            setTrendData([]);
        } finally {
            setTrendLoading(false);
        }
    }, []);

    useEffect(() => {
        fetchTrend(trendSource);
    }, [trendSource, fetchTrend]);

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
            if (document.hidden) {
                return;
            }

            const anyRunning = Object.values(statusesRef.current).some(
                (s) => s.state === 'running',
            );

            if (anyRunning) {
                setAnyRunning(true);
                refreshStatuses();
            } else {
                setAnyRunning(false);
            }
        }, POLL_INTERVAL_MS);

        return () => clearInterval(interval);
    }, [refreshStatuses]);

    const { adminUrl } = useMarket();
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: adminUrl('/dashboard') },
    ];

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
                            Scraper Control
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            manage scrapes and download exports.
                        </p>
                    </div>
                    <div className="text-right">
                        <p className="text-2xl leading-none font-bold">
                            {liveGrandTotal.toLocaleString()}
                        </p>
                        <p className="text-xs text-muted-foreground">
                            total listings across all categories
                        </p>
                        <p className="text-xs text-muted-foreground/60">
                            new per run excludes duplicates already in the
                            database
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
                        disabled={isBusy || anyRunning}
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

                {/* Trend Chart */}
                <div className="mt-8">
                    <div className="mb-4 flex items-center justify-between">
                        <div>
                            <h2 className="text-lg font-semibold">
                                Listing Volume Over Time
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                Cumulative listings and new additions per scrape
                                run.
                            </p>
                        </div>
                        <Select
                            value={trendSource}
                            onValueChange={setTrendSource}
                        >
                            <SelectTrigger className="w-56">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                {scraperCategories.map((cat) => (
                                    <SelectItem
                                        key={cat.source}
                                        value={cat.source}
                                    >
                                        {cat.label}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>

                    <div className="rounded-lg border p-4">
                        {trendLoading ? (
                            <div className="flex h-48 items-center justify-center text-sm text-muted-foreground">
                                Loading…
                            </div>
                        ) : trendData.length === 0 ? (
                            <div className="flex h-48 items-center justify-center text-sm text-muted-foreground">
                                No completed scrape runs yet for this category.
                            </div>
                        ) : (
                            <ResponsiveContainer width="100%" height={280}>
                                <LineChart
                                    data={trendData}
                                    margin={{
                                        top: 4,
                                        right: 16,
                                        left: 24,
                                        bottom: 4,
                                    }}
                                >
                                    <CartesianGrid
                                        strokeDasharray="3 3"
                                        stroke="#333333"
                                    />
                                    <XAxis
                                        dataKey="date"
                                        tick={{ fontSize: 11, fill: '#888888' }}
                                    />
                                    <YAxis
                                        tick={{ fontSize: 11, fill: '#888888' }}
                                        width={55}
                                    />
                                    <Tooltip
                                        contentStyle={{
                                            background: '#1a1a1a',
                                            border: '1px solid #333333',
                                            borderRadius: '6px',
                                            fontSize: '12px',
                                            color: '#ffffff',
                                        }}
                                    />
                                    <Legend
                                        wrapperStyle={{ fontSize: '12px' }}
                                    />
                                    <Line
                                        type="monotone"
                                        dataKey="cumulative_listings"
                                        name="Total Listings"
                                        stroke="hsl(var(--primary))"
                                        strokeWidth={2}
                                        dot={{ r: 3 }}
                                        activeDot={{ r: 5 }}
                                    />
                                    <Line
                                        type="monotone"
                                        dataKey="new_listings"
                                        name="New Per Run"
                                        stroke="hsl(var(--muted-foreground))"
                                        strokeWidth={1.5}
                                        strokeDasharray="4 2"
                                        dot={{ r: 2 }}
                                    />
                                </LineChart>
                            </ResponsiveContainer>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
