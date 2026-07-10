import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clear
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/yerevan/admin/system-health/cache'
*/
export const clear = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clear.url(options),
    method: 'post',
})

clear.definition = {
    methods: ["post"],
    url: '/yerevan/admin/system-health/cache',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clear
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/yerevan/admin/system-health/cache'
*/
clear.url = (options?: RouteQueryOptions) => {
    return clear.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clear
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/yerevan/admin/system-health/cache'
*/
clear.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clear.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clear
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/baku/admin/system-health/cache'
*/
export const clear = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clear.url(options),
    method: 'post',
})

clear.definition = {
    methods: ["post"],
    url: '/baku/admin/system-health/cache',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clear
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/baku/admin/system-health/cache'
*/
clear.url = (options?: RouteQueryOptions) => {
    return clear.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clear
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/baku/admin/system-health/cache'
*/
clear.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clear.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clear
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/tbilisi/admin/system-health/cache'
*/
export const clear = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clear.url(options),
    method: 'post',
})

clear.definition = {
    methods: ["post"],
    url: '/tbilisi/admin/system-health/cache',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clear
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/tbilisi/admin/system-health/cache'
*/
clear.url = (options?: RouteQueryOptions) => {
    return clear.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clear
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/tbilisi/admin/system-health/cache'
*/
clear.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clear.url(options),
    method: 'post',
})

const cache = {
    clear: Object.assign(clear, clear),
}

export default cache