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
const download534ae370af8f48177af87a435c6b19de = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download534ae370af8f48177af87a435c6b19de.url(args, options),
    method: 'get',
})

download534ae370af8f48177af87a435c6b19de.definition = {
    methods: ["get","head"],
    url: '/api/v1/exports/{source}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
download534ae370af8f48177af87a435c6b19de.url = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return download534ae370af8f48177af87a435c6b19de.definition.url
            .replace('{source}', parsedArgs.source.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
download534ae370af8f48177af87a435c6b19de.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download534ae370af8f48177af87a435c6b19de.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
download534ae370af8f48177af87a435c6b19de.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download534ae370af8f48177af87a435c6b19de.url(args, options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
const download534ae370af8f48177af87a435c6b19deForm = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download534ae370af8f48177af87a435c6b19de.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
download534ae370af8f48177af87a435c6b19deForm.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download534ae370af8f48177af87a435c6b19de.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/api/v1/exports/{source}'
*/
download534ae370af8f48177af87a435c6b19deForm.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download534ae370af8f48177af87a435c6b19de.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

download534ae370af8f48177af87a435c6b19de.form = download534ae370af8f48177af87a435c6b19deForm
/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/admin/exports/{source}'
*/
const download3f82479f52863b7797be4a7fdbf508ba = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download3f82479f52863b7797be4a7fdbf508ba.url(args, options),
    method: 'get',
})

download3f82479f52863b7797be4a7fdbf508ba.definition = {
    methods: ["get","head"],
    url: '/admin/exports/{source}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/admin/exports/{source}'
*/
download3f82479f52863b7797be4a7fdbf508ba.url = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return download3f82479f52863b7797be4a7fdbf508ba.definition.url
            .replace('{source}', parsedArgs.source.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/admin/exports/{source}'
*/
download3f82479f52863b7797be4a7fdbf508ba.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download3f82479f52863b7797be4a7fdbf508ba.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/admin/exports/{source}'
*/
download3f82479f52863b7797be4a7fdbf508ba.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download3f82479f52863b7797be4a7fdbf508ba.url(args, options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/admin/exports/{source}'
*/
const download3f82479f52863b7797be4a7fdbf508baForm = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download3f82479f52863b7797be4a7fdbf508ba.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/admin/exports/{source}'
*/
download3f82479f52863b7797be4a7fdbf508baForm.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download3f82479f52863b7797be4a7fdbf508ba.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:30
* @route '/admin/exports/{source}'
*/
download3f82479f52863b7797be4a7fdbf508baForm.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download3f82479f52863b7797be4a7fdbf508ba.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

download3f82479f52863b7797be4a7fdbf508ba.form = download3f82479f52863b7797be4a7fdbf508baForm

export const download = {
    '/api/v1/exports/{source}': download534ae370af8f48177af87a435c6b19de,
    '/admin/exports/{source}': download3f82479f52863b7797be4a7fdbf508ba,
}

const ExportController = { downloadAll, download }

export default ExportController