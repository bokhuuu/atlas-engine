import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\AI\Api\HealthCheckController::check
* @see app/Modules/AI/Api/HealthCheckController.php:30
* @route '/api/v1/ai/health'
*/
export const check = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: check.url(options),
    method: 'get',
})

check.definition = {
    methods: ["get","head"],
    url: '/api/v1/ai/health',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\AI\Api\HealthCheckController::check
* @see app/Modules/AI/Api/HealthCheckController.php:30
* @route '/api/v1/ai/health'
*/
check.url = (options?: RouteQueryOptions) => {
    return check.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\HealthCheckController::check
* @see app/Modules/AI/Api/HealthCheckController.php:30
* @route '/api/v1/ai/health'
*/
check.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: check.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\HealthCheckController::check
* @see app/Modules/AI/Api/HealthCheckController.php:30
* @route '/api/v1/ai/health'
*/
check.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: check.url(options),
    method: 'head',
})

/**
* @see \App\Modules\AI\Api\HealthCheckController::check
* @see app/Modules/AI/Api/HealthCheckController.php:30
* @route '/api/v1/ai/health'
*/
const checkForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: check.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\HealthCheckController::check
* @see app/Modules/AI/Api/HealthCheckController.php:30
* @route '/api/v1/ai/health'
*/
checkForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: check.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\HealthCheckController::check
* @see app/Modules/AI/Api/HealthCheckController.php:30
* @route '/api/v1/ai/health'
*/
checkForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: check.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

check.form = checkForm

const HealthCheckController = { check }

export default HealthCheckController