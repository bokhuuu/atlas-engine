import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/yerevan/admin/system-health'
*/
const index68dfa0b605d3e482e342013ae6926e04 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index68dfa0b605d3e482e342013ae6926e04.url(options),
    method: 'get',
})

index68dfa0b605d3e482e342013ae6926e04.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/system-health',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/yerevan/admin/system-health'
*/
index68dfa0b605d3e482e342013ae6926e04.url = (options?: RouteQueryOptions) => {
    return index68dfa0b605d3e482e342013ae6926e04.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/yerevan/admin/system-health'
*/
index68dfa0b605d3e482e342013ae6926e04.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index68dfa0b605d3e482e342013ae6926e04.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/yerevan/admin/system-health'
*/
index68dfa0b605d3e482e342013ae6926e04.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index68dfa0b605d3e482e342013ae6926e04.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/baku/admin/system-health'
*/
const indexb5716b69222db923d42b6c16a9f164c2 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexb5716b69222db923d42b6c16a9f164c2.url(options),
    method: 'get',
})

indexb5716b69222db923d42b6c16a9f164c2.definition = {
    methods: ["get","head"],
    url: '/baku/admin/system-health',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/baku/admin/system-health'
*/
indexb5716b69222db923d42b6c16a9f164c2.url = (options?: RouteQueryOptions) => {
    return indexb5716b69222db923d42b6c16a9f164c2.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/baku/admin/system-health'
*/
indexb5716b69222db923d42b6c16a9f164c2.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexb5716b69222db923d42b6c16a9f164c2.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/baku/admin/system-health'
*/
indexb5716b69222db923d42b6c16a9f164c2.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexb5716b69222db923d42b6c16a9f164c2.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/tbilisi/admin/system-health'
*/
const index6e5819aea45fd3082263954b7d0d2aec = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index6e5819aea45fd3082263954b7d0d2aec.url(options),
    method: 'get',
})

index6e5819aea45fd3082263954b7d0d2aec.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/system-health',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/tbilisi/admin/system-health'
*/
index6e5819aea45fd3082263954b7d0d2aec.url = (options?: RouteQueryOptions) => {
    return index6e5819aea45fd3082263954b7d0d2aec.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/tbilisi/admin/system-health'
*/
index6e5819aea45fd3082263954b7d0d2aec.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index6e5819aea45fd3082263954b7d0d2aec.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::index
* @see app/Http/Controllers/Admin/SystemHealthController.php:30
* @route '/tbilisi/admin/system-health'
*/
index6e5819aea45fd3082263954b7d0d2aec.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index6e5819aea45fd3082263954b7d0d2aec.url(options),
    method: 'head',
})

export const index = {
    '/yerevan/admin/system-health': index68dfa0b605d3e482e342013ae6926e04,
    '/baku/admin/system-health': indexb5716b69222db923d42b6c16a9f164c2,
    '/tbilisi/admin/system-health': index6e5819aea45fd3082263954b7d0d2aec,
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clearCache
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/yerevan/admin/system-health/cache'
*/
const clearCache3167365731acaf1fb80f64f51360464c = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearCache3167365731acaf1fb80f64f51360464c.url(options),
    method: 'post',
})

clearCache3167365731acaf1fb80f64f51360464c.definition = {
    methods: ["post"],
    url: '/yerevan/admin/system-health/cache',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clearCache
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/yerevan/admin/system-health/cache'
*/
clearCache3167365731acaf1fb80f64f51360464c.url = (options?: RouteQueryOptions) => {
    return clearCache3167365731acaf1fb80f64f51360464c.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clearCache
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/yerevan/admin/system-health/cache'
*/
clearCache3167365731acaf1fb80f64f51360464c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearCache3167365731acaf1fb80f64f51360464c.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clearCache
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/baku/admin/system-health/cache'
*/
const clearCache9a6df52fa3ce951438bb3fe78a967c13 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearCache9a6df52fa3ce951438bb3fe78a967c13.url(options),
    method: 'post',
})

clearCache9a6df52fa3ce951438bb3fe78a967c13.definition = {
    methods: ["post"],
    url: '/baku/admin/system-health/cache',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clearCache
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/baku/admin/system-health/cache'
*/
clearCache9a6df52fa3ce951438bb3fe78a967c13.url = (options?: RouteQueryOptions) => {
    return clearCache9a6df52fa3ce951438bb3fe78a967c13.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clearCache
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/baku/admin/system-health/cache'
*/
clearCache9a6df52fa3ce951438bb3fe78a967c13.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearCache9a6df52fa3ce951438bb3fe78a967c13.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clearCache
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/tbilisi/admin/system-health/cache'
*/
const clearCache8595f3d9de01d43000b6d33a0db31e81 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearCache8595f3d9de01d43000b6d33a0db31e81.url(options),
    method: 'post',
})

clearCache8595f3d9de01d43000b6d33a0db31e81.definition = {
    methods: ["post"],
    url: '/tbilisi/admin/system-health/cache',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clearCache
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/tbilisi/admin/system-health/cache'
*/
clearCache8595f3d9de01d43000b6d33a0db31e81.url = (options?: RouteQueryOptions) => {
    return clearCache8595f3d9de01d43000b6d33a0db31e81.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::clearCache
* @see app/Http/Controllers/Admin/SystemHealthController.php:48
* @route '/tbilisi/admin/system-health/cache'
*/
clearCache8595f3d9de01d43000b6d33a0db31e81.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearCache8595f3d9de01d43000b6d33a0db31e81.url(options),
    method: 'post',
})

export const clearCache = {
    '/yerevan/admin/system-health/cache': clearCache3167365731acaf1fb80f64f51360464c,
    '/baku/admin/system-health/cache': clearCache9a6df52fa3ce951438bb3fe78a967c13,
    '/tbilisi/admin/system-health/cache': clearCache8595f3d9de01d43000b6d33a0db31e81,
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::toggleMaintenance
* @see app/Http/Controllers/Admin/SystemHealthController.php:69
* @route '/yerevan/admin/system-health/maintenance'
*/
const toggleMaintenance0c6a1d9fc148dbe38e14323d798c1d89 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleMaintenance0c6a1d9fc148dbe38e14323d798c1d89.url(options),
    method: 'post',
})

toggleMaintenance0c6a1d9fc148dbe38e14323d798c1d89.definition = {
    methods: ["post"],
    url: '/yerevan/admin/system-health/maintenance',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::toggleMaintenance
* @see app/Http/Controllers/Admin/SystemHealthController.php:69
* @route '/yerevan/admin/system-health/maintenance'
*/
toggleMaintenance0c6a1d9fc148dbe38e14323d798c1d89.url = (options?: RouteQueryOptions) => {
    return toggleMaintenance0c6a1d9fc148dbe38e14323d798c1d89.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::toggleMaintenance
* @see app/Http/Controllers/Admin/SystemHealthController.php:69
* @route '/yerevan/admin/system-health/maintenance'
*/
toggleMaintenance0c6a1d9fc148dbe38e14323d798c1d89.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleMaintenance0c6a1d9fc148dbe38e14323d798c1d89.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::toggleMaintenance
* @see app/Http/Controllers/Admin/SystemHealthController.php:69
* @route '/baku/admin/system-health/maintenance'
*/
const toggleMaintenancee18ef9302e2d5d92ef7153dd30bf961a = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleMaintenancee18ef9302e2d5d92ef7153dd30bf961a.url(options),
    method: 'post',
})

toggleMaintenancee18ef9302e2d5d92ef7153dd30bf961a.definition = {
    methods: ["post"],
    url: '/baku/admin/system-health/maintenance',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::toggleMaintenance
* @see app/Http/Controllers/Admin/SystemHealthController.php:69
* @route '/baku/admin/system-health/maintenance'
*/
toggleMaintenancee18ef9302e2d5d92ef7153dd30bf961a.url = (options?: RouteQueryOptions) => {
    return toggleMaintenancee18ef9302e2d5d92ef7153dd30bf961a.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::toggleMaintenance
* @see app/Http/Controllers/Admin/SystemHealthController.php:69
* @route '/baku/admin/system-health/maintenance'
*/
toggleMaintenancee18ef9302e2d5d92ef7153dd30bf961a.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleMaintenancee18ef9302e2d5d92ef7153dd30bf961a.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::toggleMaintenance
* @see app/Http/Controllers/Admin/SystemHealthController.php:69
* @route '/tbilisi/admin/system-health/maintenance'
*/
const toggleMaintenance78f38cfd8010b7e6f6e7ae55c86a3e51 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleMaintenance78f38cfd8010b7e6f6e7ae55c86a3e51.url(options),
    method: 'post',
})

toggleMaintenance78f38cfd8010b7e6f6e7ae55c86a3e51.definition = {
    methods: ["post"],
    url: '/tbilisi/admin/system-health/maintenance',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::toggleMaintenance
* @see app/Http/Controllers/Admin/SystemHealthController.php:69
* @route '/tbilisi/admin/system-health/maintenance'
*/
toggleMaintenance78f38cfd8010b7e6f6e7ae55c86a3e51.url = (options?: RouteQueryOptions) => {
    return toggleMaintenance78f38cfd8010b7e6f6e7ae55c86a3e51.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SystemHealthController::toggleMaintenance
* @see app/Http/Controllers/Admin/SystemHealthController.php:69
* @route '/tbilisi/admin/system-health/maintenance'
*/
toggleMaintenance78f38cfd8010b7e6f6e7ae55c86a3e51.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleMaintenance78f38cfd8010b7e6f6e7ae55c86a3e51.url(options),
    method: 'post',
})

export const toggleMaintenance = {
    '/yerevan/admin/system-health/maintenance': toggleMaintenance0c6a1d9fc148dbe38e14323d798c1d89,
    '/baku/admin/system-health/maintenance': toggleMaintenancee18ef9302e2d5d92ef7153dd30bf961a,
    '/tbilisi/admin/system-health/maintenance': toggleMaintenance78f38cfd8010b7e6f6e7ae55c86a3e51,
}

const SystemHealthController = { index, clearCache, toggleMaintenance }

export default SystemHealthController