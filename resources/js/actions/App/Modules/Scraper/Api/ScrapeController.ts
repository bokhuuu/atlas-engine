import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Modules\Scraper\Api\ScrapeController::start
* @see app/Modules/Scraper/Api/ScrapeController.php:38
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
* @see app/Modules/Scraper/Api/ScrapeController.php:38
* @route '/api/v1/scrape/start'
*/
start.url = (options?: RouteQueryOptions) => {
    return start.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::start
* @see app/Modules/Scraper/Api/ScrapeController.php:38
* @route '/api/v1/scrape/start'
*/
start.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: start.url(options),
    method: 'post',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::start
* @see app/Modules/Scraper/Api/ScrapeController.php:38
* @route '/api/v1/scrape/start'
*/
const startForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: start.url(options),
    method: 'post',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::start
* @see app/Modules/Scraper/Api/ScrapeController.php:38
* @route '/api/v1/scrape/start'
*/
startForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: start.url(options),
    method: 'post',
})

start.form = startForm

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:61
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
* @see app/Modules/Scraper/Api/ScrapeController.php:61
* @route '/api/v1/scrape/status'
*/
status.url = (options?: RouteQueryOptions) => {
    return status.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:61
* @route '/api/v1/scrape/status'
*/
status.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:61
* @route '/api/v1/scrape/status'
*/
status.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: status.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:61
* @route '/api/v1/scrape/status'
*/
const statusForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: status.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:61
* @route '/api/v1/scrape/status'
*/
statusForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: status.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::status
* @see app/Modules/Scraper/Api/ScrapeController.php:61
* @route '/api/v1/scrape/status'
*/
statusForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: status.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

status.form = statusForm

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancel
* @see app/Modules/Scraper/Api/ScrapeController.php:84
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
* @see app/Modules/Scraper/Api/ScrapeController.php:84
* @route '/api/v1/scrape/cancel'
*/
cancel.url = (options?: RouteQueryOptions) => {
    return cancel.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancel
* @see app/Modules/Scraper/Api/ScrapeController.php:84
* @route '/api/v1/scrape/cancel'
*/
cancel.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cancel.url(options),
    method: 'post',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancel
* @see app/Modules/Scraper/Api/ScrapeController.php:84
* @route '/api/v1/scrape/cancel'
*/
const cancelForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cancel.url(options),
    method: 'post',
})

/**
* @see \App\Modules\Scraper\Api\ScrapeController::cancel
* @see app/Modules/Scraper/Api/ScrapeController.php:84
* @route '/api/v1/scrape/cancel'
*/
cancelForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cancel.url(options),
    method: 'post',
})

cancel.form = cancelForm

const ScrapeController = { start, status, cancel }

export default ScrapeController