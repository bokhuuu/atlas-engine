import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\AI\Api\FeedbackController::store
* @see app/Modules/AI/Api/FeedbackController.php:26
* @route '/api/v1/ai/feedback'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/v1/ai/feedback',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Modules\AI\Api\FeedbackController::store
* @see app/Modules/AI/Api/FeedbackController.php:26
* @route '/api/v1/ai/feedback'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Modules\AI\Api\FeedbackController::store
* @see app/Modules/AI/Api/FeedbackController.php:26
* @route '/api/v1/ai/feedback'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

const FeedbackController = { store }

export default FeedbackController