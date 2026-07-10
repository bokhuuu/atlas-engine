import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::index
* @see app/Http/Controllers/Admin/AssistantController.php:19
* @route '/admin/assistant'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const AssistantController = { index }

export default AssistantController