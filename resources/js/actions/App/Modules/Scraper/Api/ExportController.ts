import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Modules\Scraper\Api\ExportController::downloadFiltered
* @see app/Modules/Scraper/Api/ExportController.php:104
* @route '/api/v1/exports/filtered'
*/
export const downloadFiltered = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadFiltered.url(options),
    method: 'get',
})

downloadFiltered.definition = {
    methods: ["get","head"],
    url: '/api/v1/exports/filtered',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadFiltered
* @see app/Modules/Scraper/Api/ExportController.php:104
* @route '/api/v1/exports/filtered'
*/
downloadFiltered.url = (options?: RouteQueryOptions) => {
    return downloadFiltered.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadFiltered
* @see app/Modules/Scraper/Api/ExportController.php:104
* @route '/api/v1/exports/filtered'
*/
downloadFiltered.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadFiltered.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadFiltered
* @see app/Modules/Scraper/Api/ExportController.php:104
* @route '/api/v1/exports/filtered'
*/
downloadFiltered.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadFiltered.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:69
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
* @see app/Modules/Scraper/Api/ExportController.php:69
* @route '/api/v1/exports'
*/
downloadAll.url = (options?: RouteQueryOptions) => {
    return downloadAll.definition.url + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:69
* @route '/api/v1/exports'
*/
downloadAll.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadAll.url(options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::downloadAll
* @see app/Modules/Scraper/Api/ExportController.php:69
* @route '/api/v1/exports'
*/
downloadAll.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadAll.url(options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
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
* @see app/Modules/Scraper/Api/ExportController.php:50
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
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/api/v1/exports/{source}'
*/
download534ae370af8f48177af87a435c6b19de.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download534ae370af8f48177af87a435c6b19de.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/api/v1/exports/{source}'
*/
download534ae370af8f48177af87a435c6b19de.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download534ae370af8f48177af87a435c6b19de.url(args, options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/yerevan/admin/exports/{source}'
*/
const downloadc0fcec01ff333ac18f3e8bf2e72cfb86 = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadc0fcec01ff333ac18f3e8bf2e72cfb86.url(args, options),
    method: 'get',
})

downloadc0fcec01ff333ac18f3e8bf2e72cfb86.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/exports/{source}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/yerevan/admin/exports/{source}'
*/
downloadc0fcec01ff333ac18f3e8bf2e72cfb86.url = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return downloadc0fcec01ff333ac18f3e8bf2e72cfb86.definition.url
            .replace('{source}', parsedArgs.source.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/yerevan/admin/exports/{source}'
*/
downloadc0fcec01ff333ac18f3e8bf2e72cfb86.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadc0fcec01ff333ac18f3e8bf2e72cfb86.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/yerevan/admin/exports/{source}'
*/
downloadc0fcec01ff333ac18f3e8bf2e72cfb86.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadc0fcec01ff333ac18f3e8bf2e72cfb86.url(args, options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/baku/admin/exports/{source}'
*/
const download5b9f9e5e9ab39f2853546039eb301c22 = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download5b9f9e5e9ab39f2853546039eb301c22.url(args, options),
    method: 'get',
})

download5b9f9e5e9ab39f2853546039eb301c22.definition = {
    methods: ["get","head"],
    url: '/baku/admin/exports/{source}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/baku/admin/exports/{source}'
*/
download5b9f9e5e9ab39f2853546039eb301c22.url = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return download5b9f9e5e9ab39f2853546039eb301c22.definition.url
            .replace('{source}', parsedArgs.source.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/baku/admin/exports/{source}'
*/
download5b9f9e5e9ab39f2853546039eb301c22.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download5b9f9e5e9ab39f2853546039eb301c22.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/baku/admin/exports/{source}'
*/
download5b9f9e5e9ab39f2853546039eb301c22.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download5b9f9e5e9ab39f2853546039eb301c22.url(args, options),
    method: 'head',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/tbilisi/admin/exports/{source}'
*/
const downloada0938e2d8836e05cafe569ad3d033247 = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloada0938e2d8836e05cafe569ad3d033247.url(args, options),
    method: 'get',
})

downloada0938e2d8836e05cafe569ad3d033247.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/exports/{source}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/tbilisi/admin/exports/{source}'
*/
downloada0938e2d8836e05cafe569ad3d033247.url = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return downloada0938e2d8836e05cafe569ad3d033247.definition.url
            .replace('{source}', parsedArgs.source.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/tbilisi/admin/exports/{source}'
*/
downloada0938e2d8836e05cafe569ad3d033247.get = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloada0938e2d8836e05cafe569ad3d033247.url(args, options),
    method: 'get',
})

/**
* @see \App\Modules\Scraper\Api\ExportController::download
* @see app/Modules/Scraper/Api/ExportController.php:50
* @route '/tbilisi/admin/exports/{source}'
*/
downloada0938e2d8836e05cafe569ad3d033247.head = (args: { source: string | number } | [source: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloada0938e2d8836e05cafe569ad3d033247.url(args, options),
    method: 'head',
})

export const download = {
    '/api/v1/exports/{source}': download534ae370af8f48177af87a435c6b19de,
    '/yerevan/admin/exports/{source}': downloadc0fcec01ff333ac18f3e8bf2e72cfb86,
    '/baku/admin/exports/{source}': download5b9f9e5e9ab39f2853546039eb301c22,
    '/tbilisi/admin/exports/{source}': downloada0938e2d8836e05cafe569ad3d033247,
}

const ExportController = { downloadFiltered, downloadAll, download }

export default ExportController