import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
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
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/yerevan/admin/assistant'
*/
index95d0824a2a9cd02bdc3ebf4f32a90b95.url = (options?: RouteQueryOptions) => {
    return index95d0824a2a9cd02bdc3ebf4f32a90b95.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/yerevan/admin/assistant'
*/
index95d0824a2a9cd02bdc3ebf4f32a90b95.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index95d0824a2a9cd02bdc3ebf4f32a90b95.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/yerevan/admin/assistant'
*/
index95d0824a2a9cd02bdc3ebf4f32a90b95.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index95d0824a2a9cd02bdc3ebf4f32a90b95.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/yerevan/admin/assistant'
*/
const index95d0824a2a9cd02bdc3ebf4f32a90b95Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index95d0824a2a9cd02bdc3ebf4f32a90b95.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/yerevan/admin/assistant'
*/
index95d0824a2a9cd02bdc3ebf4f32a90b95Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index95d0824a2a9cd02bdc3ebf4f32a90b95.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
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
* @see app/Http/Controllers/Admin/AssistantController.php:22
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
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
index582b7e437676ca232891422d98c6f85c.url = (options?: RouteQueryOptions) => {
    return index582b7e437676ca232891422d98c6f85c.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
index582b7e437676ca232891422d98c6f85c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index582b7e437676ca232891422d98c6f85c.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
index582b7e437676ca232891422d98c6f85c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index582b7e437676ca232891422d98c6f85c.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
const index582b7e437676ca232891422d98c6f85cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index582b7e437676ca232891422d98c6f85c.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/baku/admin/assistant'
*/
index582b7e437676ca232891422d98c6f85cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index582b7e437676ca232891422d98c6f85c.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
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
* @see app/Http/Controllers/Admin/AssistantController.php:22
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
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/tbilisi/admin/assistant'
*/
index5e32ba440ba2d04330381ae215b137a7.url = (options?: RouteQueryOptions) => {
    return index5e32ba440ba2d04330381ae215b137a7.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/tbilisi/admin/assistant'
*/
index5e32ba440ba2d04330381ae215b137a7.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index5e32ba440ba2d04330381ae215b137a7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/tbilisi/admin/assistant'
*/
index5e32ba440ba2d04330381ae215b137a7.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index5e32ba440ba2d04330381ae215b137a7.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/tbilisi/admin/assistant'
*/
const index5e32ba440ba2d04330381ae215b137a7Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index5e32ba440ba2d04330381ae215b137a7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/tbilisi/admin/assistant'
*/
index5e32ba440ba2d04330381ae215b137a7Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index5e32ba440ba2d04330381ae215b137a7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
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
* @see app/Http/Controllers/Admin/AssistantController.php:22
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
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/global/admin/assistant'
*/
index7808950a53064d68031fe5cf427f74ec.url = (options?: RouteQueryOptions) => {
    return index7808950a53064d68031fe5cf427f74ec.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/global/admin/assistant'
*/
index7808950a53064d68031fe5cf427f74ec.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index7808950a53064d68031fe5cf427f74ec.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/global/admin/assistant'
*/
index7808950a53064d68031fe5cf427f74ec.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index7808950a53064d68031fe5cf427f74ec.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/global/admin/assistant'
*/
const index7808950a53064d68031fe5cf427f74ecForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7808950a53064d68031fe5cf427f74ec.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
* @route '/global/admin/assistant'
*/
index7808950a53064d68031fe5cf427f74ecForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7808950a53064d68031fe5cf427f74ec.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:22
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

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/yerevan/admin/assistant/history'
*/
const clearHistory82dec07432eee2055753e7b9a4d50fc5 = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearHistory82dec07432eee2055753e7b9a4d50fc5.url(options),
    method: 'delete',
})

clearHistory82dec07432eee2055753e7b9a4d50fc5.definition = {
    methods: ["delete"],
    url: '/yerevan/admin/assistant/history',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/yerevan/admin/assistant/history'
*/
clearHistory82dec07432eee2055753e7b9a4d50fc5.url = (options?: RouteQueryOptions) => {
    return clearHistory82dec07432eee2055753e7b9a4d50fc5.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/yerevan/admin/assistant/history'
*/
clearHistory82dec07432eee2055753e7b9a4d50fc5.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearHistory82dec07432eee2055753e7b9a4d50fc5.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/yerevan/admin/assistant/history'
*/
const clearHistory82dec07432eee2055753e7b9a4d50fc5Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearHistory82dec07432eee2055753e7b9a4d50fc5.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/yerevan/admin/assistant/history'
*/
clearHistory82dec07432eee2055753e7b9a4d50fc5Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearHistory82dec07432eee2055753e7b9a4d50fc5.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

clearHistory82dec07432eee2055753e7b9a4d50fc5.form = clearHistory82dec07432eee2055753e7b9a4d50fc5Form
/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
const clearHistoryc43b66c94a974919f6dc127040955e96 = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearHistoryc43b66c94a974919f6dc127040955e96.url(options),
    method: 'delete',
})

clearHistoryc43b66c94a974919f6dc127040955e96.definition = {
    methods: ["delete"],
    url: '/baku/admin/assistant/history',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
clearHistoryc43b66c94a974919f6dc127040955e96.url = (options?: RouteQueryOptions) => {
    return clearHistoryc43b66c94a974919f6dc127040955e96.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
clearHistoryc43b66c94a974919f6dc127040955e96.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearHistoryc43b66c94a974919f6dc127040955e96.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
const clearHistoryc43b66c94a974919f6dc127040955e96Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearHistoryc43b66c94a974919f6dc127040955e96.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
clearHistoryc43b66c94a974919f6dc127040955e96Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearHistoryc43b66c94a974919f6dc127040955e96.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

clearHistoryc43b66c94a974919f6dc127040955e96.form = clearHistoryc43b66c94a974919f6dc127040955e96Form
/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/tbilisi/admin/assistant/history'
*/
const clearHistory63a31d4f284399f1239e35d7442efc68 = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearHistory63a31d4f284399f1239e35d7442efc68.url(options),
    method: 'delete',
})

clearHistory63a31d4f284399f1239e35d7442efc68.definition = {
    methods: ["delete"],
    url: '/tbilisi/admin/assistant/history',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/tbilisi/admin/assistant/history'
*/
clearHistory63a31d4f284399f1239e35d7442efc68.url = (options?: RouteQueryOptions) => {
    return clearHistory63a31d4f284399f1239e35d7442efc68.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/tbilisi/admin/assistant/history'
*/
clearHistory63a31d4f284399f1239e35d7442efc68.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearHistory63a31d4f284399f1239e35d7442efc68.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/tbilisi/admin/assistant/history'
*/
const clearHistory63a31d4f284399f1239e35d7442efc68Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearHistory63a31d4f284399f1239e35d7442efc68.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/tbilisi/admin/assistant/history'
*/
clearHistory63a31d4f284399f1239e35d7442efc68Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearHistory63a31d4f284399f1239e35d7442efc68.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

clearHistory63a31d4f284399f1239e35d7442efc68.form = clearHistory63a31d4f284399f1239e35d7442efc68Form
/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/global/admin/assistant/history'
*/
const clearHistory6aaee98d8d43c51f2879f4e35b77357b = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearHistory6aaee98d8d43c51f2879f4e35b77357b.url(options),
    method: 'delete',
})

clearHistory6aaee98d8d43c51f2879f4e35b77357b.definition = {
    methods: ["delete"],
    url: '/global/admin/assistant/history',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/global/admin/assistant/history'
*/
clearHistory6aaee98d8d43c51f2879f4e35b77357b.url = (options?: RouteQueryOptions) => {
    return clearHistory6aaee98d8d43c51f2879f4e35b77357b.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/global/admin/assistant/history'
*/
clearHistory6aaee98d8d43c51f2879f4e35b77357b.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clearHistory6aaee98d8d43c51f2879f4e35b77357b.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/global/admin/assistant/history'
*/
const clearHistory6aaee98d8d43c51f2879f4e35b77357bForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearHistory6aaee98d8d43c51f2879f4e35b77357b.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clearHistory
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/global/admin/assistant/history'
*/
clearHistory6aaee98d8d43c51f2879f4e35b77357bForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearHistory6aaee98d8d43c51f2879f4e35b77357b.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

clearHistory6aaee98d8d43c51f2879f4e35b77357b.form = clearHistory6aaee98d8d43c51f2879f4e35b77357bForm

export const clearHistory = {
    '/yerevan/admin/assistant/history': clearHistory82dec07432eee2055753e7b9a4d50fc5,
    '/baku/admin/assistant/history': clearHistoryc43b66c94a974919f6dc127040955e96,
    '/tbilisi/admin/assistant/history': clearHistory63a31d4f284399f1239e35d7442efc68,
    '/global/admin/assistant/history': clearHistory6aaee98d8d43c51f2879f4e35b77357b,
}

const AssistantController = { index, clearHistory }

export default AssistantController