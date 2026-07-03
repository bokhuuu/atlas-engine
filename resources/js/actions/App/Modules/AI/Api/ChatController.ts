import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:114
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
* @see app/Modules/AI/Api/ChatController.php:114
* @route '/api/v1/ai/history'
*/
history.url = (options?: RouteQueryOptions) => {
    return history.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:114
* @route '/api/v1/ai/history'
*/
history.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:114
* @route '/api/v1/ai/history'
*/
history.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: history.url(options),
    method: 'head',
})

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:114
* @route '/api/v1/ai/history'
*/
const historyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: history.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:114
* @route '/api/v1/ai/history'
*/
historyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: history.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:114
* @route '/api/v1/ai/history'
*/
historyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: history.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

history.form = historyForm

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:180
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
* @see app/Modules/AI/Api/ChatController.php:180
* @route '/api/v1/ai/usage/system'
*/
systemUsage.url = (options?: RouteQueryOptions) => {
    return systemUsage.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:180
* @route '/api/v1/ai/usage/system'
*/
systemUsage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: systemUsage.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:180
* @route '/api/v1/ai/usage/system'
*/
systemUsage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: systemUsage.url(options),
    method: 'head',
})

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:180
* @route '/api/v1/ai/usage/system'
*/
const systemUsageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: systemUsage.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:180
* @route '/api/v1/ai/usage/system'
*/
systemUsageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: systemUsage.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::systemUsage
* @see app/Modules/AI/Api/ChatController.php:180
* @route '/api/v1/ai/usage/system'
*/
systemUsageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: systemUsage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

systemUsage.form = systemUsageForm

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:151
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
* @see app/Modules/AI/Api/ChatController.php:151
* @route '/api/v1/ai/usage'
*/
usage.url = (options?: RouteQueryOptions) => {
    return usage.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:151
* @route '/api/v1/ai/usage'
*/
usage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: usage.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:151
* @route '/api/v1/ai/usage'
*/
usage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: usage.url(options),
    method: 'head',
})

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:151
* @route '/api/v1/ai/usage'
*/
const usageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: usage.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:151
* @route '/api/v1/ai/usage'
*/
usageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: usage.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::usage
* @see app/Modules/AI/Api/ChatController.php:151
* @route '/api/v1/ai/usage'
*/
usageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: usage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

usage.form = usageForm

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:44
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
* @see app/Modules/AI/Api/ChatController.php:44
* @route '/api/v1/ai/chat'
*/
chat.url = (options?: RouteQueryOptions) => {
    return chat.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:44
* @route '/api/v1/ai/chat'
*/
chat.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chat.url(options),
    method: 'post',
})

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:44
* @route '/api/v1/ai/chat'
*/
const chatForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: chat.url(options),
    method: 'post',
})

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:44
* @route '/api/v1/ai/chat'
*/
chatForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: chat.url(options),
    method: 'post',
})

chat.form = chatForm

const ChatController = { history, systemUsage, usage, chat }

export default ChatController