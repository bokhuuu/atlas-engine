import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:116
* @route '/api/v1/ai/history'
*/
export const history = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(options),
    method: 'get',
})

history.definition = {
    methods: ["get","head"],
    url: '/api/v1/ai/history',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:116
* @route '/api/v1/ai/history'
*/
history.url = (options?: RouteQueryOptions) => {
    return history.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:116
* @route '/api/v1/ai/history'
*/
history.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:116
* @route '/api/v1/ai/history'
*/
history.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: history.url(options),
    method: 'head',
})

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:198
* @route '/api/v1/ai/usage/system'
*/
export const systemUsage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: systemUsage.url(options),
    method: 'get',
})

systemUsage.definition = {
    methods: ["get","head"],
    url: '/api/v1/ai/usage/system',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:198
* @route '/api/v1/ai/usage/system'
*/
systemUsage.url = (options?: RouteQueryOptions) => {
    return systemUsage.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:198
* @route '/api/v1/ai/usage/system'
*/
systemUsage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: systemUsage.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:198
* @route '/api/v1/ai/usage/system'
*/
systemUsage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: systemUsage.url(options),
    method: 'head',
})

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:154
* @route '/api/v1/ai/usage'
*/
export const usage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: usage.url(options),
    method: 'get',
})

usage.definition = {
    methods: ["get","head"],
    url: '/api/v1/ai/usage',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:154
* @route '/api/v1/ai/usage'
*/
usage.url = (options?: RouteQueryOptions) => {
    return usage.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:154
* @route '/api/v1/ai/usage'
*/
usage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: usage.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:154
* @route '/api/v1/ai/usage'
*/
usage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: usage.url(options),
    method: 'head',
})

/**
* @see \App\Modules\AI\Api\ChatController::feedback
* @see app/Modules/AI/Api/ChatController.php:184
* @route '/api/v1/ai/feedback'
*/
export const feedback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: feedback.url(options),
    method: 'get',
})

feedback.definition = {
    methods: ["get","head"],
    url: '/api/v1/ai/feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\AI\Api\ChatController::feedback
* @see app/Modules/AI/Api/ChatController.php:184
* @route '/api/v1/ai/feedback'
*/
feedback.url = (options?: RouteQueryOptions) => {
    return feedback.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::feedback
* @see app/Modules/AI/Api/ChatController.php:184
* @route '/api/v1/ai/feedback'
*/
feedback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: feedback.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::feedback
* @see app/Modules/AI/Api/ChatController.php:184
* @route '/api/v1/ai/feedback'
*/
feedback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: feedback.url(options),
    method: 'head',
})

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:45
* @route '/api/v1/ai/chat'
*/
export const chat = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chat.url(options),
    method: 'post',
})

chat.definition = {
    methods: ["post"],
    url: '/api/v1/ai/chat',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:45
* @route '/api/v1/ai/chat'
*/
chat.url = (options?: RouteQueryOptions) => {
    return chat.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:45
* @route '/api/v1/ai/chat'
*/
chat.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chat.url(options),
    method: 'post',
})

const ChatController = { history, systemUsage, usage, feedback, chat }

export default ChatController