import HealthController from './HealthController'
import ScrapeController from './ScrapeController'
import ListingController from './ListingController'

const Api = {
    HealthController: Object.assign(HealthController, HealthController),
    ScrapeController: Object.assign(ScrapeController, ScrapeController),
    ListingController: Object.assign(ListingController, ListingController),
}

export default Api