import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\Scraper\Api\ListingController::filter
* @see app/Modules/Scraper/Api/ListingController.php:164
* @route '/api/v1/listings/filter'
*/
export const filter = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: filter.url(options),
    method: 'get',
})

filter.definition = {
    methods: ["get","head"],
    url: '/api/v1/listings/filter',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ListingController::filter
* @see app/Modules/Scraper/Api/ListingController.php:164
* @route '/api/v1/listings/filter'
*/
filter.url = (options?: RouteQueryOptions) => {
    return filter.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::filter
* @see app/Modules/Scraper/Api/ListingController.php:164
* @route '/api/v1/listings/filter'
*/
filter.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: filter.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::filter
* @see app/Modules/Scraper/Api/ListingController.php:164
* @route '/api/v1/listings/filter'
*/
filter.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: filter.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:33
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
* @see app/Modules/Scraper/Api/ListingController.php:33
* @route '/api/v1/listings'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:33
* @route '/api/v1/listings'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:33
* @route '/api/v1/listings'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:54
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
* @see app/Modules/Scraper/Api/ListingController.php:54
* @route '/api/v1/listings/stats'
*/
stats.url = (options?: RouteQueryOptions) => {
    return stats.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:54
* @route '/api/v1/listings/stats'
*/
stats.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: stats.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:54
* @route '/api/v1/listings/stats'
*/
stats.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: stats.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:86
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
* @see app/Modules/Scraper/Api/ListingController.php:86
* @route '/api/v1/listings/summary'
*/
summary.url = (options?: RouteQueryOptions) => {
    return summary.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:86
* @route '/api/v1/listings/summary'
*/
summary.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: summary.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::summary
* @see app/Modules/Scraper/Api/ListingController.php:86
* @route '/api/v1/listings/summary'
*/
summary.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: summary.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:108
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
* @see app/Modules/Scraper/Api/ListingController.php:108
* @route '/api/v1/listings/yield'
*/
yieldMethod.url = (options?: RouteQueryOptions) => {
    return yieldMethod.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:108
* @route '/api/v1/listings/yield'
*/
yieldMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: yieldMethod.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::yieldMethod
* @see app/Modules/Scraper/Api/ListingController.php:108
* @route '/api/v1/listings/yield'
*/
yieldMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: yieldMethod.url(options),
    method: 'head',
})

const ListingController = { filter, index, stats, summary, yieldMethod, yield: yieldMethod }

export default ListingController