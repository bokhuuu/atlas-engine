import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
const index95d0824a2a9cd02bdc3ebf4f32a90b95 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index95d0824a2a9cd02bdc3ebf4f32a90b95.url(options),
    method: 'get',
})

index95d0824a2a9cd02bdc3ebf4f32a90b95.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
index95d0824a2a9cd02bdc3ebf4f32a90b95.url = (options?: RouteQueryOptions) => {
    return index95d0824a2a9cd02bdc3ebf4f32a90b95.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
index95d0824a2a9cd02bdc3ebf4f32a90b95.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index95d0824a2a9cd02bdc3ebf4f32a90b95.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
index95d0824a2a9cd02bdc3ebf4f32a90b95.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index95d0824a2a9cd02bdc3ebf4f32a90b95.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
const index95d0824a2a9cd02bdc3ebf4f32a90b95Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index95d0824a2a9cd02bdc3ebf4f32a90b95.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
index95d0824a2a9cd02bdc3ebf4f32a90b95Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index95d0824a2a9cd02bdc3ebf4f32a90b95.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/yerevan/admin/assistant'
*/
index95d0824a2a9cd02bdc3ebf4f32a90b95Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index95d0824a2a9cd02bdc3ebf4f32a90b95.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index95d0824a2a9cd02bdc3ebf4f32a90b95.form = index95d0824a2a9cd02bdc3ebf4f32a90b95Form
/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
const index582b7e437676ca232891422d98c6f85c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index582b7e437676ca232891422d98c6f85c.url(options),
    method: 'get',
})

index582b7e437676ca232891422d98c6f85c.definition = {
    methods: ["get","head"],
    url: '/baku/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
index582b7e437676ca232891422d98c6f85c.url = (options?: RouteQueryOptions) => {
    return index582b7e437676ca232891422d98c6f85c.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
index582b7e437676ca232891422d98c6f85c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index582b7e437676ca232891422d98c6f85c.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
index582b7e437676ca232891422d98c6f85c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index582b7e437676ca232891422d98c6f85c.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
const index582b7e437676ca232891422d98c6f85cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index582b7e437676ca232891422d98c6f85c.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
index582b7e437676ca232891422d98c6f85cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index582b7e437676ca232891422d98c6f85c.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/baku/admin/assistant'
*/
index582b7e437676ca232891422d98c6f85cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index582b7e437676ca232891422d98c6f85c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index582b7e437676ca232891422d98c6f85c.form = index582b7e437676ca232891422d98c6f85cForm
/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
const index5e32ba440ba2d04330381ae215b137a7 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index5e32ba440ba2d04330381ae215b137a7.url(options),
    method: 'get',
})

index5e32ba440ba2d04330381ae215b137a7.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
index5e32ba440ba2d04330381ae215b137a7.url = (options?: RouteQueryOptions) => {
    return index5e32ba440ba2d04330381ae215b137a7.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
index5e32ba440ba2d04330381ae215b137a7.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index5e32ba440ba2d04330381ae215b137a7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
index5e32ba440ba2d04330381ae215b137a7.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index5e32ba440ba2d04330381ae215b137a7.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
const index5e32ba440ba2d04330381ae215b137a7Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index5e32ba440ba2d04330381ae215b137a7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
index5e32ba440ba2d04330381ae215b137a7Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index5e32ba440ba2d04330381ae215b137a7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/tbilisi/admin/assistant'
*/
index5e32ba440ba2d04330381ae215b137a7Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index5e32ba440ba2d04330381ae215b137a7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index5e32ba440ba2d04330381ae215b137a7.form = index5e32ba440ba2d04330381ae215b137a7Form
/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
const index7808950a53064d68031fe5cf427f74ec = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index7808950a53064d68031fe5cf427f74ec.url(options),
    method: 'get',
})

index7808950a53064d68031fe5cf427f74ec.definition = {
    methods: ["get","head"],
    url: '/global/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
index7808950a53064d68031fe5cf427f74ec.url = (options?: RouteQueryOptions) => {
    return index7808950a53064d68031fe5cf427f74ec.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
index7808950a53064d68031fe5cf427f74ec.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index7808950a53064d68031fe5cf427f74ec.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
index7808950a53064d68031fe5cf427f74ec.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index7808950a53064d68031fe5cf427f74ec.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
const index7808950a53064d68031fe5cf427f74ecForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7808950a53064d68031fe5cf427f74ec.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
index7808950a53064d68031fe5cf427f74ecForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7808950a53064d68031fe5cf427f74ec.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/global/admin/assistant'
*/
index7808950a53064d68031fe5cf427f74ecForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7808950a53064d68031fe5cf427f74ec.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index7808950a53064d68031fe5cf427f74ec.form = index7808950a53064d68031fe5cf427f74ecForm

export const index = {
    '/yerevan/admin/assistant': index95d0824a2a9cd02bdc3ebf4f32a90b95,
    '/baku/admin/assistant': index582b7e437676ca232891422d98c6f85c,
    '/tbilisi/admin/assistant': index5e32ba440ba2d04330381ae215b137a7,
    '/global/admin/assistant': index7808950a53064d68031fe5cf427f74ec,
}

const AssistantController = { index }

export default AssistantController