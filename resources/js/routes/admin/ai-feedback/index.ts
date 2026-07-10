import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/yerevan/admin/ai-feedback'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/ai-feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/yerevan/admin/ai-feedback'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/yerevan/admin/ai-feedback'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/yerevan/admin/ai-feedback'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/baku/admin/ai-feedback'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/baku/admin/ai-feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/baku/admin/ai-feedback'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/baku/admin/ai-feedback'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/baku/admin/ai-feedback'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/tbilisi/admin/ai-feedback'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/ai-feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/tbilisi/admin/ai-feedback'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/tbilisi/admin/ai-feedback'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/tbilisi/admin/ai-feedback'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

const aiFeedback = {
    index: Object.assign(index, index),
}

export default aiFeedback