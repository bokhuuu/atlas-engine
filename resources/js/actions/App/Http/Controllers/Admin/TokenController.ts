import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/yerevan/admin/tokens'
*/
const index8e86af0ad7fa91f3531ca351a2c1ed6b = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index8e86af0ad7fa91f3531ca351a2c1ed6b.url(options),
    method: 'get',
})

index8e86af0ad7fa91f3531ca351a2c1ed6b.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/tokens',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/yerevan/admin/tokens'
*/
index8e86af0ad7fa91f3531ca351a2c1ed6b.url = (options?: RouteQueryOptions) => {
    return index8e86af0ad7fa91f3531ca351a2c1ed6b.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/yerevan/admin/tokens'
*/
index8e86af0ad7fa91f3531ca351a2c1ed6b.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index8e86af0ad7fa91f3531ca351a2c1ed6b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/yerevan/admin/tokens'
*/
index8e86af0ad7fa91f3531ca351a2c1ed6b.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index8e86af0ad7fa91f3531ca351a2c1ed6b.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/yerevan/admin/tokens'
*/
const index8e86af0ad7fa91f3531ca351a2c1ed6bForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index8e86af0ad7fa91f3531ca351a2c1ed6b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/yerevan/admin/tokens'
*/
index8e86af0ad7fa91f3531ca351a2c1ed6bForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index8e86af0ad7fa91f3531ca351a2c1ed6b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/yerevan/admin/tokens'
*/
index8e86af0ad7fa91f3531ca351a2c1ed6bForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index8e86af0ad7fa91f3531ca351a2c1ed6b.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index8e86af0ad7fa91f3531ca351a2c1ed6b.form = index8e86af0ad7fa91f3531ca351a2c1ed6bForm
/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/baku/admin/tokens'
*/
const index39b14207402ffad04ee36d6cfbd54fd3 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index39b14207402ffad04ee36d6cfbd54fd3.url(options),
    method: 'get',
})

index39b14207402ffad04ee36d6cfbd54fd3.definition = {
    methods: ["get","head"],
    url: '/baku/admin/tokens',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/baku/admin/tokens'
*/
index39b14207402ffad04ee36d6cfbd54fd3.url = (options?: RouteQueryOptions) => {
    return index39b14207402ffad04ee36d6cfbd54fd3.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/baku/admin/tokens'
*/
index39b14207402ffad04ee36d6cfbd54fd3.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index39b14207402ffad04ee36d6cfbd54fd3.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/baku/admin/tokens'
*/
index39b14207402ffad04ee36d6cfbd54fd3.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index39b14207402ffad04ee36d6cfbd54fd3.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/baku/admin/tokens'
*/
const index39b14207402ffad04ee36d6cfbd54fd3Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index39b14207402ffad04ee36d6cfbd54fd3.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/baku/admin/tokens'
*/
index39b14207402ffad04ee36d6cfbd54fd3Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index39b14207402ffad04ee36d6cfbd54fd3.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/baku/admin/tokens'
*/
index39b14207402ffad04ee36d6cfbd54fd3Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index39b14207402ffad04ee36d6cfbd54fd3.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index39b14207402ffad04ee36d6cfbd54fd3.form = index39b14207402ffad04ee36d6cfbd54fd3Form
/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/tbilisi/admin/tokens'
*/
const index387f594507f2bd43468a308c6505c4f3 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index387f594507f2bd43468a308c6505c4f3.url(options),
    method: 'get',
})

index387f594507f2bd43468a308c6505c4f3.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/tokens',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/tbilisi/admin/tokens'
*/
index387f594507f2bd43468a308c6505c4f3.url = (options?: RouteQueryOptions) => {
    return index387f594507f2bd43468a308c6505c4f3.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/tbilisi/admin/tokens'
*/
index387f594507f2bd43468a308c6505c4f3.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index387f594507f2bd43468a308c6505c4f3.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/tbilisi/admin/tokens'
*/
index387f594507f2bd43468a308c6505c4f3.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index387f594507f2bd43468a308c6505c4f3.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/tbilisi/admin/tokens'
*/
const index387f594507f2bd43468a308c6505c4f3Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index387f594507f2bd43468a308c6505c4f3.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/tbilisi/admin/tokens'
*/
index387f594507f2bd43468a308c6505c4f3Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index387f594507f2bd43468a308c6505c4f3.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::index
* @see app/Http/Controllers/Admin/TokenController.php:28
* @route '/tbilisi/admin/tokens'
*/
index387f594507f2bd43468a308c6505c4f3Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index387f594507f2bd43468a308c6505c4f3.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index387f594507f2bd43468a308c6505c4f3.form = index387f594507f2bd43468a308c6505c4f3Form

export const index = {
    '/yerevan/admin/tokens': index8e86af0ad7fa91f3531ca351a2c1ed6b,
    '/baku/admin/tokens': index39b14207402ffad04ee36d6cfbd54fd3,
    '/tbilisi/admin/tokens': index387f594507f2bd43468a308c6505c4f3,
}

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/yerevan/admin/tokens'
*/
const store8e86af0ad7fa91f3531ca351a2c1ed6b = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store8e86af0ad7fa91f3531ca351a2c1ed6b.url(options),
    method: 'post',
})

store8e86af0ad7fa91f3531ca351a2c1ed6b.definition = {
    methods: ["post"],
    url: '/yerevan/admin/tokens',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/yerevan/admin/tokens'
*/
store8e86af0ad7fa91f3531ca351a2c1ed6b.url = (options?: RouteQueryOptions) => {
    return store8e86af0ad7fa91f3531ca351a2c1ed6b.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/yerevan/admin/tokens'
*/
store8e86af0ad7fa91f3531ca351a2c1ed6b.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store8e86af0ad7fa91f3531ca351a2c1ed6b.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/yerevan/admin/tokens'
*/
const store8e86af0ad7fa91f3531ca351a2c1ed6bForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store8e86af0ad7fa91f3531ca351a2c1ed6b.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/yerevan/admin/tokens'
*/
store8e86af0ad7fa91f3531ca351a2c1ed6bForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store8e86af0ad7fa91f3531ca351a2c1ed6b.url(options),
    method: 'post',
})

store8e86af0ad7fa91f3531ca351a2c1ed6b.form = store8e86af0ad7fa91f3531ca351a2c1ed6bForm
/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/baku/admin/tokens'
*/
const store39b14207402ffad04ee36d6cfbd54fd3 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store39b14207402ffad04ee36d6cfbd54fd3.url(options),
    method: 'post',
})

store39b14207402ffad04ee36d6cfbd54fd3.definition = {
    methods: ["post"],
    url: '/baku/admin/tokens',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/baku/admin/tokens'
*/
store39b14207402ffad04ee36d6cfbd54fd3.url = (options?: RouteQueryOptions) => {
    return store39b14207402ffad04ee36d6cfbd54fd3.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/baku/admin/tokens'
*/
store39b14207402ffad04ee36d6cfbd54fd3.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store39b14207402ffad04ee36d6cfbd54fd3.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/baku/admin/tokens'
*/
const store39b14207402ffad04ee36d6cfbd54fd3Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store39b14207402ffad04ee36d6cfbd54fd3.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/baku/admin/tokens'
*/
store39b14207402ffad04ee36d6cfbd54fd3Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store39b14207402ffad04ee36d6cfbd54fd3.url(options),
    method: 'post',
})

store39b14207402ffad04ee36d6cfbd54fd3.form = store39b14207402ffad04ee36d6cfbd54fd3Form
/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/tbilisi/admin/tokens'
*/
const store387f594507f2bd43468a308c6505c4f3 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store387f594507f2bd43468a308c6505c4f3.url(options),
    method: 'post',
})

store387f594507f2bd43468a308c6505c4f3.definition = {
    methods: ["post"],
    url: '/tbilisi/admin/tokens',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/tbilisi/admin/tokens'
*/
store387f594507f2bd43468a308c6505c4f3.url = (options?: RouteQueryOptions) => {
    return store387f594507f2bd43468a308c6505c4f3.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/tbilisi/admin/tokens'
*/
store387f594507f2bd43468a308c6505c4f3.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store387f594507f2bd43468a308c6505c4f3.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/tbilisi/admin/tokens'
*/
const store387f594507f2bd43468a308c6505c4f3Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store387f594507f2bd43468a308c6505c4f3.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::store
* @see app/Http/Controllers/Admin/TokenController.php:53
* @route '/tbilisi/admin/tokens'
*/
store387f594507f2bd43468a308c6505c4f3Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store387f594507f2bd43468a308c6505c4f3.url(options),
    method: 'post',
})

store387f594507f2bd43468a308c6505c4f3.form = store387f594507f2bd43468a308c6505c4f3Form

export const store = {
    '/yerevan/admin/tokens': store8e86af0ad7fa91f3531ca351a2c1ed6b,
    '/baku/admin/tokens': store39b14207402ffad04ee36d6cfbd54fd3,
    '/tbilisi/admin/tokens': store387f594507f2bd43468a308c6505c4f3,
}

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/yerevan/admin/tokens/{id}'
*/
const destroy962711e9e568709862fa7612055b1b8e = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy962711e9e568709862fa7612055b1b8e.url(args, options),
    method: 'delete',
})

destroy962711e9e568709862fa7612055b1b8e.definition = {
    methods: ["delete"],
    url: '/yerevan/admin/tokens/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/yerevan/admin/tokens/{id}'
*/
destroy962711e9e568709862fa7612055b1b8e.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return destroy962711e9e568709862fa7612055b1b8e.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/yerevan/admin/tokens/{id}'
*/
destroy962711e9e568709862fa7612055b1b8e.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy962711e9e568709862fa7612055b1b8e.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/yerevan/admin/tokens/{id}'
*/
const destroy962711e9e568709862fa7612055b1b8eForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy962711e9e568709862fa7612055b1b8e.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/yerevan/admin/tokens/{id}'
*/
destroy962711e9e568709862fa7612055b1b8eForm.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy962711e9e568709862fa7612055b1b8e.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy962711e9e568709862fa7612055b1b8e.form = destroy962711e9e568709862fa7612055b1b8eForm
/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/baku/admin/tokens/{id}'
*/
const destroy7fcc5048ebc884b2165cce485dbff0c9 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy7fcc5048ebc884b2165cce485dbff0c9.url(args, options),
    method: 'delete',
})

destroy7fcc5048ebc884b2165cce485dbff0c9.definition = {
    methods: ["delete"],
    url: '/baku/admin/tokens/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/baku/admin/tokens/{id}'
*/
destroy7fcc5048ebc884b2165cce485dbff0c9.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return destroy7fcc5048ebc884b2165cce485dbff0c9.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/baku/admin/tokens/{id}'
*/
destroy7fcc5048ebc884b2165cce485dbff0c9.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy7fcc5048ebc884b2165cce485dbff0c9.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/baku/admin/tokens/{id}'
*/
const destroy7fcc5048ebc884b2165cce485dbff0c9Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy7fcc5048ebc884b2165cce485dbff0c9.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/baku/admin/tokens/{id}'
*/
destroy7fcc5048ebc884b2165cce485dbff0c9Form.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy7fcc5048ebc884b2165cce485dbff0c9.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy7fcc5048ebc884b2165cce485dbff0c9.form = destroy7fcc5048ebc884b2165cce485dbff0c9Form
/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/tbilisi/admin/tokens/{id}'
*/
const destroy27cd3868f1164eae53b2f6b2b927cd17 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy27cd3868f1164eae53b2f6b2b927cd17.url(args, options),
    method: 'delete',
})

destroy27cd3868f1164eae53b2f6b2b927cd17.definition = {
    methods: ["delete"],
    url: '/tbilisi/admin/tokens/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/tbilisi/admin/tokens/{id}'
*/
destroy27cd3868f1164eae53b2f6b2b927cd17.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return destroy27cd3868f1164eae53b2f6b2b927cd17.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/tbilisi/admin/tokens/{id}'
*/
destroy27cd3868f1164eae53b2f6b2b927cd17.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy27cd3868f1164eae53b2f6b2b927cd17.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/tbilisi/admin/tokens/{id}'
*/
const destroy27cd3868f1164eae53b2f6b2b927cd17Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy27cd3868f1164eae53b2f6b2b927cd17.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TokenController::destroy
* @see app/Http/Controllers/Admin/TokenController.php:68
* @route '/tbilisi/admin/tokens/{id}'
*/
destroy27cd3868f1164eae53b2f6b2b927cd17Form.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy27cd3868f1164eae53b2f6b2b927cd17.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy27cd3868f1164eae53b2f6b2b927cd17.form = destroy27cd3868f1164eae53b2f6b2b927cd17Form

export const destroy = {
    '/yerevan/admin/tokens/{id}': destroy962711e9e568709862fa7612055b1b8e,
    '/baku/admin/tokens/{id}': destroy7fcc5048ebc884b2165cce485dbff0c9,
    '/tbilisi/admin/tokens/{id}': destroy27cd3868f1164eae53b2f6b2b927cd17,
}

const TokenController = { index, store, destroy }

export default TokenController