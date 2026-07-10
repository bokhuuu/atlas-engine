import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
const indexbd8200b901f6ef8a6b061e6b305105de = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexbd8200b901f6ef8a6b061e6b305105de.url(options),
    method: 'get',
})

indexbd8200b901f6ef8a6b061e6b305105de.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/market-stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
indexbd8200b901f6ef8a6b061e6b305105de.url = (options?: RouteQueryOptions) => {
    return indexbd8200b901f6ef8a6b061e6b305105de.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
indexbd8200b901f6ef8a6b061e6b305105de.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexbd8200b901f6ef8a6b061e6b305105de.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
indexbd8200b901f6ef8a6b061e6b305105de.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexbd8200b901f6ef8a6b061e6b305105de.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
const indexbd8200b901f6ef8a6b061e6b305105deForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexbd8200b901f6ef8a6b061e6b305105de.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
indexbd8200b901f6ef8a6b061e6b305105deForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexbd8200b901f6ef8a6b061e6b305105de.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/yerevan/admin/market-stats'
*/
indexbd8200b901f6ef8a6b061e6b305105deForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexbd8200b901f6ef8a6b061e6b305105de.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexbd8200b901f6ef8a6b061e6b305105de.form = indexbd8200b901f6ef8a6b061e6b305105deForm
/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
const index1380a9386fbf4e4fd19428fd771686fc = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1380a9386fbf4e4fd19428fd771686fc.url(options),
    method: 'get',
})

index1380a9386fbf4e4fd19428fd771686fc.definition = {
    methods: ["get","head"],
    url: '/baku/admin/market-stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
index1380a9386fbf4e4fd19428fd771686fc.url = (options?: RouteQueryOptions) => {
    return index1380a9386fbf4e4fd19428fd771686fc.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
index1380a9386fbf4e4fd19428fd771686fc.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1380a9386fbf4e4fd19428fd771686fc.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
index1380a9386fbf4e4fd19428fd771686fc.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index1380a9386fbf4e4fd19428fd771686fc.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
const index1380a9386fbf4e4fd19428fd771686fcForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index1380a9386fbf4e4fd19428fd771686fc.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
index1380a9386fbf4e4fd19428fd771686fcForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index1380a9386fbf4e4fd19428fd771686fc.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/baku/admin/market-stats'
*/
index1380a9386fbf4e4fd19428fd771686fcForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index1380a9386fbf4e4fd19428fd771686fc.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index1380a9386fbf4e4fd19428fd771686fc.form = index1380a9386fbf4e4fd19428fd771686fcForm
/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
const index8cfe190c194e015bd74aa8ddb8f4edb7 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index8cfe190c194e015bd74aa8ddb8f4edb7.url(options),
    method: 'get',
})

index8cfe190c194e015bd74aa8ddb8f4edb7.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/market-stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
index8cfe190c194e015bd74aa8ddb8f4edb7.url = (options?: RouteQueryOptions) => {
    return index8cfe190c194e015bd74aa8ddb8f4edb7.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
index8cfe190c194e015bd74aa8ddb8f4edb7.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index8cfe190c194e015bd74aa8ddb8f4edb7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
index8cfe190c194e015bd74aa8ddb8f4edb7.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index8cfe190c194e015bd74aa8ddb8f4edb7.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
const index8cfe190c194e015bd74aa8ddb8f4edb7Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index8cfe190c194e015bd74aa8ddb8f4edb7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
index8cfe190c194e015bd74aa8ddb8f4edb7Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index8cfe190c194e015bd74aa8ddb8f4edb7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MarketStatsController::index
* @see app/Http/Controllers/Admin/MarketStatsController.php:24
* @route '/tbilisi/admin/market-stats'
*/
index8cfe190c194e015bd74aa8ddb8f4edb7Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index8cfe190c194e015bd74aa8ddb8f4edb7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index8cfe190c194e015bd74aa8ddb8f4edb7.form = index8cfe190c194e015bd74aa8ddb8f4edb7Form

export const index = {
    '/yerevan/admin/market-stats': indexbd8200b901f6ef8a6b061e6b305105de,
    '/baku/admin/market-stats': index1380a9386fbf4e4fd19428fd771686fc,
    '/tbilisi/admin/market-stats': index8cfe190c194e015bd74aa8ddb8f4edb7,
}

const MarketStatsController = { index }

export default MarketStatsController