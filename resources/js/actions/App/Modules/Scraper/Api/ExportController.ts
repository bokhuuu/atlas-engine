import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:49
* @route '/api/v1/exports'
*/
export const downloadAll = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadAll.url(options),
    method: 'get',
})

downloadAll.definition = {
    methods: ["get","head"],
    url: '/api/v1/exports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:49
* @route '/api/v1/exports'
*/
downloadAll.url = (options?: RouteQueryOptions) => {
    return downloadAll.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:49
* @route '/api/v1/exports'
*/
downloadAll.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadAll.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:49
* @route '/api/v1/exports'
*/
downloadAll.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadAll.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:49
* @route '/api/v1/exports'
*/
const downloadAllForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadAll.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:49
* @route '/api/v1/exports'
*/
downloadAllForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadAll.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:49
* @route '/api/v1/exports'
*/
downloadAllForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadAll.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

downloadAll.form = downloadAllForm

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
export const download = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/api/v1/exports/{source}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
download.url = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { source: args }
    }

    if (Array.isArray(args)) {
        args = {
            source: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        source: args.source,
    }

    return download.definition.url
            .replace('{source}', parsedArgs.source.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
download.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
download.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
const downloadForm = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
downloadForm.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
downloadForm.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

download.form = downloadForm

const ExportController = { downloadAll, download }

export default ExportController