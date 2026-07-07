import { Head } from '@inertiajs/react';
import {
    AlertCircle,
    Database,
    Download,
    Loader2,
    RotateCcw,
} from 'lucide-react';
import { useCallback, useEffect, useRef, useState } from 'react';

import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Data Hub', href: '/admin/data-hub' },
];

interface Category {
    source: string;
    label: string;
}

interface Props {
    categories: Category[];
    districts: string[];
}

interface FilterState {
    source: string;
    district: string;
    status: string[];
    min_price: string;
    max_price: string;
    min_area: string;
    max_area: string;
    rooms: string[];
    agency: string;
    scraped_from: string;
    scraped_to: string;
}

interface FilterResult {
    total: number;
}

interface ValidationErrors {
    price?: string;
    area?: string;
    date?: string;
    status?: string;
}

const makeDefault = (categories: Category[]): FilterState => ({
    source: categories[0]?.source ?? '',
    district: '',
    status: ['active', 'stale', 'disappeared'],
    min_price: '',
    max_price: '',
    min_area: '',
    max_area: '',
    rooms: [],
    agency: '',
    scraped_from: '',
    scraped_to: '',
});

function validate(f: FilterState): ValidationErrors {
    const errors: ValidationErrors = {};

    if (
        f.min_price &&
        f.max_price &&
        parseFloat(f.min_price) > parseFloat(f.max_price)
    ) {
        errors.price = 'Min price cannot exceed max price';
    }

    if (
        f.min_area &&
        f.max_area &&
        parseFloat(f.min_area) > parseFloat(f.max_area)
    ) {
        errors.area = 'Min area cannot exceed max area';
    }

    if (f.scraped_from && f.scraped_to && f.scraped_from > f.scraped_to) {
        errors.date = 'Start date cannot be after end date';
    }

    if (f.status.length === 0) {
        errors.status = 'At least one status must be selected';
    }

    return errors;
}

export default function DataHub({ categories, districts }: Props) {
    const [filters, setFilters] = useState<FilterState>(
        makeDefault(categories),
    );
    const [result, setResult] = useState<FilterResult | null>(null);
    const [loading, setLoading] = useState(false);
    const [activeFilterCount, setActiveFilterCount] = useState(0);
    const [errors, setErrors] = useState<ValidationErrors>({});
    const [prevTotal, setPrevTotal] = useState<number | null>(null);
    const debounceRef = useRef<ReturnType<typeof setTimeout> | null>(null);
    const abortRef = useRef<AbortController | null>(null);

    const buildQueryString = useCallback((f: FilterState) => {
        const params = new URLSearchParams();

        if (f.source) {
            params.set('source', f.source);
        }

        if (f.district) {
            params.set('district', f.district);
        }

        f.status.forEach((s) => params.append('status[]', s));

        if (f.min_price) {
            params.set('min_price', f.min_price);
        }

        if (f.max_price) {
            params.set('max_price', f.max_price);
        }

        if (f.min_area) {
            params.set('min_area', f.min_area);
        }

        if (f.max_area) {
            params.set('max_area', f.max_area);
        }

        f.rooms.forEach((r) => params.append('rooms[]', r));

        if (f.agency) {
            params.set('agency', f.agency);
        }

        if (f.scraped_from) {
            params.set('scraped_from', f.scraped_from);
        }

        if (f.scraped_to) {
            params.set('scraped_to', f.scraped_to);
        }

        return params.toString();
    }, []);

    const fetchCount = useCallback(
        async (f: FilterState) => {
            const validationErrors = validate(f);

            setErrors(validationErrors);

            if (Object.keys(validationErrors).length > 0) {
                return;
            }

            if (abortRef.current) {
                abortRef.current.abort();
            }

            abortRef.current = new AbortController();
            setLoading(true);

            try {
                const qs = buildQueryString(f);
                const response = await fetch(`/api/v1/listings/filter?${qs}`, {
                    headers: { Accept: 'application/json' },
                    signal: abortRef.current.signal,
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = (await response.json()) as FilterResult;

                setPrevTotal((prev) => {
                    if (prev !== data.total) {
                        setPrevTotal(data.total);
                    }

                    return prev;
                });
                setResult(data);
            } catch (err) {
                if (err instanceof Error && err.name === 'AbortError') {
                    return;
                }

                setResult(null);
            } finally {
                setLoading(false);
            }
        },
        [buildQueryString],
    );

    useEffect(() => {
        if (debounceRef.current) {
            clearTimeout(debounceRef.current);
        }

        debounceRef.current = setTimeout(() => fetchCount(filters), 800);

        return () => {
            if (debounceRef.current) {
                clearTimeout(debounceRef.current);
            }

            if (abortRef.current) {
                abortRef.current.abort();
            }
        };
    }, [filters, fetchCount]);

    useEffect(() => {
        let count = 0;

        if (filters.district) {
            count++;
        }

        if (filters.status.length < 3) {
            count++;
        }

        if (filters.min_price || filters.max_price) {
            count++;
        }

        if (filters.min_area || filters.max_area) {
            count++;
        }

        if (filters.rooms.length > 0) {
            count++;
        }

        if (filters.agency) {
            count++;
        }

        if (filters.scraped_from || filters.scraped_to) {
            count++;
        }

        setActiveFilterCount(count);
    }, [filters]);

    const toggleMulti = (field: 'status' | 'rooms', value: string) => {
        setFilters((prev) => {
            const arr = prev[field] as string[];

            return {
                ...prev,
                [field]: arr.includes(value)
                    ? arr.filter((x) => x !== value)
                    : [...arr, value],
            };
        });
    };

    const hasErrors = Object.keys(errors).length > 0;

    const downloadExcel = () => {
        if (hasErrors || !result || result.total === 0) {
            return;
        }

        const qs = buildQueryString(filters);

        window.open(`/api/v1/exports/filtered?${qs}`, '_blank');
    };

    const chipCls = (active: boolean, hasError = false) =>
        'cursor-pointer select-none rounded-full border px-3 py-1 text-xs font-medium transition-all duration-200 ' +
        (hasError
            ? 'border-destructive/40 bg-destructive/5 text-destructive'
            : active
              ? 'border-primary/40 bg-primary/10 text-primary shadow-sm'
              : 'border-border text-muted-foreground hover:border-primary/30 hover:text-foreground');

    const selectCls =
        'w-full cursor-pointer rounded-lg border border-border bg-background px-3 py-2 text-sm transition-all duration-200 hover:border-primary/40 focus:outline-none focus:ring-1 focus:ring-primary';

    const inputCls = (hasError = false) =>
        'w-full rounded-lg border px-3 py-2 text-sm transition-all duration-200 focus:outline-none focus:ring-1 bg-background ' +
        (hasError
            ? 'border-destructive/50 focus:ring-destructive text-destructive'
            : 'border-border hover:border-primary/40 focus:ring-primary');

    const sectionLabel = (text: string) => (
        <p className="mb-2 text-xs font-semibold tracking-wider text-muted-foreground uppercase">
            {text}
        </p>
    );

    const errorMsg = (msg: string) => (
        <div className="mt-1.5 flex items-center gap-1 text-xs text-destructive">
            <AlertCircle className="h-3 w-3 shrink-0" />
            {msg}
        </div>
    );

    const countChanged =
        prevTotal !== null && result !== null && prevTotal !== result.total;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Data Hub" />
            <div className="flex h-[calc(100vh-4rem)] flex-col overflow-hidden lg:flex-row">
                {/* Filters panel */}
                <div className="flex-1 overflow-y-auto border-b border-border p-6 lg:border-r lg:border-b-0">
                    <div className="mb-6 flex items-center justify-between">
                        <div className="flex items-center gap-2.5">
                            <Database className="h-5 w-5 text-primary" />
                            <h1 className="text-lg font-semibold">Data Hub</h1>
                            {activeFilterCount > 0 && (
                                <span className="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">
                                    {activeFilterCount} active
                                </span>
                            )}
                        </div>
                        {activeFilterCount > 0 && (
                            <button
                                onClick={() => {
                                    setFilters(makeDefault(categories));
                                    setErrors({});
                                }}
                                className="flex cursor-pointer items-center gap-1.5 rounded-lg border border-border px-3 py-1.5 text-xs text-muted-foreground transition-all duration-200 hover:border-destructive/30 hover:text-destructive"
                            >
                                <RotateCcw className="h-3 w-3" />
                                Clear all
                            </button>
                        )}
                    </div>

                    <div className="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div className="sm:col-span-2">
                            {sectionLabel('Category')}
                            <select
                                value={filters.source}
                                onChange={(e) =>
                                    setFilters((p) => ({
                                        ...p,
                                        source: e.target.value,
                                    }))
                                }
                                className={selectCls}
                            >
                                {categories.map((c) => (
                                    <option key={c.source} value={c.source}>
                                        {c.label}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div>
                            {sectionLabel('District')}
                            <select
                                value={filters.district}
                                onChange={(e) =>
                                    setFilters((p) => ({
                                        ...p,
                                        district: e.target.value,
                                    }))
                                }
                                className={selectCls}
                            >
                                <option value="">All districts</option>
                                {districts.map((d) => (
                                    <option key={d} value={d}>
                                        {d}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div>
                            {sectionLabel('Listed by')}
                            <div className="flex flex-wrap gap-2">
                                {[
                                    { value: '', label: 'All' },
                                    { value: 'agency', label: 'Agency' },
                                    { value: 'owner', label: 'Owner' },
                                ].map((o) => (
                                    <button
                                        key={o.value}
                                        onClick={() =>
                                            setFilters((p) => ({
                                                ...p,
                                                agency: o.value,
                                            }))
                                        }
                                        className={chipCls(
                                            filters.agency === o.value,
                                        )}
                                    >
                                        {o.label}
                                    </button>
                                ))}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            {sectionLabel('Status')}
                            <div className="flex flex-wrap gap-2">
                                {[
                                    { value: 'active', label: 'Active' },
                                    { value: 'stale', label: 'Stale' },
                                    {
                                        value: 'disappeared',
                                        label: 'Disappeared',
                                    },
                                ].map((s) => (
                                    <button
                                        key={s.value}
                                        onClick={() =>
                                            toggleMulti('status', s.value)
                                        }
                                        className={chipCls(
                                            filters.status.includes(s.value),
                                            !!errors.status,
                                        )}
                                    >
                                        {s.label}
                                    </button>
                                ))}
                            </div>
                            {errors.status && errorMsg(errors.status)}
                        </div>

                        <div className="sm:col-span-2">
                            {sectionLabel('Rooms')}
                            <div className="flex flex-wrap gap-2">
                                {[
                                    { value: '1', label: '1' },
                                    { value: '2', label: '2' },
                                    { value: '3', label: '3' },
                                    { value: '4', label: '4' },
                                    { value: '5', label: '5+' },
                                ].map((r) => (
                                    <button
                                        key={r.value}
                                        onClick={() =>
                                            toggleMulti('rooms', r.value)
                                        }
                                        className={chipCls(
                                            filters.rooms.includes(r.value),
                                        )}
                                    >
                                        {r.label}
                                    </button>
                                ))}
                            </div>
                        </div>

                        <div>
                            {sectionLabel('Min price (USD)')}
                            <input
                                type="number"
                                placeholder="e.g. 50,000"
                                value={filters.min_price}
                                onChange={(e) =>
                                    setFilters((p) => ({
                                        ...p,
                                        min_price: e.target.value,
                                    }))
                                }
                                className={inputCls(!!errors.price)}
                            />
                        </div>

                        <div>
                            {sectionLabel('Max price (USD)')}
                            <input
                                type="number"
                                placeholder="e.g. 300,000"
                                value={filters.max_price}
                                onChange={(e) =>
                                    setFilters((p) => ({
                                        ...p,
                                        max_price: e.target.value,
                                    }))
                                }
                                className={inputCls(!!errors.price)}
                            />
                            {errors.price && errorMsg(errors.price)}
                        </div>

                        <div>
                            {sectionLabel('Min area (sqm)')}
                            <input
                                type="number"
                                placeholder="e.g. 40"
                                value={filters.min_area}
                                onChange={(e) =>
                                    setFilters((p) => ({
                                        ...p,
                                        min_area: e.target.value,
                                    }))
                                }
                                className={inputCls(!!errors.area)}
                            />
                        </div>

                        <div>
                            {sectionLabel('Max area (sqm)')}
                            <input
                                type="number"
                                placeholder="e.g. 200"
                                value={filters.max_area}
                                onChange={(e) =>
                                    setFilters((p) => ({
                                        ...p,
                                        max_area: e.target.value,
                                    }))
                                }
                                className={inputCls(!!errors.area)}
                            />
                            {errors.area && errorMsg(errors.area)}
                        </div>

                        <div>
                            {sectionLabel('Scraped from')}
                            <input
                                type="date"
                                value={filters.scraped_from}
                                onChange={(e) =>
                                    setFilters((p) => ({
                                        ...p,
                                        scraped_from: e.target.value,
                                    }))
                                }
                                className={inputCls(!!errors.date)}
                            />
                        </div>

                        <div>
                            {sectionLabel('Scraped to')}
                            <input
                                type="date"
                                value={filters.scraped_to}
                                onChange={(e) =>
                                    setFilters((p) => ({
                                        ...p,
                                        scraped_to: e.target.value,
                                    }))
                                }
                                className={inputCls(!!errors.date)}
                            />
                            {errors.date && errorMsg(errors.date)}
                        </div>
                    </div>
                </div>

                {/* Result panel */}
                <div className="flex w-full shrink-0 flex-col items-center justify-center gap-6 p-8 lg:w-80">
                    <div className="text-center">
                        <p className="mb-4 text-xs font-semibold tracking-widest text-muted-foreground uppercase">
                            {categories.find((c) => c.source === filters.source)
                                ?.label ?? 'Listings'}
                        </p>

                        <div className="relative flex h-36 items-center justify-center">
                            {loading ? (
                                <Loader2 className="h-12 w-12 animate-spin text-muted-foreground/30" />
                            ) : hasErrors ? (
                                <div className="text-center">
                                    <AlertCircle className="mx-auto mb-2 h-10 w-10 text-destructive/40" />
                                    <p className="text-sm text-destructive/60">
                                        Fix errors to see results
                                    </p>
                                </div>
                            ) : (
                                <div
                                    className={
                                        'text-7xl font-bold tracking-tight tabular-nums transition-all duration-500 ' +
                                        (countChanged
                                            ? 'scale-110 text-primary'
                                            : 'scale-100')
                                    }
                                >
                                    {result?.total?.toLocaleString() ?? '0'}
                                </div>
                            )}
                        </div>

                        {!hasErrors && (
                            <p className="text-sm text-muted-foreground">
                                listing{result?.total !== 1 ? 's' : ''} match
                                your filters
                            </p>
                        )}

                        {activeFilterCount > 0 && !hasErrors && (
                            <p className="mt-1 text-xs text-muted-foreground">
                                {activeFilterCount} filter
                                {activeFilterCount > 1 ? 's' : ''} active
                            </p>
                        )}
                    </div>

                    <button
                        onClick={downloadExcel}
                        disabled={hasErrors || !result || result.total === 0}
                        className="flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3.5 text-sm font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 active:scale-95 disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        <Download className="h-4 w-4" />
                        Download Excel
                        {result && result.total > 0 && !hasErrors && (
                            <span className="rounded-full bg-primary-foreground/20 px-2 py-0.5 text-xs font-normal">
                                {result.total.toLocaleString()}
                            </span>
                        )}
                    </button>

                    <p className="text-center text-xs leading-relaxed text-muted-foreground">
                        Exports all matching listings as a single-sheet Excel
                        file
                    </p>
                </div>
            </div>
        </AppLayout>
    );
}
