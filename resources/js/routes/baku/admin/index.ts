import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
import assistant4160f0 from './assistant'
import exports from './exports'
import users from './users'
import settings from './settings'
import roles from './roles'
import activityLog from './activity-log'
import notifications from './notifications'
import tokens from './tokens'
import systemHealth from './system-health'
import aiFeedback from './ai-feedback'
import dataHub from './data-hub'
/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/baku/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
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
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
export const marketStats = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

marketStats.definition = {
    methods: ["get","head"],
    url: '/baku/admin/market-stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
marketStats.url = (options?: RouteQueryOptions) => {
    return marketStats.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
marketStats.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
marketStats.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: marketStats.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
const marketStatsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
marketStatsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
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
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
export const assistant = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

assistant.definition = {
    methods: ["get","head"],
    url: '/baku/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
assistant.url = (options?: RouteQueryOptions) => {
    return assistant.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
assistant.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
assistant.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: assistant.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
const assistantForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
assistantForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
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
    assistant: Object.assign(assistant, assistant4160f0),
    exports: Object.assign(exports, exports),
    users: Object.assign(users, users),
    settings: Object.assign(settings, settings),
    roles: Object.assign(roles, roles),
    activityLog: Object.assign(activityLog, activityLog),
    notifications: Object.assign(notifications, notifications),
    tokens: Object.assign(tokens, tokens),
    systemHealth: Object.assign(systemHealth, systemHealth),
    aiFeedback: Object.assign(aiFeedback, aiFeedback),
    dataHub: Object.assign(dataHub, dataHub),
}

export default admin