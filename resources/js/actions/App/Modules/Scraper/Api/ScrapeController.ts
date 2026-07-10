import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\Scraper\Api\ScrapeController::start
* @see app/Modules/Scraper/Api/ScrapeController.php:54
* @route '/api/v1/scrape/start'
*/
export const start = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: start.url(options),
    method: 'post',
})

start.definition = {
    methods: ["post"],
    url: '/api/v1/scrape/start',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Modules\Scraper\Api\ScrapeController::start
* @see app/Modules/Scraper/Api/ScrapeController.php:54
* @route '/api/v1/scrape/start'
*/
start.url = (options?: RouteQueryOptions) => {
    return start.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::start
* @see app/Modules/Scraper/Api/ScrapeController.php:54
* @route '/api/v1/scrape/start'
*/
start.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: start.url(options),
    method: 'post',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:125
* @route '/api/v1/scrape/status'
*/
export const status = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(options),
    method: 'get',
})

status.definition = {
    methods: ["get","head"],
    url: '/api/v1/scrape/status',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:125
* @route '/api/v1/scrape/status'
*/
status.url = (options?: RouteQueryOptions) => {
    return status.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:125
* @route '/api/v1/scrape/status'
*/
status.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:125
* @route '/api/v1/scrape/status'
*/
status.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: status.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancel
* @see app/Modules/Scraper/Api/ScrapeController.php:228
* @route '/api/v1/scrape/cancel'
*/
export const cancel = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cancel.url(options),
    method: 'post',
})

cancel.definition = {
    methods: ["post"],
    url: '/api/v1/scrape/cancel',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancel
* @see app/Modules/Scraper/Api/ScrapeController.php:228
* @route '/api/v1/scrape/cancel'
*/
cancel.url = (options?: RouteQueryOptions) => {
    return cancel.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancel
* @see app/Modules/Scraper/Api/ScrapeController.php:228
* @route '/api/v1/scrape/cancel'
*/
cancel.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cancel.url(options),
    method: 'post',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::startAll
* @see app/Modules/Scraper/Api/ScrapeController.php:83
* @route '/api/v1/scrape/start-all'
*/
export const startAll = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: startAll.url(options),
    method: 'post',
})

startAll.definition = {
    methods: ["post"],
    url: '/api/v1/scrape/start-all',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Modules\Scraper\Api\ScrapeController::startAll
* @see app/Modules/Scraper/Api/ScrapeController.php:83
* @route '/api/v1/scrape/start-all'
*/
startAll.url = (options?: RouteQueryOptions) => {
    return startAll.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::startAll
* @see app/Modules/Scraper/Api/ScrapeController.php:83
* @route '/api/v1/scrape/start-all'
*/
startAll.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: startAll.url(options),
    method: 'post',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::statusAll
* @see app/Modules/Scraper/Api/ScrapeController.php:154
* @route '/api/v1/scrape/status-all'
*/
export const statusAll = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: statusAll.url(options),
    method: 'get',
})

statusAll.definition = {
    methods: ["get","head"],
    url: '/api/v1/scrape/status-all',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ScrapeController::statusAll
* @see app/Modules/Scraper/Api/ScrapeController.php:154
* @route '/api/v1/scrape/status-all'
*/
statusAll.url = (options?: RouteQueryOptions) => {
    return statusAll.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::statusAll
* @see app/Modules/Scraper/Api/ScrapeController.php:154
* @route '/api/v1/scrape/status-all'
*/
statusAll.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: statusAll.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::statusAll
* @see app/Modules/Scraper/Api/ScrapeController.php:154
* @route '/api/v1/scrape/status-all'
*/
statusAll.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: statusAll.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::trend
* @see app/Modules/Scraper/Api/ScrapeController.php:197
* @route '/api/v1/scrape/trend'
*/
export const trend = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trend.url(options),
    method: 'get',
})

trend.definition = {
    methods: ["get","head"],
    url: '/api/v1/scrape/trend',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ScrapeController::trend
* @see app/Modules/Scraper/Api/ScrapeController.php:197
* @route '/api/v1/scrape/trend'
*/
trend.url = (options?: RouteQueryOptions) => {
    return trend.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::trend
* @see app/Modules/Scraper/Api/ScrapeController.php:197
* @route '/api/v1/scrape/trend'
*/
trend.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trend.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::trend
* @see app/Modules/Scraper/Api/ScrapeController.php:197
* @route '/api/v1/scrape/trend'
*/
trend.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: trend.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancelAll
* @see app/Modules/Scraper/Api/ScrapeController.php:248
* @route '/api/v1/scrape/cancel-all'
*/
export const cancelAll = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cancelAll.url(options),
    method: 'post',
})

cancelAll.definition = {
    methods: ["post"],
    url: '/api/v1/scrape/cancel-all',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancelAll
* @see app/Modules/Scraper/Api/ScrapeController.php:248
* @route '/api/v1/scrape/cancel-all'
*/
cancelAll.url = (options?: RouteQueryOptions) => {
    return cancelAll.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancelAll
* @see app/Modules/Scraper/Api/ScrapeController.php:248
* @route '/api/v1/scrape/cancel-all'
*/
cancelAll.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cancelAll.url(options),
    method: 'post',
})

const ScrapeController = { start, status, cancel, startAll, statusAll, trend, cancelAll }

export default ScrapeController