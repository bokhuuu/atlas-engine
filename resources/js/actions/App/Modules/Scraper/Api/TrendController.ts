import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\Scraper\Api\TrendController::volume
* @see app/Modules/Scraper/Api/TrendController.php:41
* @route '/api/v1/trends/volume'
*/
export const volume = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: volume.url(options),
    method: 'get',
})

volume.definition = {
    methods: ["get","head"],
    url: '/api/v1/trends/volume',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\TrendController::volume
* @see app/Modules/Scraper/Api/TrendController.php:41
* @route '/api/v1/trends/volume'
*/
volume.url = (options?: RouteQueryOptions) => {
    return volume.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\TrendController::volume
* @see app/Modules/Scraper/Api/TrendController.php:41
* @route '/api/v1/trends/volume'
*/
volume.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: volume.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\TrendController::volume
* @see app/Modules/Scraper/Api/TrendController.php:41
* @route '/api/v1/trends/volume'
*/
volume.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: volume.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\TrendController::volume
* @see app/Modules/Scraper/Api/TrendController.php:41
* @route '/api/v1/trends/volume'
*/
const volumeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: volume.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\TrendController::volume
* @see app/Modules/Scraper/Api/TrendController.php:41
* @route '/api/v1/trends/volume'
*/
volumeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: volume.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\TrendController::volume
* @see app/Modules/Scraper/Api/TrendController.php:41
* @route '/api/v1/trends/volume'
*/
volumeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: volume.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

volume.form = volumeForm

/**
* @see \App\Modules\Scraper\Api\TrendController::price
* @see app/Modules/Scraper/Api/TrendController.php:88
* @route '/api/v1/trends/price'
*/
export const price = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: price.url(options),
    method: 'get',
})

price.definition = {
    methods: ["get","head"],
    url: '/api/v1/trends/price',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\TrendController::price
* @see app/Modules/Scraper/Api/TrendController.php:88
* @route '/api/v1/trends/price'
*/
price.url = (options?: RouteQueryOptions) => {
    return price.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\TrendController::price
* @see app/Modules/Scraper/Api/TrendController.php:88
* @route '/api/v1/trends/price'
*/
price.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: price.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\TrendController::price
* @see app/Modules/Scraper/Api/TrendController.php:88
* @route '/api/v1/trends/price'
*/
price.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: price.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\TrendController::price
* @see app/Modules/Scraper/Api/TrendController.php:88
* @route '/api/v1/trends/price'
*/
const priceForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: price.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\TrendController::price
* @see app/Modules/Scraper/Api/TrendController.php:88
* @route '/api/v1/trends/price'
*/
priceForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: price.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\TrendController::price
* @see app/Modules/Scraper/Api/TrendController.php:88
* @route '/api/v1/trends/price'
*/
priceForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: price.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

price.form = priceForm

const TrendController = { volume, price }

export default TrendController