import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::index
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/global/admin/market-stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::index
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::index
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::index
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::index
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::index
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\GlobalMarketStatsController::index
* @see app/Http/Controllers/Admin/GlobalMarketStatsController.php:35
* @route '/global/admin/market-stats'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const GlobalMarketStatsController = { index }

export default GlobalMarketStatsController