import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/data-hub',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/baku/admin/data-hub',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/data-hub',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

const dataHub = {
    index: Object.assign(index, index),
}

export default dataHub