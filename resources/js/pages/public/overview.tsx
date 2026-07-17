import { Head, Link, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { login } from '@/routes';

interface MarketData {
    slug: string;
    label: string;
    flag: string;
    total_listings: number;
    active_categories: number;
    total_categories: number;
    has_data: boolean;
}

interface Props {
    markets: MarketData[];
    totalListingsAllMarkets: number;
}

const BARS = [
    38, 52, 30, 64, 46, 78, 40, 58, 34, 70, 48, 86, 42, 60, 36, 74, 50, 44, 66,
    32, 56, 80, 40, 62, 38, 72, 46, 54,
];

export default function Overview({ markets, totalListingsAllMarkets }: Props) {
    const { auth, siteSettings } = usePage().props as unknown as {
        auth: { user: { name: string } | null };
        siteSettings: {
            site_name: string;
            site_description: string;
            site_email: string;
            logo: string | null;
        };
    };

    const [entered, setEntered] = useState(false);
    const [progress, setProgress] = useState(0);
    const [copied, setCopied] = useState(false);

    useEffect(() => {
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
    }, []);

    const animatedTotal = Math.round(totalListingsAllMarkets * progress);

    return (
        <>
            <Head title="Caucasus Real Estate Intelligence — Atlas" />

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
                .ov-header { padding: 20px 48px; }
                .ov-hero { padding: 60px 48px 40px; }
                .ov-grid { padding: 8px 48px 52px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
                .ov-cta { margin: 0 48px 48px; padding: 50px; }
                .ov-footer { padding: 24px 48px; flex-direction: row; text-align: left; }
                @media (max-width: 768px) {
                    .ov-header { padding: 16px 20px; }
                    .ov-hero { padding: 36px 20px 28px; }
                    .ov-grid { padding: 8px 20px 36px; grid-template-columns: 1fr; gap: 12px; }
                    .ov-cta { margin: 0 20px 36px; padding: 32px 24px; }
                    .ov-footer { padding: 20px; flex-direction: column; gap: 6px; text-align: center; }
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
                    className="ov-header"
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
                                href="/yerevan/admin/dashboard"
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
                    className="ov-hero"
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
                            Caucasus real estate, tracked in real time.
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
                            Live inventory and pricing intelligence across
                            Yerevan, Baku and Tbilisi — the regional view behind
                            Atlas.
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
                            <span>{markets.length} markets tracked</span>
                            <span style={{ color: 'rgba(255,255,255,.14)' }}>
                                |
                            </span>
                            <span>
                                <span style={{ color: '#d6dae2' }}>
                                    {animatedTotal.toLocaleString()}
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

                {/* Market cards */}
                <div className="ov-grid">
                    {markets.map((market, i) => (
                        <div
                            key={market.slug}
                            style={{
                                position: 'relative',
                                background:
                                    'linear-gradient(180deg,rgba(255,255,255,.035),rgba(255,255,255,.012))',
                                border: '1px solid rgba(255,255,255,.07)',
                                borderRadius: '15px',
                                padding: '24px',
                                overflow: 'hidden',
                                boxShadow: '0 10px 30px rgba(0,0,0,.25)',
                                opacity: entered ? 1 : 0,
                                transform: entered
                                    ? 'translateY(0)'
                                    : 'translateY(16px)',
                                transition: `opacity .55s ease ${i * 100}ms, transform .55s ease ${i * 100}ms`,
                            }}
                        >
                            <div
                                style={{
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '12px',
                                    marginBottom: '20px',
                                }}
                            >
                                <span style={{ fontSize: '32px' }}>
                                    {market.flag}
                                </span>
                                <div>
                                    <div
                                        style={{
                                            fontSize: '18px',
                                            fontWeight: 600,
                                            color: '#fff',
                                        }}
                                    >
                                        {market.label}
                                    </div>
                                    {!market.has_data && (
                                        <div
                                            style={{
                                                fontFamily:
                                                    "'JetBrains Mono', monospace",
                                                fontSize: '10px',
                                                letterSpacing: '1.5px',
                                                color: '#5b626d',
                                                marginTop: '2px',
                                            }}
                                        >
                                            COMING SOON
                                        </div>
                                    )}
                                </div>
                            </div>

                            <div
                                style={{
                                    display: 'flex',
                                    flexDirection: 'column',
                                    gap: '12px',
                                    marginBottom: '20px',
                                }}
                            >
                                <div
                                    style={{
                                        display: 'flex',
                                        justifyContent: 'space-between',
                                        alignItems: 'baseline',
                                    }}
                                >
                                    <span
                                        style={{
                                            fontSize: '13px',
                                            color: '#7c8390',
                                        }}
                                    >
                                        Total Listings
                                    </span>
                                    <span
                                        style={{
                                            fontFamily:
                                                "'JetBrains Mono', monospace",
                                            fontSize: '20px',
                                            fontWeight: 600,
                                            color: market.has_data
                                                ? '#d6dae2'
                                                : '#3d4450',
                                        }}
                                    >
                                        {Math.round(
                                            market.total_listings * progress,
                                        ).toLocaleString()}
                                    </span>
                                </div>
                                <div
                                    style={{
                                        display: 'flex',
                                        justifyContent: 'space-between',
                                        alignItems: 'baseline',
                                    }}
                                >
                                    <span
                                        style={{
                                            fontSize: '13px',
                                            color: '#7c8390',
                                        }}
                                    >
                                        Active Categories
                                    </span>
                                    <span
                                        style={{
                                            fontFamily:
                                                "'JetBrains Mono', monospace",
                                            fontSize: '14px',
                                            color: market.has_data
                                                ? '#d6dae2'
                                                : '#3d4450',
                                        }}
                                    >
                                        {market.active_categories} /{' '}
                                        {market.total_categories}
                                    </span>
                                </div>
                            </div>

                            {market.has_data ? (
                                <a
                                    href={`/${market.slug}`}
                                    style={{
                                        display: 'block',
                                        textAlign: 'center',
                                        padding: '10px',
                                        borderRadius: '9px',
                                        background: 'rgba(255,255,255,.06)',
                                        border: '1px solid rgba(255,255,255,.08)',
                                        color: '#d6dae2',
                                        fontSize: '13px',
                                        fontWeight: 600,
                                        textDecoration: 'none',
                                        transition: 'background .2s',
                                    }}
                                >
                                    View {market.label} Market →
                                </a>
                            ) : (
                                <div
                                    style={{
                                        display: 'block',
                                        textAlign: 'center',
                                        padding: '10px',
                                        borderRadius: '9px',
                                        background: 'rgba(255,255,255,.02)',
                                        border: '1px solid rgba(255,255,255,.04)',
                                        color: '#3d4450',
                                        fontSize: '13px',
                                    }}
                                >
                                    Data collection starting soon
                                </div>
                            )}
                        </div>
                    ))}
                </div>

                {/* CTA */}
                <div
                    className="ov-cta"
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
                        trends and neighbourhood-level intelligence across the
                        Caucasus.
                    </p>
                    <button
                        onClick={() => {
                            navigator.clipboard.writeText(
                                siteSettings.site_email ||
                                    'zura.coding@gmail.com',
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
                    className="ov-footer"
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
                    <span>© 2026 Atlas · Caucasus Region</span>
                </div>
            </div>
        </>
    );
}
