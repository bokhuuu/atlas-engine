import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Public\SnapshotController::__invoke
* @see app/Http/Controllers/Public/SnapshotController.php:44
* @route '/api/v1/public/snapshot'
*/
const SnapshotController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SnapshotController.url(options),
    method: 'get',
})

SnapshotController.definition = {
    methods: ["get","head"],
    url: '/api/v1/public/snapshot',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\SnapshotController::__invoke
* @see app/Http/Controllers/Public/SnapshotController.php:44
* @route '/api/v1/public/snapshot'
*/
SnapshotController.url = (options?: RouteQueryOptions) => {
    return SnapshotController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\SnapshotController::__invoke
* @see app/Http/Controllers/Public/SnapshotController.php:44
* @route '/api/v1/public/snapshot'
*/
SnapshotController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SnapshotController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\SnapshotController::__invoke
* @see app/Http/Controllers/Public/SnapshotController.php:44
* @route '/api/v1/public/snapshot'
*/
SnapshotController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SnapshotController.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Public\SnapshotController::__invoke
* @see app/Http/Controllers/Public/SnapshotController.php:44
* @route '/api/v1/public/snapshot'
*/
const SnapshotControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SnapshotController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\SnapshotController::__invoke
* @see app/Http/Controllers/Public/SnapshotController.php:44
* @route '/api/v1/public/snapshot'
*/
SnapshotControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SnapshotController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\SnapshotController::__invoke
* @see app/Http/Controllers/Public/SnapshotController.php:44
* @route '/api/v1/public/snapshot'
*/
SnapshotControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SnapshotController.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

SnapshotController.form = SnapshotControllerForm

export default SnapshotController