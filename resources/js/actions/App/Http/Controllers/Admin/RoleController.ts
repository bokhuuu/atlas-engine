import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/yerevan/admin/roles'
*/
const indexe717ecb334cf9bd126dac5f0c706bd24 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexe717ecb334cf9bd126dac5f0c706bd24.url(options),
    method: 'get',
})

indexe717ecb334cf9bd126dac5f0c706bd24.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/yerevan/admin/roles'
*/
indexe717ecb334cf9bd126dac5f0c706bd24.url = (options?: RouteQueryOptions) => {
    return indexe717ecb334cf9bd126dac5f0c706bd24.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/yerevan/admin/roles'
*/
indexe717ecb334cf9bd126dac5f0c706bd24.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexe717ecb334cf9bd126dac5f0c706bd24.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/yerevan/admin/roles'
*/
indexe717ecb334cf9bd126dac5f0c706bd24.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexe717ecb334cf9bd126dac5f0c706bd24.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/yerevan/admin/roles'
*/
const indexe717ecb334cf9bd126dac5f0c706bd24Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexe717ecb334cf9bd126dac5f0c706bd24.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/yerevan/admin/roles'
*/
indexe717ecb334cf9bd126dac5f0c706bd24Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexe717ecb334cf9bd126dac5f0c706bd24.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/yerevan/admin/roles'
*/
indexe717ecb334cf9bd126dac5f0c706bd24Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexe717ecb334cf9bd126dac5f0c706bd24.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexe717ecb334cf9bd126dac5f0c706bd24.form = indexe717ecb334cf9bd126dac5f0c706bd24Form
/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/baku/admin/roles'
*/
const indexffd66ee722c4d74590a38dea4bdb0386 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexffd66ee722c4d74590a38dea4bdb0386.url(options),
    method: 'get',
})

indexffd66ee722c4d74590a38dea4bdb0386.definition = {
    methods: ["get","head"],
    url: '/baku/admin/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/baku/admin/roles'
*/
indexffd66ee722c4d74590a38dea4bdb0386.url = (options?: RouteQueryOptions) => {
    return indexffd66ee722c4d74590a38dea4bdb0386.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/baku/admin/roles'
*/
indexffd66ee722c4d74590a38dea4bdb0386.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexffd66ee722c4d74590a38dea4bdb0386.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/baku/admin/roles'
*/
indexffd66ee722c4d74590a38dea4bdb0386.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexffd66ee722c4d74590a38dea4bdb0386.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/baku/admin/roles'
*/
const indexffd66ee722c4d74590a38dea4bdb0386Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexffd66ee722c4d74590a38dea4bdb0386.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/baku/admin/roles'
*/
indexffd66ee722c4d74590a38dea4bdb0386Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexffd66ee722c4d74590a38dea4bdb0386.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/baku/admin/roles'
*/
indexffd66ee722c4d74590a38dea4bdb0386Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexffd66ee722c4d74590a38dea4bdb0386.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexffd66ee722c4d74590a38dea4bdb0386.form = indexffd66ee722c4d74590a38dea4bdb0386Form
/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/tbilisi/admin/roles'
*/
const index7dc17195194a590a34b4fa3c3ba1c0aa = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index7dc17195194a590a34b4fa3c3ba1c0aa.url(options),
    method: 'get',
})

index7dc17195194a590a34b4fa3c3ba1c0aa.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/tbilisi/admin/roles'
*/
index7dc17195194a590a34b4fa3c3ba1c0aa.url = (options?: RouteQueryOptions) => {
    return index7dc17195194a590a34b4fa3c3ba1c0aa.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/tbilisi/admin/roles'
*/
index7dc17195194a590a34b4fa3c3ba1c0aa.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index7dc17195194a590a34b4fa3c3ba1c0aa.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/tbilisi/admin/roles'
*/
index7dc17195194a590a34b4fa3c3ba1c0aa.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index7dc17195194a590a34b4fa3c3ba1c0aa.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/tbilisi/admin/roles'
*/
const index7dc17195194a590a34b4fa3c3ba1c0aaForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7dc17195194a590a34b4fa3c3ba1c0aa.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/tbilisi/admin/roles'
*/
index7dc17195194a590a34b4fa3c3ba1c0aaForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7dc17195194a590a34b4fa3c3ba1c0aa.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::index
* @see app/Http/Controllers/Admin/RoleController.php:29
* @route '/tbilisi/admin/roles'
*/
index7dc17195194a590a34b4fa3c3ba1c0aaForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index7dc17195194a590a34b4fa3c3ba1c0aa.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index7dc17195194a590a34b4fa3c3ba1c0aa.form = index7dc17195194a590a34b4fa3c3ba1c0aaForm

export const index = {
    '/yerevan/admin/roles': indexe717ecb334cf9bd126dac5f0c706bd24,
    '/baku/admin/roles': indexffd66ee722c4d74590a38dea4bdb0386,
    '/tbilisi/admin/roles': index7dc17195194a590a34b4fa3c3ba1c0aa,
}

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/yerevan/admin/roles/{role}'
*/
const update4ba0695dc62fd7246ba4b77f09752b0f = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update4ba0695dc62fd7246ba4b77f09752b0f.url(args, options),
    method: 'put',
})

update4ba0695dc62fd7246ba4b77f09752b0f.definition = {
    methods: ["put"],
    url: '/yerevan/admin/roles/{role}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/yerevan/admin/roles/{role}'
*/
update4ba0695dc62fd7246ba4b77f09752b0f.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { role: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            role: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        role: typeof args.role === 'object'
        ? args.role.id
        : args.role,
    }

    return update4ba0695dc62fd7246ba4b77f09752b0f.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/yerevan/admin/roles/{role}'
*/
update4ba0695dc62fd7246ba4b77f09752b0f.put = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update4ba0695dc62fd7246ba4b77f09752b0f.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/yerevan/admin/roles/{role}'
*/
const update4ba0695dc62fd7246ba4b77f09752b0fForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update4ba0695dc62fd7246ba4b77f09752b0f.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/yerevan/admin/roles/{role}'
*/
update4ba0695dc62fd7246ba4b77f09752b0fForm.put = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update4ba0695dc62fd7246ba4b77f09752b0f.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update4ba0695dc62fd7246ba4b77f09752b0f.form = update4ba0695dc62fd7246ba4b77f09752b0fForm
/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/baku/admin/roles/{role}'
*/
const update304fefe088ada71e8522c2488662f9fe = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update304fefe088ada71e8522c2488662f9fe.url(args, options),
    method: 'put',
})

update304fefe088ada71e8522c2488662f9fe.definition = {
    methods: ["put"],
    url: '/baku/admin/roles/{role}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/baku/admin/roles/{role}'
*/
update304fefe088ada71e8522c2488662f9fe.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { role: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            role: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        role: typeof args.role === 'object'
        ? args.role.id
        : args.role,
    }

    return update304fefe088ada71e8522c2488662f9fe.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/baku/admin/roles/{role}'
*/
update304fefe088ada71e8522c2488662f9fe.put = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update304fefe088ada71e8522c2488662f9fe.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/baku/admin/roles/{role}'
*/
const update304fefe088ada71e8522c2488662f9feForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update304fefe088ada71e8522c2488662f9fe.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/baku/admin/roles/{role}'
*/
update304fefe088ada71e8522c2488662f9feForm.put = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update304fefe088ada71e8522c2488662f9fe.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update304fefe088ada71e8522c2488662f9fe.form = update304fefe088ada71e8522c2488662f9feForm
/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/tbilisi/admin/roles/{role}'
*/
const updatece907fe1da7d2562b726f04583bdb614 = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updatece907fe1da7d2562b726f04583bdb614.url(args, options),
    method: 'put',
})

updatece907fe1da7d2562b726f04583bdb614.definition = {
    methods: ["put"],
    url: '/tbilisi/admin/roles/{role}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/tbilisi/admin/roles/{role}'
*/
updatece907fe1da7d2562b726f04583bdb614.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { role: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            role: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        role: typeof args.role === 'object'
        ? args.role.id
        : args.role,
    }

    return updatece907fe1da7d2562b726f04583bdb614.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/tbilisi/admin/roles/{role}'
*/
updatece907fe1da7d2562b726f04583bdb614.put = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updatece907fe1da7d2562b726f04583bdb614.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/tbilisi/admin/roles/{role}'
*/
const updatece907fe1da7d2562b726f04583bdb614Form = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updatece907fe1da7d2562b726f04583bdb614.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\RoleController::update
* @see app/Http/Controllers/Admin/RoleController.php:46
* @route '/tbilisi/admin/roles/{role}'
*/
updatece907fe1da7d2562b726f04583bdb614Form.put = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updatece907fe1da7d2562b726f04583bdb614.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

updatece907fe1da7d2562b726f04583bdb614.form = updatece907fe1da7d2562b726f04583bdb614Form

export const update = {
    '/yerevan/admin/roles/{role}': update4ba0695dc62fd7246ba4b77f09752b0f,
    '/baku/admin/roles/{role}': update304fefe088ada71e8522c2488662f9fe,
    '/tbilisi/admin/roles/{role}': updatece907fe1da7d2562b726f04583bdb614,
}

const RoleController = { index, update }

export default RoleController