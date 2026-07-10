import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/yerevan/admin/ai-feedback'
*/
const indexa4d043f191a98b5d0f912b2c828ce03e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexa4d043f191a98b5d0f912b2c828ce03e.url(options),
    method: 'get',
})

indexa4d043f191a98b5d0f912b2c828ce03e.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/ai-feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/yerevan/admin/ai-feedback'
*/
indexa4d043f191a98b5d0f912b2c828ce03e.url = (options?: RouteQueryOptions) => {
    return indexa4d043f191a98b5d0f912b2c828ce03e.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/yerevan/admin/ai-feedback'
*/
indexa4d043f191a98b5d0f912b2c828ce03e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexa4d043f191a98b5d0f912b2c828ce03e.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/yerevan/admin/ai-feedback'
*/
indexa4d043f191a98b5d0f912b2c828ce03e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexa4d043f191a98b5d0f912b2c828ce03e.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/baku/admin/ai-feedback'
*/
const index4120fd36f38453ae9c9b1aa9bc4cdf8e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index4120fd36f38453ae9c9b1aa9bc4cdf8e.url(options),
    method: 'get',
})

index4120fd36f38453ae9c9b1aa9bc4cdf8e.definition = {
    methods: ["get","head"],
    url: '/baku/admin/ai-feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/baku/admin/ai-feedback'
*/
index4120fd36f38453ae9c9b1aa9bc4cdf8e.url = (options?: RouteQueryOptions) => {
    return index4120fd36f38453ae9c9b1aa9bc4cdf8e.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/baku/admin/ai-feedback'
*/
index4120fd36f38453ae9c9b1aa9bc4cdf8e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index4120fd36f38453ae9c9b1aa9bc4cdf8e.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/baku/admin/ai-feedback'
*/
index4120fd36f38453ae9c9b1aa9bc4cdf8e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index4120fd36f38453ae9c9b1aa9bc4cdf8e.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/tbilisi/admin/ai-feedback'
*/
const index145b2905f9eff2fef9d2e486046a6e33 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index145b2905f9eff2fef9d2e486046a6e33.url(options),
    method: 'get',
})

index145b2905f9eff2fef9d2e486046a6e33.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/ai-feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/tbilisi/admin/ai-feedback'
*/
index145b2905f9eff2fef9d2e486046a6e33.url = (options?: RouteQueryOptions) => {
    return index145b2905f9eff2fef9d2e486046a6e33.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/tbilisi/admin/ai-feedback'
*/
index145b2905f9eff2fef9d2e486046a6e33.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index145b2905f9eff2fef9d2e486046a6e33.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AiFeedbackController::index
* @see app/Http/Controllers/Admin/AiFeedbackController.php:20
* @route '/tbilisi/admin/ai-feedback'
*/
index145b2905f9eff2fef9d2e486046a6e33.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index145b2905f9eff2fef9d2e486046a6e33.url(options),
    method: 'head',
})

export const index = {
    '/yerevan/admin/ai-feedback': indexa4d043f191a98b5d0f912b2c828ce03e,
    '/baku/admin/ai-feedback': index4120fd36f38453ae9c9b1aa9bc4cdf8e,
    '/tbilisi/admin/ai-feedback': index145b2905f9eff2fef9d2e486046a6e33,
}

const AiFeedbackController = { index }

export default AiFeedbackController