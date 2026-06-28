import { Head } from '@inertiajs/react';
import { useEffect, useState } from 'react';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
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
}

interface Props {
    categories: Category[];
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Market Stats', href: '/admin/market-stats' },
];

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

    const error = errorSource === selectedSource;
    const rows = result?.source === selectedSource ? result.rows : null;

    const sortedRows = rows
        ? [...rows].sort(
              (a, b) =>
                  (b.median_price_per_sqm ?? 0) - (a.median_price_per_sqm ?? 0),
          )
        : [];

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
            </div>
        </AppLayout>
    );
}
