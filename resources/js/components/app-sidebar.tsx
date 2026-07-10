import { Link, usePage } from '@inertiajs/react';
import {
    Settings,
    Users,
    LayoutGrid,
    DollarSign,
    Activity,
    ThumbsUp,
    ShieldCheck,
    Key,
    ChartNoAxesColumn,
    Bot,
    Globe,
    Database,
    ExternalLink,
} from 'lucide-react';
import { useState, useEffect } from 'react';
import AppLogo from '@/components/app-logo';
import { NavFooter } from '@/components/nav-footer';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useAuth } from '@/hooks/use-auth';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { formatCost } from '@/lib/format-cost';
import type { NavItem } from '@/types';

interface NavItemWithRoles extends NavItem {
    roles: string[];
}

interface Market {
    label: string;
    country: string;
    flag: string;
    source_prefixes: string[];
    currency: string;
}

interface SharedProps {
    currentMarket: string | null;
    markets: Record<string, Market>;
    [key: string]: unknown;
}

const footerNavItems: NavItem[] = [];

interface SystemUsage {
    this_month: { tokens: number; cost_usd: number };
    all_time: { tokens: number; cost_usd: number };
}

function buildNavItems(market: string): {
    intelligence: NavItemWithRoles[];
    administration: NavItemWithRoles[];
    system: NavItemWithRoles[];
} {
    const base = `/${market}/admin`;

    return {
        intelligence: [
            {
                title: 'Dashboard',
                href: `${base}/dashboard`,
                icon: LayoutGrid,
                roles: ['super_admin', 'admin', 'editor'],
            },
            {
                title: 'Market Stats',
                href: `${base}/market-stats`,
                icon: ChartNoAxesColumn,
                roles: ['super_admin', 'admin', 'editor'],
            },
            {
                title: 'Data Hub',
                href: `${base}/data-hub`,
                icon: Database,
                roles: ['super_admin', 'admin'],
            },
            {
                title: 'AI Assistant',
                href: `${base}/assistant`,
                icon: Bot,
                roles: ['super_admin', 'admin', 'editor'],
            },
            {
                title: 'AI Feedback',
                href: `${base}/ai-feedback`,
                icon: ThumbsUp,
                roles: ['super_admin', 'admin'],
            },
        ],
        administration: [
            {
                title: 'Users',
                href: `${base}/users`,
                icon: Users,
                roles: ['super_admin', 'admin'],
            },
            {
                title: 'Roles & Permissions',
                href: `${base}/roles`,
                icon: ShieldCheck,
                roles: ['super_admin'],
            },
            {
                title: 'Site Settings',
                href: `${base}/settings`,
                icon: Settings,
                roles: ['super_admin', 'admin'],
            },
            {
                title: 'Activity Log',
                href: `${base}/activity-log`,
                icon: Activity,
                roles: ['super_admin', 'admin'],
            },
        ],
        system: [
            {
                title: 'System Health',
                href: `${base}/system-health`,
                icon: Activity,
                roles: ['super_admin', 'admin'],
            },
            {
                title: 'API Tokens',
                href: `${base}/tokens`,
                icon: Key,
                roles: ['super_admin', 'admin', 'editor'],
            },
        ],
    };
}

function NavGroup({
    label,
    items,
    role,
}: {
    label: string;
    items: NavItemWithRoles[];
    role: string;
}) {
    const { isCurrentOrParentUrl } = useCurrentUrl();
    const filtered = items.filter((item) => item.roles.includes(role));

    if (filtered.length === 0) {
        return null;
    }

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel className="text-[10px] font-semibold tracking-widest text-muted-foreground/60 uppercase">
                {label}
            </SidebarGroupLabel>
            <SidebarMenu>
                {filtered.map((item) => (
                    <SidebarMenuItem key={item.title}>
                        <SidebarMenuButton
                            asChild
                            isActive={
                                item.href === '/'
                                    ? false
                                    : isCurrentOrParentUrl(item.href)
                            }
                            tooltip={{ children: item.title }}
                        >
                            <Link href={item.href} prefetch>
                                {item.icon && <item.icon />}
                                <span>{item.title}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                ))}
            </SidebarMenu>
        </SidebarGroup>
    );
}

function MarketSwitcher({
    currentMarket,
    markets,
}: {
    currentMarket: string;
    markets: Record<string, Market>;
}) {
    return (
        <div className="mx-3 mb-3 grid auto-cols-fr grid-flow-col gap-1.5 rounded-xl border border-sidebar-border/40 bg-sidebar-accent/10 p-1.5">
            {Object.entries(markets).map(([slug, market]) => {
                const isActive = slug === currentMarket;

                return (
                    <Link
                        key={slug}
                        href={`/${slug}/admin/dashboard`}
                        title={market.label}
                        className={`group relative flex flex-col items-center gap-1 rounded-lg py-2 transition-all duration-200 ${
                            isActive
                                ? 'bg-sidebar-accent shadow-sm'
                                : 'hover:bg-sidebar-accent/50'
                        }`}
                    >
                        <span
                            className={`text-2xl transition-transform duration-200 ${
                                isActive
                                    ? 'scale-110 drop-shadow-[0_0_8px_rgba(99,102,241,0.8)]'
                                    : 'scale-90 opacity-50 group-hover:scale-100 group-hover:opacity-75'
                            }`}
                        >
                            {market.flag}
                        </span>
                        <span
                            className={`text-[9px] font-semibold tracking-widest uppercase transition-all duration-200 ${
                                isActive
                                    ? 'text-foreground opacity-100'
                                    : 'text-muted-foreground opacity-0 group-hover:opacity-60'
                            }`}
                        >
                            {market.label}
                        </span>
                        {isActive && (
                            <span className="absolute bottom-1 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full bg-primary" />
                        )}
                    </Link>
                );
            })}
        </div>
    );
}

export function AppSidebar() {
    const { role } = useAuth();
    const { currentMarket, markets } = usePage<SharedProps>().props;
    const [systemUsage, setSystemUsage] = useState<SystemUsage | null>(null);

    const market = currentMarket ?? 'yerevan';
    const navItems = buildNavItems(market);

    useEffect(() => {
        if (!['admin', 'super_admin'].includes(role)) {
            return;
        }

        fetch('/api/v1/ai/usage/system', {
            headers: {
                Accept: 'application/json',
                'X-XSRF-TOKEN': decodeURIComponent(
                    document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '',
                ),
            },
        })
            .then((r) => (r.ok ? r.json() : null))
            .then((data) => {
                if (data?.this_month && data?.all_time) {
                    setSystemUsage(data);
                }
            })
            .catch(() => {});
    }, [role]);

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={`/${market}/admin/dashboard`} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent>
                <MarketSwitcher
                    currentMarket={market}
                    markets={markets ?? {}}
                />

                <NavGroup
                    label="Intelligence & Data"
                    items={navItems.intelligence}
                    role={role}
                />

                <div className="mx-4 my-1 border-t border-sidebar-border/50" />

                <div className="opacity-60">
                    <NavGroup
                        label="Administration"
                        items={navItems.administration}
                        role={role}
                    />
                </div>

                <div className="mx-4 my-1 border-t border-sidebar-border/50" />

                <div className="opacity-60">
                    <NavGroup
                        label="System"
                        items={navItems.system}
                        role={role}
                    />
                </div>
            </SidebarContent>
            <SidebarFooter>
                {systemUsage && ['admin', 'super_admin'].includes(role) && (
                    <div className="mx-3 mb-2 rounded-lg border border-sidebar-border/50 bg-sidebar-accent/30 px-3 py-2">
                        <p className="mb-1.5 text-[10px] font-semibold tracking-widest text-muted-foreground/60 uppercase">
                            AI Usage
                        </p>
                        <div className="flex flex-col gap-1">
                            <div className="flex items-center justify-between">
                                <span className="text-[10px] text-muted-foreground">
                                    This month
                                </span>
                                <span className="flex animate-pulse items-center gap-0.5 text-[10px] text-emerald-400 tabular-nums">
                                    <DollarSign className="h-2.5 w-2.5 text-emerald-400" />
                                    {formatCost(
                                        systemUsage.this_month.cost_usd,
                                    )}
                                </span>
                            </div>
                            <div className="flex items-center justify-between">
                                <span className="text-[10px] text-muted-foreground">
                                    All time
                                </span>
                                <span className="flex animate-pulse items-center gap-0.5 text-[10px] text-amber-400 tabular-nums">
                                    <DollarSign className="h-2.5 w-2.5 text-amber-400" />
                                    {formatCost(systemUsage.all_time.cost_usd)}
                                </span>
                            </div>
                        </div>
                    </div>
                )}

                <div className="mx-3 mt-4 mb-2">
                    <a
                        href={`/${market}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="flex items-center gap-2 rounded-md px-2 py-1.5 text-xs text-blue-400 transition-colors hover:text-blue-300"
                    >
                        <Globe className="h-3.5 w-3.5" />
                        <span>Public Site</span>
                        <ExternalLink className="ml-auto h-3 w-3 opacity-50" />
                    </a>
                </div>

                <NavFooter items={footerNavItems} className="mt-auto" />

                <div className="px-2 pb-1">
                    <p className="px-2 text-[10px] font-semibold tracking-widest text-muted-foreground/60 uppercase">
                        Account
                    </p>
                </div>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
