import HealthController from './HealthController'
import ListingController from './ListingController'
import ExportController from './ExportController'
import ScrapeController from './ScrapeController'
import TrendController from './TrendController'

const Api = {
    HealthController: Object.assign(HealthController, HealthController),
    ListingController: Object.assign(ListingController, ListingController),
    ExportController: Object.assign(ExportController, ExportController),
    ScrapeController: Object.assign(ScrapeController, ScrapeController),
    TrendController: Object.assign(TrendController, TrendController),
}

export default Api