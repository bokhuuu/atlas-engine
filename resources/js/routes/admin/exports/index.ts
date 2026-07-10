import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/yerevan/admin/exports/{source}'
*/
export const download = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/exports/{source}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/yerevan/admin/exports/{source}'
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
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/yerevan/admin/exports/{source}'
*/
download.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/yerevan/admin/exports/{source}'
*/
download.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/baku/admin/exports/{source}'
*/
export const download = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/baku/admin/exports/{source}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/baku/admin/exports/{source}'
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
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/baku/admin/exports/{source}'
*/
download.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/baku/admin/exports/{source}'
*/
download.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/tbilisi/admin/exports/{source}'
*/
export const download = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/exports/{source}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/tbilisi/admin/exports/{source}'
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
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/tbilisi/admin/exports/{source}'
*/
download.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/tbilisi/admin/exports/{source}'
*/
download.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

const exports = {
    download: Object.assign(download, download),
}

export default exports