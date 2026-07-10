import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
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

export default SnapshotController