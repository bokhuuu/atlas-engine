import admin from './admin'

const global = {
    admin: Object.assign(admin, admin),
}

export default global