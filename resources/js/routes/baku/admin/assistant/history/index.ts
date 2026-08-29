import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AssistantController::clear
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
export const clear = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clear.url(options),
    method: 'delete',
})

clear.definition = {
    methods: ["delete"],
    url: '/baku/admin/assistant/history',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\AssistantController::clear
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
clear.url = (options?: RouteQueryOptions) => {
    return clear.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssistantController::clear
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
clear.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: clear.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clear
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
const clearForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clear.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\AssistantController::clear
* @see app/Http/Controllers/Admin/AssistantController.php:32
* @route '/baku/admin/assistant/history'
*/
clearForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clear.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

clear.form = clearForm

const history = {
    clear: Object.assign(clear, clear),
}

export default history