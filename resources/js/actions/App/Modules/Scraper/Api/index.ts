import HealthController from './HealthController'
import ListingController from './ListingController'
import ExportController from './ExportController'
import ScrapeController from './ScrapeController'

const Api = {
    HealthController: Object.assign(HealthController, HealthController),
    ListingController: Object.assign(ListingController, ListingController),
    ExportController: Object.assign(ExportController, ExportController),
    ScrapeController: Object.assign(ScrapeController, ScrapeController),
}

export default Api