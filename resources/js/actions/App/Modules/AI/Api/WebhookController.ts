import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
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

const WebhookController = { receive }

export default WebhookController