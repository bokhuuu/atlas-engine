import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:30
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
* @see app/Modules/Scraper/Api/ListingController.php:30
* @route '/api/v1/listings'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:30
* @route '/api/v1/listings'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:30
* @route '/api/v1/listings'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:30
* @route '/api/v1/listings'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:30
* @route '/api/v1/listings'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::index
* @see app/Modules/Scraper/Api/ListingController.php:30
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
* @see app/Modules/Scraper/Api/ListingController.php:48
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
* @see app/Modules/Scraper/Api/ListingController.php:48
* @route '/api/v1/listings/stats'
*/
stats.url = (options?: RouteQueryOptions) => {
    return stats.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:48
* @route '/api/v1/listings/stats'
*/
stats.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: stats.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:48
* @route '/api/v1/listings/stats'
*/
stats.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: stats.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:48
* @route '/api/v1/listings/stats'
*/
const statsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stats.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:48
* @route '/api/v1/listings/stats'
*/
statsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stats.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ListingController::stats
* @see app/Modules/Scraper/Api/ListingController.php:48
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

const ListingController = { index, stats }

export default ListingController