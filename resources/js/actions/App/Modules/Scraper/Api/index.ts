import HealthController from './HealthController'
import ScrapeController from './ScrapeController'
import ListingController from './ListingController'
import ExportController from './ExportController'

const Api = {
    HealthController: Object.assign(HealthController, HealthController),
    ScrapeController: Object.assign(ScrapeController, ScrapeController),
    ListingController: Object.assign(ListingController, ListingController),
    ExportController: Object.assign(ExportController, ExportController),
}

export default Api