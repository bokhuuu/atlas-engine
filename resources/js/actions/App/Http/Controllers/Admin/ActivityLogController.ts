import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
const indexbd941478c310c8baa364074ed1fbc7c5 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexbd941478c310c8baa364074ed1fbc7c5.url(options),
    method: 'get',
})

indexbd941478c310c8baa364074ed1fbc7c5.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/activity-log',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
indexbd941478c310c8baa364074ed1fbc7c5.url = (options?: RouteQueryOptions) => {
    return indexbd941478c310c8baa364074ed1fbc7c5.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
indexbd941478c310c8baa364074ed1fbc7c5.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexbd941478c310c8baa364074ed1fbc7c5.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
indexbd941478c310c8baa364074ed1fbc7c5.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexbd941478c310c8baa364074ed1fbc7c5.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
const indexbd941478c310c8baa364074ed1fbc7c5Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexbd941478c310c8baa364074ed1fbc7c5.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
indexbd941478c310c8baa364074ed1fbc7c5Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexbd941478c310c8baa364074ed1fbc7c5.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/yerevan/admin/activity-log'
*/
indexbd941478c310c8baa364074ed1fbc7c5Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexbd941478c310c8baa364074ed1fbc7c5.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexbd941478c310c8baa364074ed1fbc7c5.form = indexbd941478c310c8baa364074ed1fbc7c5Form
/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
const indexf88f8d5ecd56e51b626b3ab23a6053f9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexf88f8d5ecd56e51b626b3ab23a6053f9.url(options),
    method: 'get',
})

indexf88f8d5ecd56e51b626b3ab23a6053f9.definition = {
    methods: ["get","head"],
    url: '/baku/admin/activity-log',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
indexf88f8d5ecd56e51b626b3ab23a6053f9.url = (options?: RouteQueryOptions) => {
    return indexf88f8d5ecd56e51b626b3ab23a6053f9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
indexf88f8d5ecd56e51b626b3ab23a6053f9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexf88f8d5ecd56e51b626b3ab23a6053f9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
indexf88f8d5ecd56e51b626b3ab23a6053f9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexf88f8d5ecd56e51b626b3ab23a6053f9.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
const indexf88f8d5ecd56e51b626b3ab23a6053f9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexf88f8d5ecd56e51b626b3ab23a6053f9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
indexf88f8d5ecd56e51b626b3ab23a6053f9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexf88f8d5ecd56e51b626b3ab23a6053f9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/baku/admin/activity-log'
*/
indexf88f8d5ecd56e51b626b3ab23a6053f9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexf88f8d5ecd56e51b626b3ab23a6053f9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexf88f8d5ecd56e51b626b3ab23a6053f9.form = indexf88f8d5ecd56e51b626b3ab23a6053f9Form
/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
const indexedc2b1e31b24934fcc92a42ac309a455 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexedc2b1e31b24934fcc92a42ac309a455.url(options),
    method: 'get',
})

indexedc2b1e31b24934fcc92a42ac309a455.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/activity-log',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
indexedc2b1e31b24934fcc92a42ac309a455.url = (options?: RouteQueryOptions) => {
    return indexedc2b1e31b24934fcc92a42ac309a455.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
indexedc2b1e31b24934fcc92a42ac309a455.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexedc2b1e31b24934fcc92a42ac309a455.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
indexedc2b1e31b24934fcc92a42ac309a455.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexedc2b1e31b24934fcc92a42ac309a455.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
const indexedc2b1e31b24934fcc92a42ac309a455Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexedc2b1e31b24934fcc92a42ac309a455.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
indexedc2b1e31b24934fcc92a42ac309a455Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexedc2b1e31b24934fcc92a42ac309a455.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ActivityLogController::index
* @see app/Http/Controllers/Admin/ActivityLogController.php:27
* @route '/tbilisi/admin/activity-log'
*/
indexedc2b1e31b24934fcc92a42ac309a455Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexedc2b1e31b24934fcc92a42ac309a455.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexedc2b1e31b24934fcc92a42ac309a455.form = indexedc2b1e31b24934fcc92a42ac309a455Form

export const index = {
    '/yerevan/admin/activity-log': indexbd941478c310c8baa364074ed1fbc7c5,
    '/baku/admin/activity-log': indexf88f8d5ecd56e51b626b3ab23a6053f9,
    '/tbilisi/admin/activity-log': indexedc2b1e31b24934fcc92a42ac309a455,
}

const ActivityLogController = { index }

export default ActivityLogController