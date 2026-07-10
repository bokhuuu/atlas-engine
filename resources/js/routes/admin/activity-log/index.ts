import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/activity-log',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/baku/admin/activity-log',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/activity-log',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

const activityLog = {
    index: Object.assign(index, index),
}

export default activityLog