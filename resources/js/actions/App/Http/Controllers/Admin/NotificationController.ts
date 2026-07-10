import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/yerevan/admin/notifications/{id}/read'
*/
const markAsRead2db3e65050af42e94adf26fd6b09cd06 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead2db3e65050af42e94adf26fd6b09cd06.url(args, options),
    method: 'post',
})

markAsRead2db3e65050af42e94adf26fd6b09cd06.definition = {
    methods: ["post"],
    url: '/yerevan/admin/notifications/{id}/read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/yerevan/admin/notifications/{id}/read'
*/
markAsRead2db3e65050af42e94adf26fd6b09cd06.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return markAsRead2db3e65050af42e94adf26fd6b09cd06.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/yerevan/admin/notifications/{id}/read'
*/
markAsRead2db3e65050af42e94adf26fd6b09cd06.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead2db3e65050af42e94adf26fd6b09cd06.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/yerevan/admin/notifications/{id}/read'
*/
const markAsRead2db3e65050af42e94adf26fd6b09cd06Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAsRead2db3e65050af42e94adf26fd6b09cd06.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/yerevan/admin/notifications/{id}/read'
*/
markAsRead2db3e65050af42e94adf26fd6b09cd06Form.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAsRead2db3e65050af42e94adf26fd6b09cd06.url(args, options),
    method: 'post',
})

markAsRead2db3e65050af42e94adf26fd6b09cd06.form = markAsRead2db3e65050af42e94adf26fd6b09cd06Form
/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/baku/admin/notifications/{id}/read'
*/
const markAsRead9abcbbf0ac6e4024e93caad72cf6841f = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead9abcbbf0ac6e4024e93caad72cf6841f.url(args, options),
    method: 'post',
})

markAsRead9abcbbf0ac6e4024e93caad72cf6841f.definition = {
    methods: ["post"],
    url: '/baku/admin/notifications/{id}/read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/baku/admin/notifications/{id}/read'
*/
markAsRead9abcbbf0ac6e4024e93caad72cf6841f.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return markAsRead9abcbbf0ac6e4024e93caad72cf6841f.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/baku/admin/notifications/{id}/read'
*/
markAsRead9abcbbf0ac6e4024e93caad72cf6841f.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead9abcbbf0ac6e4024e93caad72cf6841f.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/baku/admin/notifications/{id}/read'
*/
const markAsRead9abcbbf0ac6e4024e93caad72cf6841fForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAsRead9abcbbf0ac6e4024e93caad72cf6841f.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/baku/admin/notifications/{id}/read'
*/
markAsRead9abcbbf0ac6e4024e93caad72cf6841fForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAsRead9abcbbf0ac6e4024e93caad72cf6841f.url(args, options),
    method: 'post',
})

markAsRead9abcbbf0ac6e4024e93caad72cf6841f.form = markAsRead9abcbbf0ac6e4024e93caad72cf6841fForm
/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/tbilisi/admin/notifications/{id}/read'
*/
const markAsReadf6a2348b1cc571c74adf6cf2022fc774 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsReadf6a2348b1cc571c74adf6cf2022fc774.url(args, options),
    method: 'post',
})

markAsReadf6a2348b1cc571c74adf6cf2022fc774.definition = {
    methods: ["post"],
    url: '/tbilisi/admin/notifications/{id}/read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/tbilisi/admin/notifications/{id}/read'
*/
markAsReadf6a2348b1cc571c74adf6cf2022fc774.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return markAsReadf6a2348b1cc571c74adf6cf2022fc774.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/tbilisi/admin/notifications/{id}/read'
*/
markAsReadf6a2348b1cc571c74adf6cf2022fc774.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsReadf6a2348b1cc571c74adf6cf2022fc774.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/tbilisi/admin/notifications/{id}/read'
*/
const markAsReadf6a2348b1cc571c74adf6cf2022fc774Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAsReadf6a2348b1cc571c74adf6cf2022fc774.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:26
* @route '/tbilisi/admin/notifications/{id}/read'
*/
markAsReadf6a2348b1cc571c74adf6cf2022fc774Form.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAsReadf6a2348b1cc571c74adf6cf2022fc774.url(args, options),
    method: 'post',
})

markAsReadf6a2348b1cc571c74adf6cf2022fc774.form = markAsReadf6a2348b1cc571c74adf6cf2022fc774Form

export const markAsRead = {
    '/yerevan/admin/notifications/{id}/read': markAsRead2db3e65050af42e94adf26fd6b09cd06,
    '/baku/admin/notifications/{id}/read': markAsRead9abcbbf0ac6e4024e93caad72cf6841f,
    '/tbilisi/admin/notifications/{id}/read': markAsReadf6a2348b1cc571c74adf6cf2022fc774,
}

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/yerevan/admin/notifications/read-all'
*/
const markAllAsReade437815a80419f4215dbf5ee385d862a = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllAsReade437815a80419f4215dbf5ee385d862a.url(options),
    method: 'post',
})

markAllAsReade437815a80419f4215dbf5ee385d862a.definition = {
    methods: ["post"],
    url: '/yerevan/admin/notifications/read-all',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/yerevan/admin/notifications/read-all'
*/
markAllAsReade437815a80419f4215dbf5ee385d862a.url = (options?: RouteQueryOptions) => {
    return markAllAsReade437815a80419f4215dbf5ee385d862a.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/yerevan/admin/notifications/read-all'
*/
markAllAsReade437815a80419f4215dbf5ee385d862a.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllAsReade437815a80419f4215dbf5ee385d862a.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/yerevan/admin/notifications/read-all'
*/
const markAllAsReade437815a80419f4215dbf5ee385d862aForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAllAsReade437815a80419f4215dbf5ee385d862a.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/yerevan/admin/notifications/read-all'
*/
markAllAsReade437815a80419f4215dbf5ee385d862aForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAllAsReade437815a80419f4215dbf5ee385d862a.url(options),
    method: 'post',
})

markAllAsReade437815a80419f4215dbf5ee385d862a.form = markAllAsReade437815a80419f4215dbf5ee385d862aForm
/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/baku/admin/notifications/read-all'
*/
const markAllAsRead9485bc348d5016bd5327ae1b6f51701a = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllAsRead9485bc348d5016bd5327ae1b6f51701a.url(options),
    method: 'post',
})

markAllAsRead9485bc348d5016bd5327ae1b6f51701a.definition = {
    methods: ["post"],
    url: '/baku/admin/notifications/read-all',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/baku/admin/notifications/read-all'
*/
markAllAsRead9485bc348d5016bd5327ae1b6f51701a.url = (options?: RouteQueryOptions) => {
    return markAllAsRead9485bc348d5016bd5327ae1b6f51701a.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/baku/admin/notifications/read-all'
*/
markAllAsRead9485bc348d5016bd5327ae1b6f51701a.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllAsRead9485bc348d5016bd5327ae1b6f51701a.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/baku/admin/notifications/read-all'
*/
const markAllAsRead9485bc348d5016bd5327ae1b6f51701aForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAllAsRead9485bc348d5016bd5327ae1b6f51701a.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/baku/admin/notifications/read-all'
*/
markAllAsRead9485bc348d5016bd5327ae1b6f51701aForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAllAsRead9485bc348d5016bd5327ae1b6f51701a.url(options),
    method: 'post',
})

markAllAsRead9485bc348d5016bd5327ae1b6f51701a.form = markAllAsRead9485bc348d5016bd5327ae1b6f51701aForm
/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/tbilisi/admin/notifications/read-all'
*/
const markAllAsRead1ad4044e3a542d749ef80714afd82529 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllAsRead1ad4044e3a542d749ef80714afd82529.url(options),
    method: 'post',
})

markAllAsRead1ad4044e3a542d749ef80714afd82529.definition = {
    methods: ["post"],
    url: '/tbilisi/admin/notifications/read-all',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/tbilisi/admin/notifications/read-all'
*/
markAllAsRead1ad4044e3a542d749ef80714afd82529.url = (options?: RouteQueryOptions) => {
    return markAllAsRead1ad4044e3a542d749ef80714afd82529.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/tbilisi/admin/notifications/read-all'
*/
markAllAsRead1ad4044e3a542d749ef80714afd82529.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllAsRead1ad4044e3a542d749ef80714afd82529.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/tbilisi/admin/notifications/read-all'
*/
const markAllAsRead1ad4044e3a542d749ef80714afd82529Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAllAsRead1ad4044e3a542d749ef80714afd82529.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NotificationController::markAllAsRead
* @see app/Http/Controllers/Admin/NotificationController.php:40
* @route '/tbilisi/admin/notifications/read-all'
*/
markAllAsRead1ad4044e3a542d749ef80714afd82529Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: markAllAsRead1ad4044e3a542d749ef80714afd82529.url(options),
    method: 'post',
})

markAllAsRead1ad4044e3a542d749ef80714afd82529.form = markAllAsRead1ad4044e3a542d749ef80714afd82529Form

export const markAllAsRead = {
    '/yerevan/admin/notifications/read-all': markAllAsReade437815a80419f4215dbf5ee385d862a,
    '/baku/admin/notifications/read-all': markAllAsRead9485bc348d5016bd5327ae1b6f51701a,
    '/tbilisi/admin/notifications/read-all': markAllAsRead1ad4044e3a542d749ef80714afd82529,
}

const NotificationController = { markAsRead, markAllAsRead }

export default NotificationController