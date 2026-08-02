import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

interface CategoryData {
    source: string;
    label: string;
    median_price_per_sqm: number | null;
}

interface MarketStat {
    slug: string;
    label: string;
    flag: string;
    categories: Record<string, CategoryData>;
}

interface Category {
    source: string;
    canonical_type: string;
    label: string;
    listing_type: 'sale' | 'rent';
}

interface Props {
    marketStats: MarketStat[];
    allCategories: Category[];
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Global Dashboard', href: '/global/admin/dashboard' },
    { title: 'Market Comparison', href: '/global/admin/market-stats' },
];

export default function GlobalMarketStats({
    marketStats,
    allCategories,
}: Props) {
    const saleCategories = allCategories.filter(
        (c) => c.listing_type === 'sale',
    );
    const rentCategories = allCategories.filter(
        (c) => c.listing_type === 'rent',
    );

    const formatPrice = (price: number | null) =>
        price ? `$${Math.round(price).toLocaleString()}` : '—';

    const renderSection = (title: string, categories: Category[]) => (
        <div className="mb-8">
            <h2 className="mb-3 text-sm font-semibold tracking-wide text-muted-foreground uppercase">
                {title}
            </h2>
            <div className="overflow-x-auto rounded-lg border">
                <table className="w-full text-sm">
                    <thead className="border-b bg-muted/50">
                        <tr>
                            <th className="px-4 py-3 text-left font-medium">
                                Category
                            </th>
                            {marketStats.map((market) => (
                                <th
                                    key={market.slug}
                                    className="px-4 py-3 text-left font-medium"
                                >
                                    <span className="mr-1.5">
                                        {market.flag}
                                    </span>
                                    {market.label}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {categories.map((category, i) => (
                            <tr
                                key={category.canonical_type}
                                className={`border-b last:border-0 ${
                                    i % 2 === 0 ? '' : 'bg-muted/20'
                                }`}
                            >
                                <td className="px-4 py-3 font-medium">
                                    {category.label}
                                </td>
                                {marketStats.map((market) => (
                                    <td
                                        key={market.slug}
                                        className="px-4 py-3 tabular-nums"
                                    >
                                        {market.categories?.[
                                            category.canonical_type
                                        ]
                                            ? formatPrice(
                                                  market.categories[
                                                      category.canonical_type
                                                  ].median_price_per_sqm,
                                              )
                                            : '—'}
                                    </td>
                                ))}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Market Comparison" />
            <div className="p-6">
                <div className="mb-8">
                    <h1 className="text-2xl font-semibold">
                        Market Comparison
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Median price/sqm by category across all configured
                        markets. All prices in USD.
                    </p>
                </div>

                {renderSection('Sale Categories', saleCategories)}
                {renderSection('Rental Categories', rentCategories)}

                <p className="text-xs text-muted-foreground">
                    — indicates no data available for this market yet. Data
                    collection has not started for Baku and Tbilisi.
                </p>
            </div>
        </AppLayout>
    );
}
