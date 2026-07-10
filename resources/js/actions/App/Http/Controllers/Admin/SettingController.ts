import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/yerevan/admin/settings'
*/
const index9289cf673a8b339d823a882df2433bf2 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index9289cf673a8b339d823a882df2433bf2.url(options),
    method: 'get',
})

index9289cf673a8b339d823a882df2433bf2.definition = {
    methods: ["get","head"],
    url: '/yerevan/admin/settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/yerevan/admin/settings'
*/
index9289cf673a8b339d823a882df2433bf2.url = (options?: RouteQueryOptions) => {
    return index9289cf673a8b339d823a882df2433bf2.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/yerevan/admin/settings'
*/
index9289cf673a8b339d823a882df2433bf2.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index9289cf673a8b339d823a882df2433bf2.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/yerevan/admin/settings'
*/
index9289cf673a8b339d823a882df2433bf2.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index9289cf673a8b339d823a882df2433bf2.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/yerevan/admin/settings'
*/
const index9289cf673a8b339d823a882df2433bf2Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index9289cf673a8b339d823a882df2433bf2.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/yerevan/admin/settings'
*/
index9289cf673a8b339d823a882df2433bf2Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index9289cf673a8b339d823a882df2433bf2.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/yerevan/admin/settings'
*/
index9289cf673a8b339d823a882df2433bf2Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index9289cf673a8b339d823a882df2433bf2.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index9289cf673a8b339d823a882df2433bf2.form = index9289cf673a8b339d823a882df2433bf2Form
/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/baku/admin/settings'
*/
const index5bb35913e393f8dcb4e17ada81e58e2a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index5bb35913e393f8dcb4e17ada81e58e2a.url(options),
    method: 'get',
})

index5bb35913e393f8dcb4e17ada81e58e2a.definition = {
    methods: ["get","head"],
    url: '/baku/admin/settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/baku/admin/settings'
*/
index5bb35913e393f8dcb4e17ada81e58e2a.url = (options?: RouteQueryOptions) => {
    return index5bb35913e393f8dcb4e17ada81e58e2a.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/baku/admin/settings'
*/
index5bb35913e393f8dcb4e17ada81e58e2a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index5bb35913e393f8dcb4e17ada81e58e2a.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/baku/admin/settings'
*/
index5bb35913e393f8dcb4e17ada81e58e2a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index5bb35913e393f8dcb4e17ada81e58e2a.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/baku/admin/settings'
*/
const index5bb35913e393f8dcb4e17ada81e58e2aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index5bb35913e393f8dcb4e17ada81e58e2a.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/baku/admin/settings'
*/
index5bb35913e393f8dcb4e17ada81e58e2aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index5bb35913e393f8dcb4e17ada81e58e2a.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/baku/admin/settings'
*/
index5bb35913e393f8dcb4e17ada81e58e2aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index5bb35913e393f8dcb4e17ada81e58e2a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index5bb35913e393f8dcb4e17ada81e58e2a.form = index5bb35913e393f8dcb4e17ada81e58e2aForm
/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/tbilisi/admin/settings'
*/
const index1da272b2813c79a25805df08e951003d = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1da272b2813c79a25805df08e951003d.url(options),
    method: 'get',
})

index1da272b2813c79a25805df08e951003d.definition = {
    methods: ["get","head"],
    url: '/tbilisi/admin/settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/tbilisi/admin/settings'
*/
index1da272b2813c79a25805df08e951003d.url = (options?: RouteQueryOptions) => {
    return index1da272b2813c79a25805df08e951003d.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/tbilisi/admin/settings'
*/
index1da272b2813c79a25805df08e951003d.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1da272b2813c79a25805df08e951003d.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/tbilisi/admin/settings'
*/
index1da272b2813c79a25805df08e951003d.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index1da272b2813c79a25805df08e951003d.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/tbilisi/admin/settings'
*/
const index1da272b2813c79a25805df08e951003dForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index1da272b2813c79a25805df08e951003d.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/tbilisi/admin/settings'
*/
index1da272b2813c79a25805df08e951003dForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index1da272b2813c79a25805df08e951003d.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::index
* @see app/Http/Controllers/Admin/SettingController.php:30
* @route '/tbilisi/admin/settings'
*/
index1da272b2813c79a25805df08e951003dForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index1da272b2813c79a25805df08e951003d.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index1da272b2813c79a25805df08e951003d.form = index1da272b2813c79a25805df08e951003dForm

export const index = {
    '/yerevan/admin/settings': index9289cf673a8b339d823a882df2433bf2,
    '/baku/admin/settings': index5bb35913e393f8dcb4e17ada81e58e2a,
    '/tbilisi/admin/settings': index1da272b2813c79a25805df08e951003d,
}

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/yerevan/admin/settings'
*/
const update9289cf673a8b339d823a882df2433bf2 = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update9289cf673a8b339d823a882df2433bf2.url(options),
    method: 'put',
})

update9289cf673a8b339d823a882df2433bf2.definition = {
    methods: ["put"],
    url: '/yerevan/admin/settings',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/yerevan/admin/settings'
*/
update9289cf673a8b339d823a882df2433bf2.url = (options?: RouteQueryOptions) => {
    return update9289cf673a8b339d823a882df2433bf2.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/yerevan/admin/settings'
*/
update9289cf673a8b339d823a882df2433bf2.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update9289cf673a8b339d823a882df2433bf2.url(options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/yerevan/admin/settings'
*/
const update9289cf673a8b339d823a882df2433bf2Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update9289cf673a8b339d823a882df2433bf2.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/yerevan/admin/settings'
*/
update9289cf673a8b339d823a882df2433bf2Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update9289cf673a8b339d823a882df2433bf2.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update9289cf673a8b339d823a882df2433bf2.form = update9289cf673a8b339d823a882df2433bf2Form
/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/baku/admin/settings'
*/
const update5bb35913e393f8dcb4e17ada81e58e2a = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update5bb35913e393f8dcb4e17ada81e58e2a.url(options),
    method: 'put',
})

update5bb35913e393f8dcb4e17ada81e58e2a.definition = {
    methods: ["put"],
    url: '/baku/admin/settings',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/baku/admin/settings'
*/
update5bb35913e393f8dcb4e17ada81e58e2a.url = (options?: RouteQueryOptions) => {
    return update5bb35913e393f8dcb4e17ada81e58e2a.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/baku/admin/settings'
*/
update5bb35913e393f8dcb4e17ada81e58e2a.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update5bb35913e393f8dcb4e17ada81e58e2a.url(options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/baku/admin/settings'
*/
const update5bb35913e393f8dcb4e17ada81e58e2aForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update5bb35913e393f8dcb4e17ada81e58e2a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/baku/admin/settings'
*/
update5bb35913e393f8dcb4e17ada81e58e2aForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update5bb35913e393f8dcb4e17ada81e58e2a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update5bb35913e393f8dcb4e17ada81e58e2a.form = update5bb35913e393f8dcb4e17ada81e58e2aForm
/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/tbilisi/admin/settings'
*/
const update1da272b2813c79a25805df08e951003d = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update1da272b2813c79a25805df08e951003d.url(options),
    method: 'put',
})

update1da272b2813c79a25805df08e951003d.definition = {
    methods: ["put"],
    url: '/tbilisi/admin/settings',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/tbilisi/admin/settings'
*/
update1da272b2813c79a25805df08e951003d.url = (options?: RouteQueryOptions) => {
    return update1da272b2813c79a25805df08e951003d.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/tbilisi/admin/settings'
*/
update1da272b2813c79a25805df08e951003d.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update1da272b2813c79a25805df08e951003d.url(options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/tbilisi/admin/settings'
*/
const update1da272b2813c79a25805df08e951003dForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update1da272b2813c79a25805df08e951003d.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\SettingController::update
* @see app/Http/Controllers/Admin/SettingController.php:57
* @route '/tbilisi/admin/settings'
*/
update1da272b2813c79a25805df08e951003dForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update1da272b2813c79a25805df08e951003d.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update1da272b2813c79a25805df08e951003d.form = update1da272b2813c79a25805df08e951003dForm

export const update = {
    '/yerevan/admin/settings': update9289cf673a8b339d823a882df2433bf2,
    '/baku/admin/settings': update5bb35913e393f8dcb4e17ada81e58e2a,
    '/tbilisi/admin/settings': update1da272b2813c79a25805df08e951003d,
}

const SettingController = { index, update }

export default SettingController