import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:31
* @route '/api/v1/listings'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/v1/listings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:31
* @route '/api/v1/listings'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:31
* @route '/api/v1/listings'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:31
* @route '/api/v1/listings'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:31
* @route '/api/v1/listings'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:31
* @route '/api/v1/listings'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:31
* @route '/api/v1/listings'
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

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:49
* @route '/api/v1/listings/stats'
*/
export const stats = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: stats.url(options),
    method: 'get',
})

stats.definition = {
    methods: ["get","head"],
    url: '/api/v1/listings/stats',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:49
* @route '/api/v1/listings/stats'
*/
stats.url = (options?: RouteQueryOptions) => {
    return stats.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:49
* @route '/api/v1/listings/stats'
*/
stats.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: stats.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:49
* @route '/api/v1/listings/stats'
*/
stats.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: stats.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:49
* @route '/api/v1/listings/stats'
*/
const statsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stats.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:49
* @route '/api/v1/listings/stats'
*/
statsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stats.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:49
* @route '/api/v1/listings/stats'
*/
statsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stats.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

stats.form = statsForm

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:74
* @route '/api/v1/listings/summary'
*/
export const summary = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: summary.url(options),
    method: 'get',
})

summary.definition = {
    methods: ["get","head"],
    url: '/api/v1/listings/summary',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:74
* @route '/api/v1/listings/summary'
*/
summary.url = (options?: RouteQueryOptions) => {
    return summary.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:74
* @route '/api/v1/listings/summary'
*/
summary.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: summary.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:74
* @route '/api/v1/listings/summary'
*/
summary.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: summary.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:74
* @route '/api/v1/listings/summary'
*/
const summaryForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: summary.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:74
* @route '/api/v1/listings/summary'
*/
summaryForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: summary.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:74
* @route '/api/v1/listings/summary'
*/
summaryForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: summary.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

summary.form = summaryForm

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:96
* @route '/api/v1/listings/yield'
*/
export const yieldMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: yieldMethod.url(options),
    method: 'get',
})

yieldMethod.definition = {
    methods: ["get","head"],
    url: '/api/v1/listings/yield',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:96
* @route '/api/v1/listings/yield'
*/
yieldMethod.url = (options?: RouteQueryOptions) => {
    return yieldMethod.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:96
* @route '/api/v1/listings/yield'
*/
yieldMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: yieldMethod.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:96
* @route '/api/v1/listings/yield'
*/
yieldMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: yieldMethod.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:96
* @route '/api/v1/listings/yield'
*/
const yieldMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: yieldMethod.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:96
* @route '/api/v1/listings/yield'
*/
yieldMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: yieldMethod.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:96
* @route '/api/v1/listings/yield'
*/
yieldMethodForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: yieldMethod.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

yieldMethod.form = yieldMethodForm

const ListingController = { index, stats, summary, yieldMethod, yield: yieldMethod }

export default ListingController