import { usePage } from '@inertiajs/react';

/**
 * Returns the current market slug and a helper to build market-prefixed admin URLs.
 */
export function useMarket() {
    const props = usePage().props as unknown as {
        currentMarket: string | null;
    };
    const market = props.currentMarket ?? 'yerevan';

    /**
     * Builds a market-prefixed admin URL.
     * e.g. adminUrl('/dashboard') → '/yerevan/admin/dashboard'
     */
    const adminUrl = (path: string) => `/${market}/admin${path}`;

    return { market, adminUrl };
}
