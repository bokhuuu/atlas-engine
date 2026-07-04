import { Link } from '@inertiajs/react';
import {
    Settings,
    Users,
    LayoutGrid,
    Zap,
    DollarSign,
    Activity,
    ShieldCheck,
    KeyRound,
    HeartPulse,
    ChartNoAxesColumn,
    Bot,
    Globe,
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
import { dashboard } from '@/routes/admin';
import type { NavItem } from '@/types';

interface NavItemWithRoles extends NavItem {
    roles: string[];
}

const footerNavItems: NavItem[] = [];

interface SystemUsage {
    this_month: { tokens: number; cost_usd: number };
    all_time: { tokens: number; cost_usd: number };
}

const intelligenceItems: NavItemWithRoles[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
        roles: ['super_admin', 'admin', 'editor'],
    },
    {
        title: 'Market Stats',
        href: '/admin/market-stats',
        icon: ChartNoAxesColumn,
        roles: ['super_admin', 'admin', 'editor'],
    },
    {
        title: 'AI Assistant',
        href: '/admin/assistant',
        icon: Bot,
        roles: ['super_admin', 'admin', 'editor'],
    },
    {
        title: 'Public Site',
        href: '/',
        icon: Globe,
        roles: ['super_admin', 'admin', 'editor'],
    },
];

const administrationItems: NavItemWithRoles[] = [
    {
        title: 'Users',
        href: '/admin/users',
        icon: Users,
        roles: ['super_admin', 'admin'],
    },
    {
        title: 'Roles & Permissions',
        href: '/admin/roles',
        icon: ShieldCheck,
        roles: ['super_admin'],
    },
    {
        title: 'System Health',
        href: '/admin/system-health',
        icon: HeartPulse,
        roles: ['super_admin'],
    },
    {
        title: 'Site Settings',
        href: '/admin/settings',
        icon: Settings,
        roles: ['super_admin', 'admin'],
    },
    {
        title: 'Activity Log',
        href: '/admin/activity-log',
        icon: Activity,
        roles: ['super_admin', 'admin'],
    },
    {
        title: 'API Tokens',
        href: '/admin/tokens',
        icon: KeyRound,
        roles: ['super_admin', 'admin'],
    },
];

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

export function AppSidebar() {
    const { role } = useAuth();
    const [systemUsage, setSystemUsage] = useState<SystemUsage | null>(null);

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
            .then((r) => r.json())
            .then((data) => setSystemUsage(data))
            .catch(() => {});
    }, [role]);

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent>
                <NavGroup
                    label="Intelligence"
                    items={intelligenceItems}
                    role={role}
                />

                <div className="mx-4 my-1 border-t border-sidebar-border/50" />

                <NavGroup
                    label="Administration"
                    items={administrationItems}
                    role={role}
                />
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
                                <div className="flex items-center gap-1.5">
                                    <span className="flex items-center gap-0.5 text-[10px] text-foreground tabular-nums">
                                        <Zap className="h-2.5 w-2.5 text-primary" />
                                        {systemUsage.this_month.tokens.toLocaleString()}
                                    </span>
                                    <span className="flex items-center gap-0.5 text-[10px] text-foreground tabular-nums">
                                        <DollarSign className="h-2.5 w-2.5 text-primary" />
                                        {formatCost(
                                            systemUsage.this_month.cost_usd,
                                        )}
                                    </span>
                                </div>
                            </div>
                            <div className="flex items-center justify-between">
                                <span className="text-[10px] text-muted-foreground">
                                    All time
                                </span>
                                <div className="flex items-center gap-1.5">
                                    <span className="flex items-center gap-0.5 text-[10px] text-foreground tabular-nums">
                                        <Zap className="h-2.5 w-2.5 text-muted-foreground" />
                                        {systemUsage.all_time.tokens.toLocaleString()}
                                    </span>
                                    <span className="flex items-center gap-0.5 text-[10px] text-foreground tabular-nums">
                                        <DollarSign className="h-2.5 w-2.5 text-muted-foreground" />
                                        {formatCost(
                                            systemUsage.all_time.cost_usd,
                                        )}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

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
