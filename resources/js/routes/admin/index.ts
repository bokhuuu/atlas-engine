import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import exports from './exports'
import users from './users'
import settings from './settings'
import roles from './roles'
import activityLog from './activity-log'
import notifications from './notifications'
import tokens from './tokens'
import systemHealth from './system-health'
/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:33
* @route '/admin/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:33
* @route '/admin/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:33
* @route '/admin/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:33
* @route '/admin/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:33
* @route '/admin/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:33
* @route '/admin/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:33
* @route '/admin/dashboard'
*/
dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dashboard.form = dashboardForm

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:21
* @route '/admin/market-stats'
*/
export const marketStats = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

marketStats.definition = {
    methods: ["get","head"],
    url: '/admin/market-stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:21
* @route '/admin/market-stats'
*/
marketStats.url = (options?: RouteQueryOptions) => {
    return marketStats.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:21
* @route '/admin/market-stats'
*/
marketStats.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:21
* @route '/admin/market-stats'
*/
marketStats.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: marketStats.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:21
* @route '/admin/market-stats'
*/
const marketStatsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:21
* @route '/admin/market-stats'
*/
marketStatsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:21
* @route '/admin/market-stats'
*/
marketStatsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketStats.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

marketStats.form = marketStatsForm

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
export const assistant = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

assistant.definition = {
    methods: ["get","head"],
    url: '/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
assistant.url = (options?: RouteQueryOptions) => {
    return assistant.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
assistant.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
assistant.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: assistant.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
const assistantForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
assistantForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
assistantForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: assistant.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

assistant.form = assistantForm

const admin = {
    dashboard: Object.assign(dashboard, dashboard),
    marketStats: Object.assign(marketStats, marketStats),
    assistant: Object.assign(assistant, assistant),
    exports: Object.assign(exports, exports),
    users: Object.assign(users, users),
    settings: Object.assign(settings, settings),
    roles: Object.assign(roles, roles),
    activityLog: Object.assign(activityLog, activityLog),
    notifications: Object.assign(notifications, notifications),
    tokens: Object.assign(tokens, tokens),
    systemHealth: Object.assign(systemHealth, systemHealth),
}

export default admin