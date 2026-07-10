import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Inertia\Controller::__invoke
* @see vendor/inertiajs/inertia-laravel/src/Controller.php:13
* @route '/baku'
*/
export const unnamed = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unnamed.url(options),
    method: 'get',
})

unnamed.definition = {
    methods: ["get","head"],
    url: '/baku',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Inertia\Controller::__invoke
* @see vendor/inertiajs/inertia-laravel/src/Controller.php:13
* @route '/baku'
*/
unnamed.url = (options?: RouteQueryOptions) => {
    return unnamed.definition.url + queryParams(options)
}

/**
* @see \Inertia\Controller::__invoke
* @see vendor/inertiajs/inertia-laravel/src/Controller.php:13
* @route '/baku'
*/
unnamed.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unnamed.url(options),
    method: 'get',
})

/**
* @see \Inertia\Controller::__invoke
* @see vendor/inertiajs/inertia-laravel/src/Controller.php:13
* @route '/baku'
*/
unnamed.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: unnamed.url(options),
    method: 'head',
})

/**
* @see \Inertia\Controller::__invoke
* @see vendor/inertiajs/inertia-laravel/src/Controller.php:13
* @route '/baku'
*/
const unnamedForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: unnamed.url(options),
    method: 'get',
})

/**
* @see \Inertia\Controller::__invoke
* @see vendor/inertiajs/inertia-laravel/src/Controller.php:13
* @route '/baku'
*/
unnamedForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: unnamed.url(options),
    method: 'get',
})

/**
* @see \Inertia\Controller::__invoke
* @see vendor/inertiajs/inertia-laravel/src/Controller.php:13
* @route '/baku'
*/
unnamedForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: unnamed.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

unnamed.form = unnamedForm

const home = {
    unnamed: Object.assign(unnamed, unnamed),
}

export default home