import { Head } from '@inertiajs/react';
import { TrendingUp, Building2, Clock } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

interface MarketData {
    slug: string;
    label: string;
    flag: string;
    currency: string;
    total_listings: number;
    active_categories: number;
    median_sale_price_per_sqm: number | null;
    last_scraped_at: string | null;
    has_data: boolean;
}

interface Props {
    markets: MarketData[];
    totalListingsAllMarkets: number;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Global Dashboard', href: '/global/admin/dashboard' },
];

export default function GlobalDashboard({
    markets,
    totalListingsAllMarkets,
}: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Global Dashboard" />
            <div className="p-6">
                <div className="mb-8">
                    <h1 className="text-2xl font-semibold">
                        Caucasus Market Overview
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Cross-market intelligence across all configured markets.
                    </p>
                </div>

                {/* Hero stat */}
                <div className="mb-8 rounded-lg border bg-primary/5 p-6">
                    <p className="text-sm text-muted-foreground">
                        Total Listings Tracked
                    </p>
                    <p className="mt-1 text-4xl font-bold">
                        {totalListingsAllMarkets.toLocaleString()}
                    </p>
                    <p className="mt-1 text-xs text-muted-foreground">
                        across {markets.length} configured markets
                    </p>
                </div>

                {/* Market cards */}
                <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                    {markets.map((market) => (
                        <div
                            key={market.slug}
                            className={`rounded-lg border p-5 ${
                                market.has_data
                                    ? 'bg-card'
                                    : 'bg-muted/20 opacity-60'
                            }`}
                        >
                            <div className="mb-4 flex items-center justify-between">
                                <div className="flex items-center gap-2">
                                    <span className="text-2xl">
                                        {market.flag}
                                    </span>
                                    <div>
                                        <h2 className="font-semibold">
                                            {market.label}
                                        </h2>
                                        <p className="text-xs text-muted-foreground">
                                            {market.currency}
                                        </p>
                                    </div>
                                </div>
                                {!market.has_data && (
                                    <span className="rounded-full border px-2 py-0.5 text-xs text-muted-foreground">
                                        No data yet
                                    </span>
                                )}
                            </div>

                            <div className="space-y-3">
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                                        <Building2 className="h-3.5 w-3.5" />
                                        <span>Total Listings</span>
                                    </div>
                                    <span className="font-semibold">
                                        {market.total_listings.toLocaleString()}
                                    </span>
                                </div>

                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                                        <TrendingUp className="h-3.5 w-3.5" />
                                        <span>Median Sale/sqm</span>
                                    </div>
                                    <span className="font-semibold">
                                        {market.median_sale_price_per_sqm
                                            ? `$${Math.round(market.median_sale_price_per_sqm).toLocaleString()}`
                                            : '—'}
                                    </span>
                                </div>

                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                                        <Clock className="h-3.5 w-3.5" />
                                        <span>Last Scraped</span>
                                    </div>
                                    <span className="text-sm">
                                        {market.last_scraped_at
                                            ? new Date(
                                                  market.last_scraped_at,
                                              ).toLocaleDateString()
                                            : '—'}
                                    </span>
                                </div>

                                <div className="flex items-center justify-between">
                                    <span className="text-sm text-muted-foreground">
                                        Active Categories
                                    </span>
                                    <span className="text-sm font-medium">
                                        {market.active_categories} / 7
                                    </span>
                                </div>
                            </div>

                            {market.has_data && (
                                <a
                                    href={`/${market.slug}/admin/dashboard`}
                                    className="mt-4 block w-full rounded-md border px-3 py-1.5 text-center text-xs text-muted-foreground transition-colors hover:text-foreground"
                                >
                                    View {market.label} →
                                </a>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
