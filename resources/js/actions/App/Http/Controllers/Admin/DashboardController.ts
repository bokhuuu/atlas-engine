import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
*/
const indexe40a71b1139bd81f7dfe8236fee19fcf = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexe40a71b1139bd81f7dfe8236fee19fcf.url(options),
    method: 'get',
})

indexe40a71b1139bd81f7dfe8236fee19fcf.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
*/
indexe40a71b1139bd81f7dfe8236fee19fcf.url = (options?: RouteQueryOptions) => {
    return indexe40a71b1139bd81f7dfe8236fee19fcf.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
*/
indexe40a71b1139bd81f7dfe8236fee19fcf.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexe40a71b1139bd81f7dfe8236fee19fcf.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
*/
indexe40a71b1139bd81f7dfe8236fee19fcf.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexe40a71b1139bd81f7dfe8236fee19fcf.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
*/
const indexe40a71b1139bd81f7dfe8236fee19fcfForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexe40a71b1139bd81f7dfe8236fee19fcf.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
*/
indexe40a71b1139bd81f7dfe8236fee19fcfForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexe40a71b1139bd81f7dfe8236fee19fcf.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/yerevan/admin/dashboard'
*/
indexe40a71b1139bd81f7dfe8236fee19fcfForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexe40a71b1139bd81f7dfe8236fee19fcf.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexe40a71b1139bd81f7dfe8236fee19fcf.form = indexe40a71b1139bd81f7dfe8236fee19fcfForm
/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
const indexf2f567d2bcafe0f55e3414f109da61d9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexf2f567d2bcafe0f55e3414f109da61d9.url(options),
    method: 'get',
})

indexf2f567d2bcafe0f55e3414f109da61d9.definition = {
    methods: ["get","head"],
    url: '/baku/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
indexf2f567d2bcafe0f55e3414f109da61d9.url = (options?: RouteQueryOptions) => {
    return indexf2f567d2bcafe0f55e3414f109da61d9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
indexf2f567d2bcafe0f55e3414f109da61d9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexf2f567d2bcafe0f55e3414f109da61d9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
indexf2f567d2bcafe0f55e3414f109da61d9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexf2f567d2bcafe0f55e3414f109da61d9.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
const indexf2f567d2bcafe0f55e3414f109da61d9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexf2f567d2bcafe0f55e3414f109da61d9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
indexf2f567d2bcafe0f55e3414f109da61d9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexf2f567d2bcafe0f55e3414f109da61d9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/baku/admin/dashboard'
*/
indexf2f567d2bcafe0f55e3414f109da61d9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexf2f567d2bcafe0f55e3414f109da61d9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexf2f567d2bcafe0f55e3414f109da61d9.form = indexf2f567d2bcafe0f55e3414f109da61d9Form
/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
const indexc6fe9d6ace573f536ed0ff17a7a8a8be = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexc6fe9d6ace573f536ed0ff17a7a8a8be.url(options),
    method: 'get',
})

indexc6fe9d6ace573f536ed0ff17a7a8a8be.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
indexc6fe9d6ace573f536ed0ff17a7a8a8be.url = (options?: RouteQueryOptions) => {
    return indexc6fe9d6ace573f536ed0ff17a7a8a8be.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
indexc6fe9d6ace573f536ed0ff17a7a8a8be.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexc6fe9d6ace573f536ed0ff17a7a8a8be.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
indexc6fe9d6ace573f536ed0ff17a7a8a8be.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexc6fe9d6ace573f536ed0ff17a7a8a8be.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
const indexc6fe9d6ace573f536ed0ff17a7a8a8beForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexc6fe9d6ace573f536ed0ff17a7a8a8be.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
indexc6fe9d6ace573f536ed0ff17a7a8a8beForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexc6fe9d6ace573f536ed0ff17a7a8a8be.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DashboardController::index
* @see app/Http/Controllers/Admin/DashboardController.php:35
* @route '/tbilisi/admin/dashboard'
*/
indexc6fe9d6ace573f536ed0ff17a7a8a8beForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexc6fe9d6ace573f536ed0ff17a7a8a8be.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexc6fe9d6ace573f536ed0ff17a7a8a8be.form = indexc6fe9d6ace573f536ed0ff17a7a8a8beForm

export const index = {
    '/yerevan/admin/dashboard': indexe40a71b1139bd81f7dfe8236fee19fcf,
    '/baku/admin/dashboard': indexf2f567d2bcafe0f55e3414f109da61d9,
    '/tbilisi/admin/dashboard': indexc6fe9d6ace573f536ed0ff17a7a8a8be,
}

const DashboardController = { index }

export default DashboardController