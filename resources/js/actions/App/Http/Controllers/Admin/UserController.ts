import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/yerevan/admin/users'
*/
const indexe38f12b144573eb0f8822cbb74bdf665 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexe38f12b144573eb0f8822cbb74bdf665.url(options),
    method: 'get',
})

indexe38f12b144573eb0f8822cbb74bdf665.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/yerevan/admin/users'
*/
indexe38f12b144573eb0f8822cbb74bdf665.url = (options?: RouteQueryOptions) => {
    return indexe38f12b144573eb0f8822cbb74bdf665.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/yerevan/admin/users'
*/
indexe38f12b144573eb0f8822cbb74bdf665.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexe38f12b144573eb0f8822cbb74bdf665.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/yerevan/admin/users'
*/
indexe38f12b144573eb0f8822cbb74bdf665.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexe38f12b144573eb0f8822cbb74bdf665.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/baku/admin/users'
*/
const index761de714b18569c72c4d5aae740322b7 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index761de714b18569c72c4d5aae740322b7.url(options),
    method: 'get',
})

index761de714b18569c72c4d5aae740322b7.definition = {
    methods: ["get","head"],
    url: '/baku/admin/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/baku/admin/users'
*/
index761de714b18569c72c4d5aae740322b7.url = (options?: RouteQueryOptions) => {
    return index761de714b18569c72c4d5aae740322b7.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/baku/admin/users'
*/
index761de714b18569c72c4d5aae740322b7.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index761de714b18569c72c4d5aae740322b7.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/baku/admin/users'
*/
index761de714b18569c72c4d5aae740322b7.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index761de714b18569c72c4d5aae740322b7.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/tbilisi/admin/users'
*/
const index1b67194e790cc5a7d0e08a7564efbfb4 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1b67194e790cc5a7d0e08a7564efbfb4.url(options),
    method: 'get',
})

index1b67194e790cc5a7d0e08a7564efbfb4.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/tbilisi/admin/users'
*/
index1b67194e790cc5a7d0e08a7564efbfb4.url = (options?: RouteQueryOptions) => {
    return index1b67194e790cc5a7d0e08a7564efbfb4.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/tbilisi/admin/users'
*/
index1b67194e790cc5a7d0e08a7564efbfb4.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1b67194e790cc5a7d0e08a7564efbfb4.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:28
* @route '/tbilisi/admin/users'
*/
index1b67194e790cc5a7d0e08a7564efbfb4.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index1b67194e790cc5a7d0e08a7564efbfb4.url(options),
    method: 'head',
})

export const index = {
    '/yerevan/admin/users': indexe38f12b144573eb0f8822cbb74bdf665,
    '/baku/admin/users': index761de714b18569c72c4d5aae740322b7,
    '/tbilisi/admin/users': index1b67194e790cc5a7d0e08a7564efbfb4,
}

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/yerevan/admin/users/create'
*/
const create144385697db0cb43b4a2519ab74955e4 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create144385697db0cb43b4a2519ab74955e4.url(options),
    method: 'get',
})

create144385697db0cb43b4a2519ab74955e4.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/users/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/yerevan/admin/users/create'
*/
create144385697db0cb43b4a2519ab74955e4.url = (options?: RouteQueryOptions) => {
    return create144385697db0cb43b4a2519ab74955e4.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/yerevan/admin/users/create'
*/
create144385697db0cb43b4a2519ab74955e4.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create144385697db0cb43b4a2519ab74955e4.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/yerevan/admin/users/create'
*/
create144385697db0cb43b4a2519ab74955e4.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create144385697db0cb43b4a2519ab74955e4.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/baku/admin/users/create'
*/
const create0b8244a7c83672186963dd86b41e7b72 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create0b8244a7c83672186963dd86b41e7b72.url(options),
    method: 'get',
})

create0b8244a7c83672186963dd86b41e7b72.definition = {
    methods: ["get","head"],
    url: '/baku/admin/users/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/baku/admin/users/create'
*/
create0b8244a7c83672186963dd86b41e7b72.url = (options?: RouteQueryOptions) => {
    return create0b8244a7c83672186963dd86b41e7b72.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/baku/admin/users/create'
*/
create0b8244a7c83672186963dd86b41e7b72.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create0b8244a7c83672186963dd86b41e7b72.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/baku/admin/users/create'
*/
create0b8244a7c83672186963dd86b41e7b72.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create0b8244a7c83672186963dd86b41e7b72.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/tbilisi/admin/users/create'
*/
const create745a220651b0f528f324a29b84b495c5 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create745a220651b0f528f324a29b84b495c5.url(options),
    method: 'get',
})

create745a220651b0f528f324a29b84b495c5.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/users/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/tbilisi/admin/users/create'
*/
create745a220651b0f528f324a29b84b495c5.url = (options?: RouteQueryOptions) => {
    return create745a220651b0f528f324a29b84b495c5.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/tbilisi/admin/users/create'
*/
create745a220651b0f528f324a29b84b495c5.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create745a220651b0f528f324a29b84b495c5.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:44
* @route '/tbilisi/admin/users/create'
*/
create745a220651b0f528f324a29b84b495c5.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create745a220651b0f528f324a29b84b495c5.url(options),
    method: 'head',
})

export const create = {
    '/yerevan/admin/users/create': create144385697db0cb43b4a2519ab74955e4,
    '/baku/admin/users/create': create0b8244a7c83672186963dd86b41e7b72,
    '/tbilisi/admin/users/create': create745a220651b0f528f324a29b84b495c5,
}

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/yerevan/admin/users/trashed'
*/
const trashed7344a5f648fe8c7e4c38cdd4ffe02740 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trashed7344a5f648fe8c7e4c38cdd4ffe02740.url(options),
    method: 'get',
})

trashed7344a5f648fe8c7e4c38cdd4ffe02740.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/users/trashed',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/yerevan/admin/users/trashed'
*/
trashed7344a5f648fe8c7e4c38cdd4ffe02740.url = (options?: RouteQueryOptions) => {
    return trashed7344a5f648fe8c7e4c38cdd4ffe02740.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/yerevan/admin/users/trashed'
*/
trashed7344a5f648fe8c7e4c38cdd4ffe02740.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trashed7344a5f648fe8c7e4c38cdd4ffe02740.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/yerevan/admin/users/trashed'
*/
trashed7344a5f648fe8c7e4c38cdd4ffe02740.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: trashed7344a5f648fe8c7e4c38cdd4ffe02740.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/baku/admin/users/trashed'
*/
const trashed833cd300f3827c1d3824d1568ea1e083 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trashed833cd300f3827c1d3824d1568ea1e083.url(options),
    method: 'get',
})

trashed833cd300f3827c1d3824d1568ea1e083.definition = {
    methods: ["get","head"],
    url: '/baku/admin/users/trashed',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/baku/admin/users/trashed'
*/
trashed833cd300f3827c1d3824d1568ea1e083.url = (options?: RouteQueryOptions) => {
    return trashed833cd300f3827c1d3824d1568ea1e083.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/baku/admin/users/trashed'
*/
trashed833cd300f3827c1d3824d1568ea1e083.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trashed833cd300f3827c1d3824d1568ea1e083.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/baku/admin/users/trashed'
*/
trashed833cd300f3827c1d3824d1568ea1e083.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: trashed833cd300f3827c1d3824d1568ea1e083.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/tbilisi/admin/users/trashed'
*/
const trashed4c759b2684ed20053ad60facd9939b9f = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trashed4c759b2684ed20053ad60facd9939b9f.url(options),
    method: 'get',
})

trashed4c759b2684ed20053ad60facd9939b9f.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/users/trashed',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/tbilisi/admin/users/trashed'
*/
trashed4c759b2684ed20053ad60facd9939b9f.url = (options?: RouteQueryOptions) => {
    return trashed4c759b2684ed20053ad60facd9939b9f.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/tbilisi/admin/users/trashed'
*/
trashed4c759b2684ed20053ad60facd9939b9f.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trashed4c759b2684ed20053ad60facd9939b9f.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::trashed
* @see app/Http/Controllers/Admin/UserController.php:66
* @route '/tbilisi/admin/users/trashed'
*/
trashed4c759b2684ed20053ad60facd9939b9f.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: trashed4c759b2684ed20053ad60facd9939b9f.url(options),
    method: 'head',
})

export const trashed = {
    '/yerevan/admin/users/trashed': trashed7344a5f648fe8c7e4c38cdd4ffe02740,
    '/baku/admin/users/trashed': trashed833cd300f3827c1d3824d1568ea1e083,
    '/tbilisi/admin/users/trashed': trashed4c759b2684ed20053ad60facd9939b9f,
}

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:75
* @route '/yerevan/admin/users'
*/
const storee38f12b144573eb0f8822cbb74bdf665 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storee38f12b144573eb0f8822cbb74bdf665.url(options),
    method: 'post',
})

storee38f12b144573eb0f8822cbb74bdf665.definition = {
    methods: ["post"],
    url: '/yerevan/admin/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:75
* @route '/yerevan/admin/users'
*/
storee38f12b144573eb0f8822cbb74bdf665.url = (options?: RouteQueryOptions) => {
    return storee38f12b144573eb0f8822cbb74bdf665.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:75
* @route '/yerevan/admin/users'
*/
storee38f12b144573eb0f8822cbb74bdf665.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storee38f12b144573eb0f8822cbb74bdf665.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:75
* @route '/baku/admin/users'
*/
const store761de714b18569c72c4d5aae740322b7 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store761de714b18569c72c4d5aae740322b7.url(options),
    method: 'post',
})

store761de714b18569c72c4d5aae740322b7.definition = {
    methods: ["post"],
    url: '/baku/admin/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:75
* @route '/baku/admin/users'
*/
store761de714b18569c72c4d5aae740322b7.url = (options?: RouteQueryOptions) => {
    return store761de714b18569c72c4d5aae740322b7.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:75
* @route '/baku/admin/users'
*/
store761de714b18569c72c4d5aae740322b7.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store761de714b18569c72c4d5aae740322b7.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:75
* @route '/tbilisi/admin/users'
*/
const store1b67194e790cc5a7d0e08a7564efbfb4 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store1b67194e790cc5a7d0e08a7564efbfb4.url(options),
    method: 'post',
})

store1b67194e790cc5a7d0e08a7564efbfb4.definition = {
    methods: ["post"],
    url: '/tbilisi/admin/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:75
* @route '/tbilisi/admin/users'
*/
store1b67194e790cc5a7d0e08a7564efbfb4.url = (options?: RouteQueryOptions) => {
    return store1b67194e790cc5a7d0e08a7564efbfb4.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:75
* @route '/tbilisi/admin/users'
*/
store1b67194e790cc5a7d0e08a7564efbfb4.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store1b67194e790cc5a7d0e08a7564efbfb4.url(options),
    method: 'post',
})

export const store = {
    '/yerevan/admin/users': storee38f12b144573eb0f8822cbb74bdf665,
    '/baku/admin/users': store761de714b18569c72c4d5aae740322b7,
    '/tbilisi/admin/users': store1b67194e790cc5a7d0e08a7564efbfb4,
}

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/yerevan/admin/users/{user}/edit'
*/
const edit188d3b82b7e7e62d8fc3c04f6db57a6f = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit188d3b82b7e7e62d8fc3c04f6db57a6f.url(args, options),
    method: 'get',
})

edit188d3b82b7e7e62d8fc3c04f6db57a6f.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/users/{user}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/yerevan/admin/users/{user}/edit'
*/
edit188d3b82b7e7e62d8fc3c04f6db57a6f.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return edit188d3b82b7e7e62d8fc3c04f6db57a6f.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/yerevan/admin/users/{user}/edit'
*/
edit188d3b82b7e7e62d8fc3c04f6db57a6f.get = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit188d3b82b7e7e62d8fc3c04f6db57a6f.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/yerevan/admin/users/{user}/edit'
*/
edit188d3b82b7e7e62d8fc3c04f6db57a6f.head = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit188d3b82b7e7e62d8fc3c04f6db57a6f.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/baku/admin/users/{user}/edit'
*/
const edit3fcb35ca732f82782a0baca46da3d11d = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit3fcb35ca732f82782a0baca46da3d11d.url(args, options),
    method: 'get',
})

edit3fcb35ca732f82782a0baca46da3d11d.definition = {
    methods: ["get","head"],
    url: '/baku/admin/users/{user}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/baku/admin/users/{user}/edit'
*/
edit3fcb35ca732f82782a0baca46da3d11d.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return edit3fcb35ca732f82782a0baca46da3d11d.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/baku/admin/users/{user}/edit'
*/
edit3fcb35ca732f82782a0baca46da3d11d.get = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit3fcb35ca732f82782a0baca46da3d11d.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/baku/admin/users/{user}/edit'
*/
edit3fcb35ca732f82782a0baca46da3d11d.head = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit3fcb35ca732f82782a0baca46da3d11d.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/tbilisi/admin/users/{user}/edit'
*/
const edit03f855fe84c32682ae8091e07154400b = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit03f855fe84c32682ae8091e07154400b.url(args, options),
    method: 'get',
})

edit03f855fe84c32682ae8091e07154400b.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/users/{user}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/tbilisi/admin/users/{user}/edit'
*/
edit03f855fe84c32682ae8091e07154400b.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return edit03f855fe84c32682ae8091e07154400b.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/tbilisi/admin/users/{user}/edit'
*/
edit03f855fe84c32682ae8091e07154400b.get = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit03f855fe84c32682ae8091e07154400b.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:94
* @route '/tbilisi/admin/users/{user}/edit'
*/
edit03f855fe84c32682ae8091e07154400b.head = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit03f855fe84c32682ae8091e07154400b.url(args, options),
    method: 'head',
})

export const edit = {
    '/yerevan/admin/users/{user}/edit': edit188d3b82b7e7e62d8fc3c04f6db57a6f,
    '/baku/admin/users/{user}/edit': edit3fcb35ca732f82782a0baca46da3d11d,
    '/tbilisi/admin/users/{user}/edit': edit03f855fe84c32682ae8091e07154400b,
}

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:121
* @route '/yerevan/admin/users/{user}'
*/
const update8a872ec8ba4b9b69faede74a365fffcc = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update8a872ec8ba4b9b69faede74a365fffcc.url(args, options),
    method: 'put',
})

update8a872ec8ba4b9b69faede74a365fffcc.definition = {
    methods: ["put"],
    url: '/yerevan/admin/users/{user}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:121
* @route '/yerevan/admin/users/{user}'
*/
update8a872ec8ba4b9b69faede74a365fffcc.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return update8a872ec8ba4b9b69faede74a365fffcc.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:121
* @route '/yerevan/admin/users/{user}'
*/
update8a872ec8ba4b9b69faede74a365fffcc.put = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update8a872ec8ba4b9b69faede74a365fffcc.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:121
* @route '/baku/admin/users/{user}'
*/
const update1a06d795c6c6fb4eace6ff4705f7285e = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update1a06d795c6c6fb4eace6ff4705f7285e.url(args, options),
    method: 'put',
})

update1a06d795c6c6fb4eace6ff4705f7285e.definition = {
    methods: ["put"],
    url: '/baku/admin/users/{user}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:121
* @route '/baku/admin/users/{user}'
*/
update1a06d795c6c6fb4eace6ff4705f7285e.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return update1a06d795c6c6fb4eace6ff4705f7285e.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:121
* @route '/baku/admin/users/{user}'
*/
update1a06d795c6c6fb4eace6ff4705f7285e.put = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update1a06d795c6c6fb4eace6ff4705f7285e.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:121
* @route '/tbilisi/admin/users/{user}'
*/
const update085a59e45f1a6318e7383026589d48c9 = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update085a59e45f1a6318e7383026589d48c9.url(args, options),
    method: 'put',
})

update085a59e45f1a6318e7383026589d48c9.definition = {
    methods: ["put"],
    url: '/tbilisi/admin/users/{user}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:121
* @route '/tbilisi/admin/users/{user}'
*/
update085a59e45f1a6318e7383026589d48c9.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return update085a59e45f1a6318e7383026589d48c9.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:121
* @route '/tbilisi/admin/users/{user}'
*/
update085a59e45f1a6318e7383026589d48c9.put = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update085a59e45f1a6318e7383026589d48c9.url(args, options),
    method: 'put',
})

export const update = {
    '/yerevan/admin/users/{user}': update8a872ec8ba4b9b69faede74a365fffcc,
    '/baku/admin/users/{user}': update1a06d795c6c6fb4eace6ff4705f7285e,
    '/tbilisi/admin/users/{user}': update085a59e45f1a6318e7383026589d48c9,
}

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:136
* @route '/yerevan/admin/users/{user}'
*/
const destroy8a872ec8ba4b9b69faede74a365fffcc = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy8a872ec8ba4b9b69faede74a365fffcc.url(args, options),
    method: 'delete',
})

destroy8a872ec8ba4b9b69faede74a365fffcc.definition = {
    methods: ["delete"],
    url: '/yerevan/admin/users/{user}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:136
* @route '/yerevan/admin/users/{user}'
*/
destroy8a872ec8ba4b9b69faede74a365fffcc.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return destroy8a872ec8ba4b9b69faede74a365fffcc.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:136
* @route '/yerevan/admin/users/{user}'
*/
destroy8a872ec8ba4b9b69faede74a365fffcc.delete = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy8a872ec8ba4b9b69faede74a365fffcc.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:136
* @route '/baku/admin/users/{user}'
*/
const destroy1a06d795c6c6fb4eace6ff4705f7285e = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy1a06d795c6c6fb4eace6ff4705f7285e.url(args, options),
    method: 'delete',
})

destroy1a06d795c6c6fb4eace6ff4705f7285e.definition = {
    methods: ["delete"],
    url: '/baku/admin/users/{user}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:136
* @route '/baku/admin/users/{user}'
*/
destroy1a06d795c6c6fb4eace6ff4705f7285e.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return destroy1a06d795c6c6fb4eace6ff4705f7285e.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:136
* @route '/baku/admin/users/{user}'
*/
destroy1a06d795c6c6fb4eace6ff4705f7285e.delete = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy1a06d795c6c6fb4eace6ff4705f7285e.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:136
* @route '/tbilisi/admin/users/{user}'
*/
const destroy085a59e45f1a6318e7383026589d48c9 = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy085a59e45f1a6318e7383026589d48c9.url(args, options),
    method: 'delete',
})

destroy085a59e45f1a6318e7383026589d48c9.definition = {
    methods: ["delete"],
    url: '/tbilisi/admin/users/{user}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:136
* @route '/tbilisi/admin/users/{user}'
*/
destroy085a59e45f1a6318e7383026589d48c9.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return destroy085a59e45f1a6318e7383026589d48c9.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:136
* @route '/tbilisi/admin/users/{user}'
*/
destroy085a59e45f1a6318e7383026589d48c9.delete = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy085a59e45f1a6318e7383026589d48c9.url(args, options),
    method: 'delete',
})

export const destroy = {
    '/yerevan/admin/users/{user}': destroy8a872ec8ba4b9b69faede74a365fffcc,
    '/baku/admin/users/{user}': destroy1a06d795c6c6fb4eace6ff4705f7285e,
    '/tbilisi/admin/users/{user}': destroy085a59e45f1a6318e7383026589d48c9,
}

/**
* @see \App\Http\Controllers\Admin\UserController::restore
* @see app/Http/Controllers/Admin/UserController.php:150
* @route '/yerevan/admin/users/{user}/restore'
*/
const restore37beb7524207d6760d5037316b1a3644 = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: restore37beb7524207d6760d5037316b1a3644.url(args, options),
    method: 'patch',
})

restore37beb7524207d6760d5037316b1a3644.definition = {
    methods: ["patch"],
    url: '/yerevan/admin/users/{user}/restore',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\UserController::restore
* @see app/Http/Controllers/Admin/UserController.php:150
* @route '/yerevan/admin/users/{user}/restore'
*/
restore37beb7524207d6760d5037316b1a3644.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return restore37beb7524207d6760d5037316b1a3644.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::restore
* @see app/Http/Controllers/Admin/UserController.php:150
* @route '/yerevan/admin/users/{user}/restore'
*/
restore37beb7524207d6760d5037316b1a3644.patch = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: restore37beb7524207d6760d5037316b1a3644.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Admin\UserController::restore
* @see app/Http/Controllers/Admin/UserController.php:150
* @route '/baku/admin/users/{user}/restore'
*/
const restore8ebfa34e6fdd939e324d8cad7b03c1e6 = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: restore8ebfa34e6fdd939e324d8cad7b03c1e6.url(args, options),
    method: 'patch',
})

restore8ebfa34e6fdd939e324d8cad7b03c1e6.definition = {
    methods: ["patch"],
    url: '/baku/admin/users/{user}/restore',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\UserController::restore
* @see app/Http/Controllers/Admin/UserController.php:150
* @route '/baku/admin/users/{user}/restore'
*/
restore8ebfa34e6fdd939e324d8cad7b03c1e6.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return restore8ebfa34e6fdd939e324d8cad7b03c1e6.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::restore
* @see app/Http/Controllers/Admin/UserController.php:150
* @route '/baku/admin/users/{user}/restore'
*/
restore8ebfa34e6fdd939e324d8cad7b03c1e6.patch = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: restore8ebfa34e6fdd939e324d8cad7b03c1e6.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Admin\UserController::restore
* @see app/Http/Controllers/Admin/UserController.php:150
* @route '/tbilisi/admin/users/{user}/restore'
*/
const restorec9b9859f72d101878af7ab9491ff896f = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: restorec9b9859f72d101878af7ab9491ff896f.url(args, options),
    method: 'patch',
})

restorec9b9859f72d101878af7ab9491ff896f.definition = {
    methods: ["patch"],
    url: '/tbilisi/admin/users/{user}/restore',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\UserController::restore
* @see app/Http/Controllers/Admin/UserController.php:150
* @route '/tbilisi/admin/users/{user}/restore'
*/
restorec9b9859f72d101878af7ab9491ff896f.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return restorec9b9859f72d101878af7ab9491ff896f.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::restore
* @see app/Http/Controllers/Admin/UserController.php:150
* @route '/tbilisi/admin/users/{user}/restore'
*/
restorec9b9859f72d101878af7ab9491ff896f.patch = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: restorec9b9859f72d101878af7ab9491ff896f.url(args, options),
    method: 'patch',
})

export const restore = {
    '/yerevan/admin/users/{user}/restore': restore37beb7524207d6760d5037316b1a3644,
    '/baku/admin/users/{user}/restore': restore8ebfa34e6fdd939e324d8cad7b03c1e6,
    '/tbilisi/admin/users/{user}/restore': restorec9b9859f72d101878af7ab9491ff896f,
}

/**
* @see \App\Http\Controllers\Admin\UserController::forceDelete
* @see app/Http/Controllers/Admin/UserController.php:164
* @route '/yerevan/admin/users/{user}/force-delete'
*/
const forceDeletee4ddbfed03fe2c5c28da3eaedf9eb69c = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: forceDeletee4ddbfed03fe2c5c28da3eaedf9eb69c.url(args, options),
    method: 'delete',
})

forceDeletee4ddbfed03fe2c5c28da3eaedf9eb69c.definition = {
    methods: ["delete"],
    url: '/yerevan/admin/users/{user}/force-delete',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\UserController::forceDelete
* @see app/Http/Controllers/Admin/UserController.php:164
* @route '/yerevan/admin/users/{user}/force-delete'
*/
forceDeletee4ddbfed03fe2c5c28da3eaedf9eb69c.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return forceDeletee4ddbfed03fe2c5c28da3eaedf9eb69c.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::forceDelete
* @see app/Http/Controllers/Admin/UserController.php:164
* @route '/yerevan/admin/users/{user}/force-delete'
*/
forceDeletee4ddbfed03fe2c5c28da3eaedf9eb69c.delete = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: forceDeletee4ddbfed03fe2c5c28da3eaedf9eb69c.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\UserController::forceDelete
* @see app/Http/Controllers/Admin/UserController.php:164
* @route '/baku/admin/users/{user}/force-delete'
*/
const forceDelete2b8df150ec5dd36d05323546256cc267 = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: forceDelete2b8df150ec5dd36d05323546256cc267.url(args, options),
    method: 'delete',
})

forceDelete2b8df150ec5dd36d05323546256cc267.definition = {
    methods: ["delete"],
    url: '/baku/admin/users/{user}/force-delete',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\UserController::forceDelete
* @see app/Http/Controllers/Admin/UserController.php:164
* @route '/baku/admin/users/{user}/force-delete'
*/
forceDelete2b8df150ec5dd36d05323546256cc267.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return forceDelete2b8df150ec5dd36d05323546256cc267.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::forceDelete
* @see app/Http/Controllers/Admin/UserController.php:164
* @route '/baku/admin/users/{user}/force-delete'
*/
forceDelete2b8df150ec5dd36d05323546256cc267.delete = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: forceDelete2b8df150ec5dd36d05323546256cc267.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\UserController::forceDelete
* @see app/Http/Controllers/Admin/UserController.php:164
* @route '/tbilisi/admin/users/{user}/force-delete'
*/
const forceDelete613769948fc141603dccdd5264a6d945 = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: forceDelete613769948fc141603dccdd5264a6d945.url(args, options),
    method: 'delete',
})

forceDelete613769948fc141603dccdd5264a6d945.definition = {
    methods: ["delete"],
    url: '/tbilisi/admin/users/{user}/force-delete',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\UserController::forceDelete
* @see app/Http/Controllers/Admin/UserController.php:164
* @route '/tbilisi/admin/users/{user}/force-delete'
*/
forceDelete613769948fc141603dccdd5264a6d945.url = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: args.user,
    }

    return forceDelete613769948fc141603dccdd5264a6d945.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::forceDelete
* @see app/Http/Controllers/Admin/UserController.php:164
* @route '/tbilisi/admin/users/{user}/force-delete'
*/
forceDelete613769948fc141603dccdd5264a6d945.delete = (args: { user: string | number } | [user: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: forceDelete613769948fc141603dccdd5264a6d945.url(args, options),
    method: 'delete',
})

export const forceDelete = {
    '/yerevan/admin/users/{user}/force-delete': forceDeletee4ddbfed03fe2c5c28da3eaedf9eb69c,
    '/baku/admin/users/{user}/force-delete': forceDelete2b8df150ec5dd36d05323546256cc267,
    '/tbilisi/admin/users/{user}/force-delete': forceDelete613769948fc141603dccdd5264a6d945,
}

const UserController = { index, create, trashed, store, edit, update, destroy, restore, forceDelete }

export default UserController