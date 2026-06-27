import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\AI\Api\WebhookController::receive
* @see app/Modules/AI/Api/WebhookController.php:29
* @route '/api/v1/ai/webhook'
*/
export const receive = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: receive.url(options),
    method: 'post',
})

receive.definition = {
    methods: ["post"],
    url: '/api/v1/ai/webhook',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Modules\AI\Api\WebhookController::receive
* @see app/Modules/AI/Api/WebhookController.php:29
* @route '/api/v1/ai/webhook'
*/
receive.url = (options?: RouteQueryOptions) => {
    return receive.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\WebhookController::receive
* @see app/Modules/AI/Api/WebhookController.php:29
* @route '/api/v1/ai/webhook'
*/
receive.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: receive.url(options),
    method: 'post',
})

/**
* @see \App\Modules\AI\Api\WebhookController::receive
* @see app/Modules/AI/Api/WebhookController.php:29
* @route '/api/v1/ai/webhook'
*/
const receiveForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: receive.url(options),
    method: 'post',
})

/**
* @see \App\Modules\AI\Api\WebhookController::receive
* @see app/Modules/AI/Api/WebhookController.php:29
* @route '/api/v1/ai/webhook'
*/
receiveForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: receive.url(options),
    method: 'post',
})

receive.form = receiveForm

const WebhookController = { receive }

export default WebhookController