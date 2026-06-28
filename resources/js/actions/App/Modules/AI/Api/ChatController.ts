import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:41
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
* @see app/Modules/AI/Api/ChatController.php:41
* @route '/api/v1/ai/chat'
*/
chat.url = (options?: RouteQueryOptions) => {
    return chat.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:41
* @route '/api/v1/ai/chat'
*/
chat.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chat.url(options),
    method: 'post',
})

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:41
* @route '/api/v1/ai/chat'
*/
const chatForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: chat.url(options),
    method: 'post',
})

/**
* @see \App\Modules\AI\Api\ChatController::chat
* @see app/Modules/AI/Api/ChatController.php:41
* @route '/api/v1/ai/chat'
*/
chatForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: chat.url(options),
    method: 'post',
})

chat.form = chatForm

const ChatController = { chat }

export default ChatController