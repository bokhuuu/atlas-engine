import { Head, Link, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { login } from '@/routes';

/**
 * Screen 0 — Public Market Snapshot (ATLAS.md Section 10).
 *
 * Redesigned in Thread 6 using Claude Design's "Softer Premium" variant.
 * Space Grotesk + JetBrains Mono typography, animated card entrance,
 * count-up numbers, pulsing live badge, blue/amber sale/rent accents.
 *
 * Uses median price/sqm (not mean) — confirmed necessary after
 * Commercial-for-Sale's mean sat well above real Yerevan market reports.
 * Applied uniformly across all 7 categories per the Data Principle.
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

const SALE_COLOR = '#5AA0FF';
const RENT_COLOR = '#F5B53D';
const SALE_SOFT = 'rgba(90,160,255,0.12)';
const RENT_SOFT = 'rgba(245,181,61,0.12)';

const getIcon = (source: string): string => {
    if (
        source.endsWith('_apartments_sale') ||
        source.endsWith('_apartments_rent')
    ) {
        return '🏙️';
    }

    if (source.endsWith('_houses_sale')) {
        return '🏠';
    }

    if (
        source.endsWith('_commercial_sale') ||
        source.endsWith('_commercial_rent')
    ) {
        return '🏢';
    }

    if (source.endsWith('_land_sale')) {
        return '🌍';
    }

    if (source.endsWith('_new_developments_sale')) {
        return '🏗️';
    }

    return '🏘️';
};

const BARS = [
    38, 52, 30, 64, 46, 78, 40, 58, 34, 70, 48, 86, 42, 60, 36, 74, 50, 44, 66,
    32, 56, 80, 40, 62, 38, 72, 46, 54,
];

function CategoryCard({
    cat,
    index,
    progress,
    entered,
}: {
    cat: CategorySnapshot;
    index: number;
    progress: number;
    entered: boolean;
}) {
    const isRent = cat.deal_type === 'rent';
    const accent = isRent ? RENT_COLOR : SALE_COLOR;
    const accentSoft = isRent ? RENT_SOFT : SALE_SOFT;
    const rawPrice = cat.median_price_per_sqm ?? 0;
    const animatedPrice = Math.round(rawPrice * progress);
    const animatedListings = Math.round(cat.total_listings * progress);
    const unit = isRent ? '/m² · mo' : '/m²';
    const delay = index * 70;

    return (
        <div
            style={{
                position: 'relative',
                background:
                    'linear-gradient(180deg,rgba(255,255,255,.035),rgba(255,255,255,.012))',
                border: '1px solid rgba(255,255,255,.07)',
                borderRadius: '15px',
                padding: '20px',
                overflow: 'hidden',
                boxShadow: '0 10px 30px rgba(0,0,0,.25)',
                opacity: entered ? 1 : 0,
                transform: entered ? 'translateY(0)' : 'translateY(16px)',
                transition: `opacity .55s ease ${delay}ms, transform .55s ease ${delay}ms`,
            }}
        >
            <div
                style={{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    marginBottom: '16px',
                }}
            >
                <span
                    style={{
                        display: 'inline-flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        width: '40px',
                        height: '40px',
                        borderRadius: '10px',
                        fontSize: '20px',
                        background: accentSoft,
                    }}
                >
                    {getIcon(cat.source)}
                </span>
                <span
                    style={{
                        fontFamily: "'JetBrains Mono', monospace",
                        fontSize: '10px',
                        letterSpacing: '1.5px',
                        fontWeight: 600,
                        padding: '4px 8px',
                        borderRadius: '6px',
                        color: accent,
                        background: accentSoft,
                    }}
                >
                    {isRent ? 'RENT' : 'SALE'}
                </span>
            </div>

            <div
                style={{
                    fontSize: '13px',
                    color: '#a6adb8',
                    fontWeight: 500,
                    marginBottom: '12px',
                    letterSpacing: '.2px',
                }}
            >
                {cat.label}
            </div>

            <div
                style={{
                    display: 'flex',
                    alignItems: 'baseline',
                    gap: '5px',
                    marginBottom: '12px',
                }}
            >
                <span
                    style={{
                        fontFamily: "'JetBrains Mono', monospace",
                        fontSize: 'clamp(24px, 5vw, 36px)',
                        fontWeight: 600,
                        color: accent,
                        letterSpacing: '-1px',
                        lineHeight: 1,
                    }}
                >
                    {cat.median_price_per_sqm !== null
                        ? `$${animatedPrice.toLocaleString()}`
                        : '—'}
                </span>
                <span
                    style={{
                        fontFamily: "'JetBrains Mono', monospace",
                        fontSize: '11px',
                        color: '#6b7280',
                    }}
                >
                    {unit}
                </span>
            </div>

            <div
                style={{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    borderTop: '1px solid rgba(255,255,255,.06)',
                    paddingTop: '12px',
                }}
            >
                <span
                    style={{
                        fontFamily: "'JetBrains Mono', monospace",
                        fontSize: '11px',
                        color: '#858c97',
                    }}
                >
                    {animatedListings.toLocaleString()} listings
                </span>
            </div>
        </div>
    );
}

export default function MarketSnapshot() {
    const [data, setData] = useState<SnapshotData | null>(null);
    const [error, setError] = useState(false);
    const [copied, setCopied] = useState(false);
    const [entered, setEntered] = useState(false);
    const [progress, setProgress] = useState(0);
    const { auth, siteSettings, currentMarket } = usePage()
        .props as unknown as {
        auth: { user: { name: string } | null };
        siteSettings: {
            site_name: string;
            site_description: string;
            site_email: string;
            logo: string | null;
        };
        currentMarket: string | null;
    };

    useEffect(() => {
        fetch(`/api/v1/public/snapshot?market=${currentMarket ?? 'yerevan'}`)
            .then((res) => {
                if (!res.ok) {
                    throw new Error('Snapshot request failed');
                }

                return res.json();
            })
            .then(setData)
            .catch(() => setError(true));
    }, [currentMarket]);

    useEffect(() => {
        if (!data) {
            return;
        }

        requestAnimationFrame(() => setEntered(true));

        const dur = 1500;
        const t0 = performance.now();
        const ease = (t: number) => 1 - Math.pow(1 - t, 3);
        let raf: number;

        const tick = (now: number) => {
            const p = Math.min(1, (now - t0) / dur);

            setProgress(ease(p));

            if (p < 1) {
                raf = requestAnimationFrame(tick);
            }
        };

        raf = requestAnimationFrame(tick);

        return () => cancelAnimationFrame(raf);
    }, [data]);

    const totalListings = data
        ? Math.round(
              data.categories.reduce((sum, c) => sum + c.total_listings, 0) *
                  progress,
          )
        : 0;

    const gridCats =
        data?.categories.filter((c) => !c.source.endsWith('_land_sale')) ?? [];
    const landCat = data?.categories.find((c) =>
        c.source.endsWith('_land_sale'),
    );

    return (
        <>
            <Head
                title={`${currentMarket ? currentMarket.charAt(0).toUpperCase() + currentMarket.slice(1) : 'Real Estate'} Market Snapshot`}
            />

            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link
                rel="preconnect"
                href="https://fonts.gstatic.com"
                crossOrigin=""
            />
            <link
                href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
                rel="stylesheet"
            />

            <style>{`
                @keyframes pulseDot {
                    0% { box-shadow: 0 0 0 0 rgba(70,192,138,.5); }
                    70% { box-shadow: 0 0 0 9px rgba(70,192,138,0); }
                    100% { box-shadow: 0 0 0 0 rgba(70,192,138,0); }
                }
                .s0-header { padding: 20px 48px; }
                .s0-hero { padding: 60px 48px 40px; }
                .s0-grid-wrap { padding: 8px 48px 52px; }
                .s0-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
                .s0-land { display: flex; flex-direction: row; align-items: center; justify-content: space-between; }
                .s0-land-price { font-size: 44px; }
                .s0-cta { margin: 0 48px 48px; padding: 50px; }
                .s0-footer { padding: 24px 48px; flex-direction: row; text-align: left; }
                @media (max-width: 768px) {
                    .s0-header { padding: 16px 20px; }
                    .s0-hero { padding: 36px 20px 28px; }
                    .s0-grid-wrap { padding: 8px 20px 36px; }
                    .s0-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
                    .s0-land { flex-direction: column; align-items: flex-start; gap: 16px; }
                    .s0-land-price { font-size: 32px; }
                    .s0-cta { margin: 0 20px 36px; padding: 32px 24px; }
                    .s0-footer { padding: 20px; flex-direction: column; gap: 6px; text-align: center; }
                }
                @media (max-width: 480px) {
                    .s0-grid { grid-template-columns: 1fr; }
                    .s0-header-yerevan { display: none; }
                }
            `}</style>

            <div
                style={{
                    minHeight: '100vh',
                    background: 'linear-gradient(180deg,#15171F,#101218)',
                    color: '#EAECF1',
                    fontFamily: "'Space Grotesk', sans-serif",
                    position: 'relative',
                    overflow: 'hidden',
                }}
            >
                <div
                    style={{
                        position: 'absolute',
                        inset: 0,
                        background:
                            'radial-gradient(110% 60% at 50% -5%,rgba(124,156,255,.09),transparent 55%)',
                        pointerEvents: 'none',
                    }}
                />

                {/* Header */}
                <div
                    className="s0-header"
                    style={{
                        position: 'relative',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between',
                        borderBottom: '1px solid rgba(255,255,255,.05)',
                    }}
                >
                    <div
                        style={{
                            display: 'flex',
                            alignItems: 'center',
                            gap: '10px',
                        }}
                    >
                        <span
                            style={{
                                width: '7px',
                                height: '7px',
                                borderRadius: '2px',
                                background: '#D6122E',
                                display: 'inline-block',
                            }}
                        />
                        <span
                            style={{
                                fontSize: '21px',
                                fontWeight: 700,
                                letterSpacing: '-.5px',
                                color: '#fff',
                            }}
                        >
                            {siteSettings.site_name || 'Atlas'}
                        </span>
                        <span style={{ fontSize: '17px' }}>🗺️</span>
                        <span
                            className="s0-header-yerevan"
                            style={{
                                fontFamily: "'JetBrains Mono', monospace",
                                fontSize: '10px',
                                letterSpacing: '2px',
                                color: '#737a86',
                                borderLeft: '1px solid rgba(255,255,255,.1)',
                                paddingLeft: '11px',
                            }}
                        >
                            {(currentMarket ?? 'MARKET').toUpperCase()}
                        </span>

                        <a
                            href="/"
                            style={{
                                fontFamily: "'JetBrains Mono', monospace",
                                fontSize: '11px',
                                letterSpacing: '1px',
                                color: '#5b626d',
                                textDecoration: 'none',
                                borderLeft: '1px solid rgba(255,255,255,.06)',
                                paddingLeft: '11px',
                                transition: 'color .2s',
                            }}
                            onMouseEnter={(e) =>
                                (e.currentTarget.style.color = '#9aa1ad')
                            }
                            onMouseLeave={(e) =>
                                (e.currentTarget.style.color = '#5b626d')
                            }
                        >
                            ← All Markets
                        </a>
                    </div>
                    <div
                        style={{
                            display: 'flex',
                            alignItems: 'center',
                            gap: '18px',
                        }}
                    >
                        {auth.user ? (
                            <Link
                                href={`/${currentMarket ?? 'yerevan'}/admin/dashboard`}
                                style={{
                                    fontSize: '14px',
                                    color: '#fff',
                                    textDecoration: 'none',
                                    fontWeight: 600,
                                    padding: '10px 18px',
                                    borderRadius: '9px',
                                    background: 'rgba(255,255,255,.08)',
                                    border: '1px solid rgba(255,255,255,.09)',
                                }}
                            >
                                Go to Dashboard →
                            </Link>
                        ) : (
                            <Link
                                href={login().url}
                                style={{
                                    fontSize: '14px',
                                    color: '#fff',
                                    textDecoration: 'none',
                                    fontWeight: 600,
                                    padding: '10px 18px',
                                    borderRadius: '9px',
                                    background: 'rgba(255,255,255,.08)',
                                    border: '1px solid rgba(255,255,255,.09)',
                                }}
                            >
                                Log in
                            </Link>
                        )}
                    </div>
                </div>

                {/* Hero */}
                <div
                    className="s0-hero"
                    style={{ position: 'relative', overflow: 'hidden' }}
                >
                    <div
                        style={{
                            position: 'absolute',
                            left: 0,
                            right: 0,
                            bottom: 0,
                            height: '150px',
                            display: 'flex',
                            alignItems: 'flex-end',
                            gap: '7px',
                            padding: '0 48px',
                            opacity: 0.55,
                            pointerEvents: 'none',
                            WebkitMaskImage:
                                'linear-gradient(180deg,transparent,#000 70%)',
                            maskImage:
                                'linear-gradient(180deg,transparent,#000 70%)',
                        }}
                    >
                        {BARS.map((b, i) => (
                            <div
                                key={i}
                                style={{
                                    flex: 1,
                                    height: `${b}%`,
                                    background:
                                        'linear-gradient(180deg,rgba(124,156,255,.22),rgba(124,156,255,.02))',
                                    borderRadius: '3px 3px 0 0',
                                }}
                            />
                        ))}
                    </div>

                    <div style={{ position: 'relative' }}>
                        <div
                            style={{
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: '10px',
                                padding: '8px 15px',
                                border: '1px solid rgba(255,255,255,.09)',
                                borderRadius: '30px',
                                background: 'rgba(255,255,255,.03)',
                                marginBottom: '24px',
                            }}
                        >
                            <span
                                style={{
                                    width: '8px',
                                    height: '8px',
                                    borderRadius: '50%',
                                    background: '#46c08a',
                                    animation: 'pulseDot 2s infinite',
                                    display: 'inline-block',
                                }}
                            />
                            <span
                                style={{
                                    fontFamily: "'JetBrains Mono', monospace",
                                    fontSize: '11px',
                                    letterSpacing: '1.5px',
                                    color: '#d6dae2',
                                    fontWeight: 600,
                                }}
                            >
                                LIVE MARKET DATA
                            </span>
                            <span
                                style={{
                                    width: '1px',
                                    height: '11px',
                                    background: 'rgba(255,255,255,.14)',
                                    display: 'inline-block',
                                }}
                            />
                            <span
                                style={{
                                    fontFamily: "'JetBrains Mono', monospace",
                                    fontSize: '11px',
                                    color: '#7c8390',
                                }}
                            >
                                updated daily
                            </span>
                        </div>

                        <h1
                            style={{
                                fontSize: 'clamp(28px, 5vw, 54px)',
                                lineHeight: 1.08,
                                fontWeight: 600,
                                letterSpacing: '-1.5px',
                                color: '#fff',
                                margin: '0 0 16px',
                                maxWidth: '820px',
                            }}
                        >
                            {currentMarket
                                ? currentMarket.charAt(0).toUpperCase() +
                                  currentMarket.slice(1)
                                : 'Real estate'}
                            , priced in real time.
                        </h1>

                        <p
                            style={{
                                fontSize: 'clamp(15px, 2vw, 18px)',
                                lineHeight: 1.55,
                                color: '#9aa1ad',
                                margin: '0 0 24px',
                                maxWidth: '620px',
                            }}
                        >
                            {siteSettings.site_description ||
                                'Live median pricing and active inventory across seven asset classes — the market view behind Atlas.'}
                        </p>

                        <div
                            style={{
                                display: 'flex',
                                alignItems: 'center',
                                gap: '16px',
                                fontFamily: "'JetBrains Mono', monospace",
                                fontSize: '12px',
                                color: '#7c8390',
                                flexWrap: 'wrap',
                            }}
                        >
                            <span>
                                {data ? data.categories.length : 7} asset
                                classes
                            </span>
                            <span style={{ color: 'rgba(255,255,255,.14)' }}>
                                |
                            </span>
                            <span>
                                <span style={{ color: '#d6dae2' }}>
                                    {totalListings.toLocaleString()}
                                </span>{' '}
                                active listings
                            </span>
                            <span style={{ color: 'rgba(255,255,255,.14)' }}>
                                |
                            </span>
                            <span>refreshed daily</span>
                        </div>
                    </div>
                </div>

                {/* Grid */}
                <div className="s0-grid-wrap" style={{ position: 'relative' }}>
                    <div
                        style={{
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'space-between',
                            marginBottom: '18px',
                        }}
                    >
                        <span
                            style={{
                                fontFamily: "'JetBrains Mono', monospace",
                                fontSize: '11px',
                                letterSpacing: '2px',
                                color: '#7c8390',
                                fontWeight: 600,
                            }}
                        >
                            MARKET SNAPSHOT
                        </span>
                        <span
                            style={{
                                fontFamily: "'JetBrains Mono', monospace",
                                fontSize: '11px',
                                color: '#5b626d',
                            }}
                        >
                            USD per m²
                        </span>
                    </div>

                    {error && (
                        <p style={{ color: '#858c97', fontSize: '14px' }}>
                            Market data is temporarily unavailable. Please check
                            back shortly.
                        </p>
                    )}

                    {!error && !data && (
                        <p style={{ color: '#858c97', fontSize: '14px' }}>
                            Loading market data...
                        </p>
                    )}

                    {data && (
                        <>
                            <div className="s0-grid">
                                {gridCats.map((cat, i) => (
                                    <CategoryCard
                                        key={cat.source}
                                        cat={cat}
                                        index={i}
                                        progress={progress}
                                        entered={entered}
                                    />
                                ))}
                            </div>

                            {landCat && (
                                <div
                                    className="s0-land"
                                    style={{
                                        position: 'relative',
                                        gap: '30px',
                                        marginTop: '18px',
                                        background:
                                            'linear-gradient(180deg,rgba(255,255,255,.035),rgba(255,255,255,.012))',
                                        border: '1px solid rgba(255,255,255,.07)',
                                        borderRadius: '15px',
                                        padding: '24px 28px',
                                        overflow: 'hidden',
                                        boxShadow:
                                            '0 10px 30px rgba(0,0,0,.25)',
                                        opacity: entered ? 1 : 0,
                                        transform: entered
                                            ? 'translateY(0)'
                                            : 'translateY(16px)',
                                        transition:
                                            'opacity .55s ease 480ms, transform .55s ease 480ms',
                                    }}
                                >
                                    <div
                                        style={{
                                            display: 'flex',
                                            alignItems: 'center',
                                            gap: '18px',
                                        }}
                                    >
                                        <span
                                            style={{
                                                display: 'inline-flex',
                                                alignItems: 'center',
                                                justifyContent: 'center',
                                                width: '54px',
                                                height: '54px',
                                                borderRadius: '13px',
                                                fontSize: '28px',
                                                background: SALE_SOFT,
                                                flexShrink: 0,
                                            }}
                                        >
                                            🌍
                                        </span>
                                        <div>
                                            <div
                                                style={{
                                                    display: 'flex',
                                                    alignItems: 'center',
                                                    gap: '10px',
                                                    marginBottom: '6px',
                                                    flexWrap: 'wrap',
                                                }}
                                            >
                                                <span
                                                    style={{
                                                        fontSize: '17px',
                                                        color: '#d6dae2',
                                                        fontWeight: 600,
                                                    }}
                                                >
                                                    {landCat.label}
                                                </span>
                                                <span
                                                    style={{
                                                        fontFamily:
                                                            "'JetBrains Mono', monospace",
                                                        fontSize: '10px',
                                                        letterSpacing: '1.5px',
                                                        fontWeight: 600,
                                                        padding: '4px 9px',
                                                        borderRadius: '6px',
                                                        color: SALE_COLOR,
                                                        background: SALE_SOFT,
                                                    }}
                                                >
                                                    SALE
                                                </span>
                                            </div>
                                            <span
                                                style={{
                                                    fontFamily:
                                                        "'JetBrains Mono', monospace",
                                                    fontSize: '12px',
                                                    color: '#858c97',
                                                }}
                                            >
                                                {Math.round(
                                                    landCat.total_listings *
                                                        progress,
                                                ).toLocaleString()}{' '}
                                                listings across{' '}
                                                {currentMarket ?? 'the market'}{' '}
                                                districts
                                            </span>
                                        </div>
                                    </div>
                                    <div
                                        style={{
                                            display: 'flex',
                                            alignItems: 'baseline',
                                            gap: '7px',
                                        }}
                                    >
                                        <span
                                            className="s0-land-price"
                                            style={{
                                                fontFamily:
                                                    "'JetBrains Mono', monospace",
                                                fontWeight: 600,
                                                color: SALE_COLOR,
                                                letterSpacing: '-1.5px',
                                                lineHeight: 1,
                                            }}
                                        >
                                            {landCat.median_price_per_sqm !==
                                            null
                                                ? `$${Math.round(landCat.median_price_per_sqm * progress).toLocaleString()}`
                                                : '—'}
                                        </span>
                                        <span
                                            style={{
                                                fontFamily:
                                                    "'JetBrains Mono', monospace",
                                                fontSize: '13px',
                                                color: '#6b7280',
                                            }}
                                        >
                                            /m²
                                        </span>
                                    </div>
                                </div>
                            )}
                        </>
                    )}
                </div>

                {/* CTA */}
                <div
                    className="s0-cta"
                    style={{
                        position: 'relative',
                        borderRadius: '16px',
                        textAlign: 'center',
                        background:
                            'linear-gradient(180deg,rgba(255,255,255,.04),rgba(255,255,255,.012))',
                        border: '1px solid rgba(255,255,255,.08)',
                        overflow: 'hidden',
                    }}
                >
                    <div
                        style={{
                            position: 'absolute',
                            inset: 0,
                            background:
                                'radial-gradient(80% 130% at 50% 130%,rgba(214,18,46,.16),transparent 60%)',
                            pointerEvents: 'none',
                        }}
                    />
                    <h2
                        style={{
                            position: 'relative',
                            fontSize: 'clamp(22px, 4vw, 32px)',
                            fontWeight: 600,
                            letterSpacing: '-.8px',
                            color: '#fff',
                            margin: '0 0 12px',
                        }}
                    >
                        Get the full picture.
                    </h2>
                    <p
                        style={{
                            position: 'relative',
                            fontSize: '15px',
                            color: '#9aa1ad',
                            margin: '0 auto 28px',
                            maxWidth: '500px',
                            lineHeight: 1.55,
                        }}
                    >
                        Request analyst access to live dashboards, historical
                        trends and neighbourhood-level intelligence.
                    </p>
                    <button
                        onClick={() => {
                            navigator.clipboard.writeText(
                                siteSettings.site_email ||
                                    'admin@example.com',
                            );
                            setCopied(true);
                            setTimeout(() => setCopied(false), 2000);
                        }}
                        style={{
                            position: 'relative',
                            display: 'inline-block',
                            background: '#D6122E',
                            color: '#fff',
                            fontSize: '15px',
                            fontWeight: 600,
                            padding: '15px 30px',
                            borderRadius: '10px',
                            border: 'none',
                            cursor: 'pointer',
                            boxShadow: '0 10px 30px rgba(214,18,46,.32)',
                            fontFamily: "'Space Grotesk', sans-serif",
                            transition: 'opacity .2s',
                        }}
                    >
                        {copied ? '✓ Email Copied!' : 'Request Full Access →'}
                    </button>
                </div>

                {/* Footer */}
                <div
                    className="s0-footer"
                    style={{
                        position: 'relative',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between',
                        borderTop: '1px solid rgba(255,255,255,.05)',
                        fontFamily: "'JetBrains Mono', monospace",
                        fontSize: '11px',
                        color: '#6b7280',
                    }}
                >
                    <span>Powered by live scraped data</span>
                    <span>
                        © 2026 Atlas ·{' '}
                        {currentMarket
                            ? currentMarket.charAt(0).toUpperCase() +
                              currentMarket.slice(1)
                            : 'Atlas'}
                    </span>
                </div>
            </div>
        </>
    );
}
