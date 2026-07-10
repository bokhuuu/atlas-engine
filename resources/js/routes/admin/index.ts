import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
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
* @route '/yerevan/admin/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
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
* @route '/tbilisi/admin/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::dashboard
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
export const marketStats = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

marketStats.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/market-stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
marketStats.url = (options?: RouteQueryOptions) => {
    return marketStats.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
marketStats.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
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
* @route '/tbilisi/admin/market-stats'
*/
export const marketStats = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

marketStats.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/market-stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
marketStats.url = (options?: RouteQueryOptions) => {
    return marketStats.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
marketStats.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::marketStats
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
marketStats.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: marketStats.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
export const assistant = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

assistant.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
assistant.url = (options?: RouteQueryOptions) => {
    return assistant.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
assistant.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
assistant.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: assistant.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
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
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
assistant.url = (options?: RouteQueryOptions) => {
    return assistant.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
assistant.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
assistant.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: assistant.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
export const assistant = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

assistant.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
assistant.url = (options?: RouteQueryOptions) => {
    return assistant.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
assistant.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
assistant.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: assistant.url(options),
    method: 'head',
})

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
    aiFeedback: Object.assign(aiFeedback, aiFeedback),
    dataHub: Object.assign(dataHub, dataHub),
}

export default admin