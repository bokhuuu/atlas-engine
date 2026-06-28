import { Link } from '@inertiajs/react';
import {
    Settings,
    Users,
    LayoutGrid,
    Activity,
    ShieldCheck,
    KeyRound,
    HeartPulse,
    ChartNoAxesColumn,
    Bot,
    Globe,
} from 'lucide-react';
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
import { dashboard } from '@/routes/admin';
import type { NavItem } from '@/types';

interface NavItemWithRoles extends NavItem {
    roles: string[];
}

const footerNavItems: NavItem[] = [
    {
        title: 'Public Site',
        href: '/',
        icon: Globe,
    },
];

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
                            isActive={isCurrentOrParentUrl(item.href)}
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
