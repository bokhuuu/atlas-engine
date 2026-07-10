/**
 * Format a USD cost value for display.
 * Under $0.01 → shows as cents (e.g. "0.74¢")
 * $0.01 and above → shows as dollars (e.g. "$1.23")
 */
export function formatCost(usd: number): string {
    if (usd === 0) {
        return '0.00';
    }

    if (usd < 0.01) {
        return `${(usd * 100).toFixed(2)}`;
    }

    return `$${usd.toFixed(2)}`;
}
