import { Head } from '@inertiajs/react';
import { TrendingDown, XCircle, Clock } from 'lucide-react';
import { useEffect, useState } from 'react';
import {
    LineChart,
    Line,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
} from 'recharts';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useMarket } from '@/hooks/use-market';
import AppLayout from '@/layouts/app-layout';
import { fetchJson } from '@/lib/scraper-api';
import type { BreadcrumbItem } from '@/types';

interface Category {
    source: string;
    label: string;
}

interface DistrictRow {
    district: string;
    listing_count: number;
    avg_price_per_sqm: number | null;
    median_price_per_sqm: number | null;
    price_reductions: number;
    disappeared_count: number;
    stale_count: number;
}

interface YieldRow {
    district: string;
    sale_median: number | null;
    rent_median: number | null;
    yield_pct: number | null;
}

interface VolumePoint {
    period: string;
    sale?: number;
    rent?: number;
}

interface PricePoint {
    period: string;
    median_price_per_sqm: number;
}

interface Props {
    categories: Category[];
}

/**
 * Background intensity scales with median price/sqm rank among the
 * districts currently shown - a direct comparison of an existing
 * statistic, not an invented "hot district" label.
 */
function intensityClass(rank: number, total: number): string {
    if (total <= 1) {
        return 'bg-primary/10';
    }

    const pct = rank / (total - 1);

    if (pct <= 0.2) {
        return 'bg-primary/10';
    }

    if (pct <= 0.4) {
        return 'bg-primary/20';
    }

    if (pct <= 0.6) {
        return 'bg-primary/30';
    }

    if (pct <= 0.8) {
        return 'bg-primary/40';
    }

    return 'bg-primary/50';
}

export default function MarketStats({ categories }: Props) {
    const { adminUrl, market } = useMarket();

    const [selectedSource, setSelectedSource] = useState(
        categories[0]?.source ?? '',
    );
    const [result, setResult] = useState<{
        source: string;
        rows: DistrictRow[];
    } | null>(null);
    const [errorSource, setErrorSource] = useState<string | null>(null);
    const [summary, setSummary] = useState<string | null>(null);
    const [summarySource, setSummarySource] = useState<string>('');
    const [yieldData, setYieldData] = useState<Record<string, YieldRow>>({});
    const [groupBy, setGroupBy] = useState<'month' | 'quarter'>('month');
    const [volumeData, setVolumeData] = useState<VolumePoint[]>([]);
    const [priceData, setPriceData] = useState<PricePoint[]>([]);
    const [trendsLoading, setTrendsLoading] = useState(false);

    useEffect(() => {
        let active = true;

        Promise.all([
            fetchJson<{ series: VolumePoint[] }>(
                `/api/v1/trends/volume?market=${market}&groupBy=${groupBy}`,
            ),
            fetchJson<{ series: PricePoint[] }>(
                `/api/v1/trends/price?market=${market}&groupBy=${groupBy}`,
            ),
        ])
            .then(([volumeRes, priceRes]) => {
                if (active) {
                    setVolumeData(volumeRes.series);
                    setPriceData(priceRes.series);
                    setTrendsLoading(false);
                }
            })
            .catch(() => {
                if (active) {
                    setVolumeData([]);
                    setPriceData([]);
                    setTrendsLoading(false);
                }
            });

        return () => {
            active = false;
        };
    }, [market, groupBy]);

    useEffect(() => {
        if (!selectedSource) {
            return;
        }

        let active = true;

        fetchJson<{ source: string; data: DistrictRow[] }>(
            `/api/v1/listings/stats?source=${selectedSource}`,
        )
            .then((res) => {
                if (active) {
                    setResult({ source: selectedSource, rows: res.data });
                    setErrorSource(null);
                }
            })
            .catch(() => {
                if (active) {
                    setErrorSource(selectedSource);
                }
            });

        return () => {
            active = false;
        };
    }, [selectedSource]);

    useEffect(() => {
        if (!selectedSource) {
            return;
        }

        let active = true;

        fetchJson<{ summary: string | null }>(
            `/api/v1/listings/summary?source=${selectedSource}`,
        )
            .then((res) => {
                if (active) {
                    setSummary(res.summary);
                    setSummarySource(selectedSource);
                }
            })
            .catch(() => {
                if (active) {
                    setSummary(null);
                    setSummarySource(selectedSource);
                }
            });

        return () => {
            active = false;
        };
    }, [selectedSource]);

    useEffect(() => {
        const pair = selectedSource.endsWith('_commercial_sale')
            ? 'commercial'
            : null;

        let active = true;

        if (pair) {
            fetchJson<{ pair: string; rows: YieldRow[] }>(
                `/api/v1/listings/yield?pair=${pair}&market=${market}`,
            )
                .then((res) => {
                    if (active) {
                        const map: Record<string, YieldRow> = {};

                        res.rows.forEach((r) => {
                            map[r.district] = r;
                        });

                        setYieldData(map);
                    }
                })
                .catch(() => {
                    if (active) {
                        setYieldData({});
                    }
                });
        }

        return () => {
            active = false;
        };
    }, [selectedSource, market]);

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: adminUrl('/dashboard') },
    ];

    const error = errorSource === selectedSource;
    const rows = result?.source === selectedSource ? result.rows : null;
    const showYield = selectedSource.endsWith('_commercial_sale');

    const sortedRows = rows
        ? [...rows].sort(
              (a, b) =>
                  (b.median_price_per_sqm ?? 0) - (a.median_price_per_sqm ?? 0),
          )
        : [];

    if (categories.length === 0) {
        return (
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Market Stats" />
                <div className="flex flex-col items-center justify-center py-32 text-center">
                    <p className="mb-2 text-2xl">📊</p>
                    <h2 className="mb-1 text-lg font-semibold">
                        No data available yet
                    </h2>
                    <p className="text-sm text-muted-foreground">
                        Data collection for the{' '}
                        {market.charAt(0).toUpperCase() + market.slice(1)}{' '}
                        market has not started yet.
                    </p>
                </div>
            </AppLayout>
        );
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Market Stats" />
            <div className="p-6">
                <div className="mb-6">
                    <h1 className="text-2xl font-semibold">
                        Pricing Breakdown
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        district-level pricing breakdown by category.
                    </p>
                </div>

                <div className="mb-6">
                    <Select
                        value={selectedSource}
                        onValueChange={setSelectedSource}
                    >
                        <SelectTrigger className="w-64">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            {categories.map((cat) => (
                                <SelectItem key={cat.source} value={cat.source}>
                                    {cat.label}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>

                {error && (
                    <p className="text-sm text-muted-foreground">
                        Could not load market stats. Try again shortly.
                    </p>
                )}

                {!error && rows === null && (
                    <p className="text-sm text-muted-foreground">Loading...</p>
                )}

                {!error && rows !== null && rows.length === 0 && (
                    <p className="text-sm text-muted-foreground">
                        No data yet for this category - run a scrape from the
                        Dashboard to populate it.
                    </p>
                )}

                {!error && rows !== null && rows.length > 0 && (
                    <div className="overflow-x-auto rounded-lg border">
                        <table className="w-full min-w-150 text-sm">
                            <thead className="border-b bg-muted/50 text-left">
                                <tr>
                                    <th className="px-4 py-2">District</th>
                                    <th className="px-4 py-2">Listings</th>
                                    <th className="px-4 py-2">Avg Price/sqm</th>
                                    <th className="px-4 py-2">
                                        Median Price/sqm
                                    </th>
                                    <th className="px-4 py-2">
                                        Price Reductions
                                    </th>
                                    <th className="px-4 py-2">Disappeared</th>
                                    <th className="px-4 py-2">Stale</th>
                                    {showYield && (
                                        <th className="px-4 py-2">
                                            Est. Yield/yr{' '}
                                            <span className="text-[10px] font-normal text-muted-foreground/60">
                                                (rent ÷ sale)
                                            </span>
                                        </th>
                                    )}
                                </tr>
                            </thead>
                            <tbody>
                                {sortedRows.map((row, i) => (
                                    <tr
                                        key={row.district}
                                        className={`border-b last:border-0 ${intensityClass(i, sortedRows.length)}`}
                                    >
                                        <td className="px-4 py-2 font-medium">
                                            {row.district}
                                        </td>
                                        <td className="px-4 py-2">
                                            {row.listing_count}
                                        </td>
                                        <td className="px-4 py-2">
                                            {row.avg_price_per_sqm !== null
                                                ? `$${Math.round(row.avg_price_per_sqm).toLocaleString()}`
                                                : '—'}
                                        </td>
                                        <td className="px-4 py-2">
                                            {row.median_price_per_sqm !== null
                                                ? `$${Math.round(row.median_price_per_sqm).toLocaleString()}`
                                                : '—'}
                                        </td>
                                        <td className="px-4 py-2">
                                            {row.price_reductions > 0 ? (
                                                <span className="flex items-center gap-1 text-red-700">
                                                    <TrendingDown className="h-3.5 w-3.5" />
                                                    {row.price_reductions}
                                                </span>
                                            ) : (
                                                <span className="text-muted-foreground">
                                                    —
                                                </span>
                                            )}
                                        </td>
                                        <td className="px-4 py-2">
                                            {row.disappeared_count > 0 ? (
                                                <span className="flex items-center gap-1 text-yellow-700">
                                                    <XCircle className="h-3.5 w-3.5 animate-pulse" />
                                                    {row.disappeared_count}
                                                </span>
                                            ) : (
                                                <span className="text-muted-foreground">
                                                    —
                                                </span>
                                            )}
                                        </td>
                                        <td className="px-4 py-2">
                                            {row.stale_count > 0 ? (
                                                <span className="flex items-center gap-1 text-blue-700">
                                                    <Clock
                                                        className="h-3.5 w-3.5 animate-spin"
                                                        style={{
                                                            animationDuration:
                                                                '3s',
                                                        }}
                                                    />
                                                    {row.stale_count}
                                                </span>
                                            ) : (
                                                <span className="text-muted-foreground">
                                                    —
                                                </span>
                                            )}
                                        </td>
                                        {showYield && (
                                            <td className="px-4 py-2 font-medium text-blue-700">
                                                {yieldData[row.district]
                                                    ?.yield_pct != null
                                                    ? `${yieldData[row.district].yield_pct}%`
                                                    : '—'}
                                            </td>
                                        )}
                                    </tr>
                                ))}
                            </tbody>
                        </table>

                        {summary && summarySource === selectedSource && (
                            <div className="mt-4 rounded-lg border p-4">
                                <p className="mb-1 text-xs font-medium tracking-wide text-muted-foreground uppercase">
                                    AI Market Summary
                                </p>
                                <p className="text-sm leading-relaxed">
                                    {summary}
                                </p>
                            </div>
                        )}
                    </div>
                )}

                {/* Market Trends */}
                <div className="mt-10">
                    <div className="mb-4 flex items-center justify-between">
                        <div>
                            <h2 className="text-lg font-semibold">
                                Market Trends
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                listing activity and pricing over time.
                            </p>
                        </div>
                        <div className="flex gap-2">
                            {(['month', 'quarter'] as const).map((g) => (
                                <button
                                    key={g}
                                    onClick={() => setGroupBy(g)}
                                    className={`cursor-pointer rounded px-3 py-1 text-sm ${
                                        groupBy === g
                                            ? 'bg-primary text-primary-foreground'
                                            : 'border text-muted-foreground'
                                    }`}
                                >
                                    {g.charAt(0).toUpperCase() + g.slice(1)}
                                </button>
                            ))}
                        </div>
                    </div>

                    {trendsLoading ? (
                        <div className="flex h-48 items-center justify-center text-sm text-muted-foreground">
                            Loading…
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            {/* Sale Volume */}
                            <div className="rounded-lg border p-4">
                                <h3 className="mb-3 text-sm font-medium">
                                    Sale Listing Volume
                                </h3>
                                {volumeData.length === 0 ? (
                                    <div className="flex h-48 items-center justify-center text-sm text-muted-foreground">
                                        Not enough data yet.
                                    </div>
                                ) : (
                                    <ResponsiveContainer
                                        width="100%"
                                        height={220}
                                    >
                                        <LineChart
                                            data={volumeData}
                                            margin={{
                                                top: 4,
                                                right: 16,
                                                left: 0,
                                                bottom: 4,
                                            }}
                                        >
                                            <CartesianGrid
                                                strokeDasharray="3 3"
                                                stroke="#333333"
                                            />
                                            <XAxis
                                                dataKey="period"
                                                tick={{
                                                    fontSize: 11,
                                                    fill: '#888888',
                                                }}
                                            />
                                            <YAxis
                                                tick={{
                                                    fontSize: 11,
                                                    fill: '#888888',
                                                }}
                                                width={40}
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
                                            <Line
                                                type="monotone"
                                                dataKey="sale"
                                                name="Sale Listings"
                                                stroke="hsl(var(--primary))"
                                                strokeWidth={2}
                                                dot={{ r: 3 }}
                                                activeDot={{ r: 5 }}
                                            />
                                        </LineChart>
                                    </ResponsiveContainer>
                                )}
                            </div>

                            {/* Rent Volume */}
                            <div className="rounded-lg border p-4">
                                <h3 className="mb-3 text-sm font-medium">
                                    Rent Listing Volume
                                </h3>
                                {volumeData.length === 0 ? (
                                    <div className="flex h-48 items-center justify-center text-sm text-muted-foreground">
                                        Not enough data yet.
                                    </div>
                                ) : (
                                    <ResponsiveContainer
                                        width="100%"
                                        height={220}
                                    >
                                        <LineChart
                                            data={volumeData}
                                            margin={{
                                                top: 4,
                                                right: 16,
                                                left: 0,
                                                bottom: 4,
                                            }}
                                        >
                                            <CartesianGrid
                                                strokeDasharray="3 3"
                                                stroke="#333333"
                                            />
                                            <XAxis
                                                dataKey="period"
                                                tick={{
                                                    fontSize: 11,
                                                    fill: '#888888',
                                                }}
                                            />
                                            <YAxis
                                                tick={{
                                                    fontSize: 11,
                                                    fill: '#888888',
                                                }}
                                                width={40}
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
                                            <Line
                                                type="monotone"
                                                dataKey="rent"
                                                name="Rent Listings"
                                                stroke="hsl(var(--muted-foreground))"
                                                strokeWidth={2}
                                                dot={{ r: 3 }}
                                                activeDot={{ r: 5 }}
                                            />
                                        </LineChart>
                                    </ResponsiveContainer>
                                )}
                            </div>

                            {/* Median Price/sqm */}
                            <div className="rounded-lg border p-4 lg:col-span-2">
                                <h3 className="mb-3 text-sm font-medium">
                                    Median Sale Price/sqm (USD)
                                </h3>
                                {priceData.length === 0 ? (
                                    <div className="flex h-48 items-center justify-center text-sm text-muted-foreground">
                                        Not enough data yet.
                                    </div>
                                ) : (
                                    <ResponsiveContainer
                                        width="100%"
                                        height={220}
                                    >
                                        <LineChart
                                            data={priceData}
                                            margin={{
                                                top: 4,
                                                right: 16,
                                                left: 8,
                                                bottom: 4,
                                            }}
                                        >
                                            <CartesianGrid
                                                strokeDasharray="3 3"
                                                stroke="#333333"
                                            />
                                            <XAxis
                                                dataKey="period"
                                                tick={{
                                                    fontSize: 11,
                                                    fill: '#888888',
                                                }}
                                            />
                                            <YAxis
                                                tick={{
                                                    fontSize: 11,
                                                    fill: '#888888',
                                                }}
                                                width={55}
                                                tickFormatter={(v) => `$${v}`}
                                            />
                                            <Tooltip
                                                contentStyle={{
                                                    background: '#1a1a1a',
                                                    border: '1px solid #333333',
                                                    borderRadius: '6px',
                                                    fontSize: '12px',
                                                    color: '#ffffff',
                                                }}
                                                formatter={(v) => [
                                                    `$${v}`,
                                                    'Median Price/sqm',
                                                ]}
                                            />
                                            <Line
                                                type="monotone"
                                                dataKey="median_price_per_sqm"
                                                name="Median Price/sqm"
                                                stroke="hsl(var(--primary))"
                                                strokeWidth={2}
                                                dot={{ r: 3 }}
                                                activeDot={{ r: 5 }}
                                            />
                                        </LineChart>
                                    </ResponsiveContainer>
                                )}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
