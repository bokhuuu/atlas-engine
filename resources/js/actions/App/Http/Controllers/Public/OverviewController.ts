import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Public\OverviewController::__invoke
* @see app/Http/Controllers/Public/OverviewController.php:34
* @route '/'
*/
const OverviewController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: OverviewController.url(options),
    method: 'get',
})

OverviewController.definition = {
    methods: ["get","head"],
    url: '/',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\OverviewController::__invoke
* @see app/Http/Controllers/Public/OverviewController.php:34
* @route '/'
*/
OverviewController.url = (options?: RouteQueryOptions) => {
    return OverviewController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\OverviewController::__invoke
* @see app/Http/Controllers/Public/OverviewController.php:34
* @route '/'
*/
OverviewController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: OverviewController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OverviewController::__invoke
* @see app/Http/Controllers/Public/OverviewController.php:34
* @route '/'
*/
OverviewController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: OverviewController.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Public\OverviewController::__invoke
* @see app/Http/Controllers/Public/OverviewController.php:34
* @route '/'
*/
const OverviewControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: OverviewController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OverviewController::__invoke
* @see app/Http/Controllers/Public/OverviewController.php:34
* @route '/'
*/
OverviewControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: OverviewController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OverviewController::__invoke
* @see app/Http/Controllers/Public/OverviewController.php:34
* @route '/'
*/
OverviewControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: OverviewController.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

OverviewController.form = OverviewControllerForm

export default OverviewController