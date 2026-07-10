import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
const index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.url(options),
    method: 'get',
})

index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/data-hub',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.url = (options?: RouteQueryOptions) => {
    return index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
const index7a6ddbc3ca6bf1a8189ad6b6d2b13ebdForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
index7a6ddbc3ca6bf1a8189ad6b6d2b13ebdForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/yerevan/admin/data-hub'
*/
index7a6ddbc3ca6bf1a8189ad6b6d2b13ebdForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd.form = index7a6ddbc3ca6bf1a8189ad6b6d2b13ebdForm
/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
const index6a5e85a428770fd42a59f1afecd31405 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index6a5e85a428770fd42a59f1afecd31405.url(options),
    method: 'get',
})

index6a5e85a428770fd42a59f1afecd31405.definition = {
    methods: ["get","head"],
    url: '/baku/admin/data-hub',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
index6a5e85a428770fd42a59f1afecd31405.url = (options?: RouteQueryOptions) => {
    return index6a5e85a428770fd42a59f1afecd31405.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
index6a5e85a428770fd42a59f1afecd31405.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index6a5e85a428770fd42a59f1afecd31405.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
index6a5e85a428770fd42a59f1afecd31405.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index6a5e85a428770fd42a59f1afecd31405.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
const index6a5e85a428770fd42a59f1afecd31405Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index6a5e85a428770fd42a59f1afecd31405.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
index6a5e85a428770fd42a59f1afecd31405Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index6a5e85a428770fd42a59f1afecd31405.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/baku/admin/data-hub'
*/
index6a5e85a428770fd42a59f1afecd31405Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index6a5e85a428770fd42a59f1afecd31405.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index6a5e85a428770fd42a59f1afecd31405.form = index6a5e85a428770fd42a59f1afecd31405Form
/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
const index293d0a17be715d08117fffa540d440b9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index293d0a17be715d08117fffa540d440b9.url(options),
    method: 'get',
})

index293d0a17be715d08117fffa540d440b9.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/data-hub',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
index293d0a17be715d08117fffa540d440b9.url = (options?: RouteQueryOptions) => {
    return index293d0a17be715d08117fffa540d440b9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
index293d0a17be715d08117fffa540d440b9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index293d0a17be715d08117fffa540d440b9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
index293d0a17be715d08117fffa540d440b9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index293d0a17be715d08117fffa540d440b9.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
const index293d0a17be715d08117fffa540d440b9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index293d0a17be715d08117fffa540d440b9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
index293d0a17be715d08117fffa540d440b9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index293d0a17be715d08117fffa540d440b9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\DataHubController::index
* @see app/Http/Controllers/Admin/DataHubController.php:23
* @route '/tbilisi/admin/data-hub'
*/
index293d0a17be715d08117fffa540d440b9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index293d0a17be715d08117fffa540d440b9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index293d0a17be715d08117fffa540d440b9.form = index293d0a17be715d08117fffa540d440b9Form

export const index = {
    '/yerevan/admin/data-hub': index7a6ddbc3ca6bf1a8189ad6b6d2b13ebd,
    '/baku/admin/data-hub': index6a5e85a428770fd42a59f1afecd31405,
    '/tbilisi/admin/data-hub': index293d0a17be715d08117fffa540d440b9,
}

const DataHubController = { index }

export default DataHubController