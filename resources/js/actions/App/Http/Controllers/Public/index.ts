import SnapshotController from './SnapshotController'
import OverviewController from './OverviewController'

const Public = {
    SnapshotController: Object.assign(SnapshotController, SnapshotController),
    OverviewController: Object.assign(OverviewController, OverviewController),
}

export default Public