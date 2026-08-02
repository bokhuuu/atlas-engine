import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\GlobalDashboardController::dashboard
* @see app/Http/Controllers/Admin/GlobalDashboardController.php:35
* @route '/global/admin/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/global/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\GlobalDashboardController::dashboard
* @see app/Http/Controllers/Admin/GlobalDashboardController.php:35
* @route '/global/admin/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GlobalDashboardController::dashboard
* @see app/Http/Controllers/Admin/GlobalDashboardController.php:35
* @route '/global/admin/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\GlobalDashboardController::dashboard
* @see app/Http/Controllers/Admin/GlobalDashboardController.php:35
* @route '/global/admin/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\GlobalDashboardController::dashboard
* @see app/Http/Controllers/Admin/GlobalDashboardController.php:35
* @route '/global/admin/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\GlobalDashboardController::dashboard
* @see app/Http/Controllers/Admin/GlobalDashboardController.php:35
* @route '/global/admin/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\GlobalDashboardController::dashboard
* @see app/Http/Controllers/Admin/GlobalDashboardController.php:35
* @route '/global/admin/dashboard'
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
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
export const assistant = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

assistant.definition = {
    methods: ["get","head"],
    url: '/global/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
assistant.url = (options?: RouteQueryOptions) => {
    return assistant.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
assistant.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
assistant.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: assistant.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
const assistantForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
assistantForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: assistant.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::assistant
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
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

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::marketStats
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
export const marketStats = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

marketStats.definition = {
    methods: ["get","head"],
    url: '/global/admin/market-stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::marketStats
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
marketStats.url = (options?: RouteQueryOptions) => {
    return marketStats.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::marketStats
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
marketStats.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::marketStats
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
marketStats.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: marketStats.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::marketStats
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
const marketStatsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::marketStats
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
marketStatsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketStats.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::marketStats
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
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

const admin = {
    dashboard: Object.assign(dashboard, dashboard),
    assistant: Object.assign(assistant, assistant),
    marketStats: Object.assign(marketStats, marketStats),
}

export default admin