import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:112
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
* @see app/Modules/AI/Api/ChatController.php:112
* @route '/api/v1/ai/history'
*/
history.url = (options?: RouteQueryOptions) => {
    return history.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:112
* @route '/api/v1/ai/history'
*/
history.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:112
* @route '/api/v1/ai/history'
*/
history.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: history.url(options),
    method: 'head',
})

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:112
* @route '/api/v1/ai/history'
*/
const historyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: history.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:112
* @route '/api/v1/ai/history'
*/
historyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: history.url(options),
    method: 'get',
})

/**
* @see \App\Modules\AI\Api\ChatController::history
* @see app/Modules/AI/Api/ChatController.php:112
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
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:43
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
* @see app/Modules/AI/Api/ChatController.php:43
* @route '/api/v1/ai/chat'
*/
chat.url = (options?: RouteQueryOptions) => {
    return chat.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:43
* @route '/api/v1/ai/chat'
*/
chat.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chat.url(options),
    method: 'post',
})

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:43
* @route '/api/v1/ai/chat'
*/
const chatForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: chat.url(options),
    method: 'post',
})

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:43
* @route '/api/v1/ai/chat'
*/
chatForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: chat.url(options),
    method: 'post',
})

chat.form = chatForm

const ChatController = { history, chat }

export default ChatController