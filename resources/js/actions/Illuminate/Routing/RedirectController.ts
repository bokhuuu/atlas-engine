import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
const RedirectController980bb49ee7ae63891f1d891d2fbcf1c9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url = (options?: RouteQueryOptions) => {
    return RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
const RedirectController980bb49ee7ae63891f1d891d2fbcf1c9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/'
*/
RedirectController980bb49ee7ae63891f1d891d2fbcf1c9Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController980bb49ee7ae63891f1d891d2fbcf1c9.form = RedirectController980bb49ee7ae63891f1d891d2fbcf1c9Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
const RedirectController35f58437d9250c39f332f5e8e70440b7 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})

RedirectController35f58437d9250c39f332f5e8e70440b7.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.url = (options?: RouteQueryOptions) => {
    return RedirectController35f58437d9250c39f332f5e8e70440b7.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
const RedirectController35f58437d9250c39f332f5e8e70440b7Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController35f58437d9250c39f332f5e8e70440b7.form = RedirectController35f58437d9250c39f332f5e8e70440b7Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
const RedirectController750aeb224105761400ee952169bd178c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'get',
})

RedirectController750aeb224105761400ee952169bd178c.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin/dashboard',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178c.url = (options?: RouteQueryOptions) => {
    return RedirectController750aeb224105761400ee952169bd178c.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178c.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178c.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178c.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178c.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
const RedirectController750aeb224105761400ee952169bd178cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController750aeb224105761400ee952169bd178c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController750aeb224105761400ee952169bd178c.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178cForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController750aeb224105761400ee952169bd178c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178cForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController750aeb224105761400ee952169bd178c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178cForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController750aeb224105761400ee952169bd178c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/dashboard'
*/
RedirectController750aeb224105761400ee952169bd178cForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController750aeb224105761400ee952169bd178c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController750aeb224105761400ee952169bd178c.form = RedirectController750aeb224105761400ee952169bd178cForm
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
const RedirectController4b87d2df7e3aa853f6720faea796e36c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

RedirectController4b87d2df7e3aa853f6720faea796e36c.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/settings',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.url = (options?: RouteQueryOptions) => {
    return RedirectController4b87d2df7e3aa853f6720faea796e36c.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
const RedirectController4b87d2df7e3aa853f6720faea796e36cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController4b87d2df7e3aa853f6720faea796e36c.form = RedirectController4b87d2df7e3aa853f6720faea796e36cForm

const RedirectController = {
    '/': RedirectController980bb49ee7ae63891f1d891d2fbcf1c9,
    '/admin': RedirectController35f58437d9250c39f332f5e8e70440b7,
    '/admin/dashboard': RedirectController750aeb224105761400ee952169bd178c,
    '/settings': RedirectController4b87d2df7e3aa853f6720faea796e36c,
}

export default RedirectController