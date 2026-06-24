import { Head, Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { login } from '@/routes';

/**
 * Screen 0 — Public Market Snapshot (ATLAS.md Section 10).
 *
 * Shows all 7 scraped categories' headline stats (median price/sqm +
 * deduplicated listing count), each clearly labeled sale vs rent so
 * a monthly rent figure is never visually confused with a purchase
 * price. No district breakdown here - that stays behind login on
 * Screen 1, so "Request Full Access" still has real value to offer.
 * No week-over-week trend either - Section 15 notes there isn't
 * enough scheduled-scrape history yet for that to be meaningful.
 *
 * Uses median, not mean, for price/sqm - confirmed necessary after
 * Commercial-for-Sale's mean was found to sit well above real
 * Yerevan market reports, traced to a handful of genuinely real but
 * extreme listings (e.g. a 5 sqm kiosk at $1.8M), not a scraping
 * bug. Applied uniformly to all 7 categories, not just the one
 * category where a problem was found.
 */

interface CategorySnapshot {
    source: string;
    label: string;
    deal_type: 'sale' | 'rent';
    median_price_per_sqm: number | null;
    total_listings: number;
}

interface SnapshotData {
    categories: CategorySnapshot[];
}

/**
 * Formats the median price/sqm, labeled by deal type. Median, not
 * mean, is used here specifically because a handful of genuinely
 * real but extreme listings (e.g. a tiny premium-location unit
 * priced in the millions) shouldn't be allowed to skew what looks
 * like the "typical" price for a category.
 */
function formatPricePerSqm(value: number | null): string {
    if (value === null) {
        return '—';
    }

    return `$${Math.round(value).toLocaleString()}`;
}

export default function MarketSnapshot() {
    const [data, setData] = useState<SnapshotData | null>(null);
    const [error, setError] = useState(false);

    useEffect(() => {
        fetch('/api/v1/public/snapshot')
            .then((res) => {
                if (!res.ok) {
                    throw new Error('Snapshot request failed');
                }

                return res.json();
            })
            .then(setData)
            .catch(() => setError(true));
    }, []);

    return (
        <>
            <Head title="Yerevan Real Estate Market Snapshot" />

            <div className="flex min-h-screen flex-col bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a]">
                <header className="flex items-center justify-between p-6 lg:px-12">
                    <span className="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                        Atlas 🗺️
                    </span>
                    <Link
                        href={login().url}
                        className="rounded-sm border border-[#19140035] px-4 py-1.5 text-sm hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Log in
                    </Link>
                </header>

                <main className="mx-auto flex max-w-5xl flex-1 flex-col gap-8 px-6 py-10 lg:px-0">
                    <div>
                        <h1 className="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                            Yerevan Real Estate Market Stats
                        </h1>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Live data across all tracked categories -
                            apartments, houses, commercial and land.
                        </p>
                    </div>

                    {error && (
                        <p className="text-sm text-muted-foreground">
                            Market data is temporarily unavailable. Please check
                            back shortly.
                        </p>
                    )}

                    {!error && !data && (
                        <p className="text-sm text-muted-foreground">
                            Loading market data...
                        </p>
                    )}

                    {data && (
                        <>
                            <div className="flex flex-wrap justify-center gap-4">
                                {data.categories.map((cat) => (
                                    <Card
                                        key={cat.source}
                                        className="w-full sm:w-[calc(50%-0.5rem)] lg:w-[calc(33.333%-0.667rem)]"
                                    >
                                        <CardHeader className="pb-2">
                                            <CardTitle className="flex items-center justify-between text-base">
                                                {cat.label}
                                                <Badge
                                                    variant={
                                                        cat.deal_type === 'rent'
                                                            ? 'secondary'
                                                            : 'outline'
                                                    }
                                                >
                                                    {cat.deal_type}
                                                </Badge>
                                            </CardTitle>
                                        </CardHeader>
                                        <CardContent className="flex items-end justify-between">
                                            <p className="text-2xl font-semibold">
                                                {formatPricePerSqm(
                                                    cat.median_price_per_sqm,
                                                )}
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                {cat.total_listings.toLocaleString()}{' '}
                                                listings
                                            </p>
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>

                            <Card className="bg-muted/40">
                                <CardContent className="flex flex-col items-start gap-3 pt-6 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p className="font-medium">
                                            Want the full district breakdown?
                                        </p>
                                        <p className="text-sm text-muted-foreground">
                                            This is an initial sample run - full
                                            depth available on request.
                                        </p>
                                    </div>
                                    <Link
                                        href={login().url}
                                        className="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-2 text-sm text-white hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white"
                                    >
                                        Request Full Access
                                    </Link>
                                </CardContent>
                            </Card>
                        </>
                    )}
                </main>
            </div>
        </>
    );
}
