import DashboardController from './DashboardController'
import MarketStatsController from './MarketStatsController'
import AssistantController from './AssistantController'
import UserController from './UserController'
import SettingController from './SettingController'
import RoleController from './RoleController'
import ActivityLogController from './ActivityLogController'
import NotificationController from './NotificationController'
import TokenController from './TokenController'
import SystemHealthController from './SystemHealthController'
import AiFeedbackController from './AiFeedbackController'

const Admin = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    MarketStatsController: Object.assign(MarketStatsController, MarketStatsController),
    AssistantController: Object.assign(AssistantController, AssistantController),
    UserController: Object.assign(UserController, UserController),
    SettingController: Object.assign(SettingController, SettingController),
    RoleController: Object.assign(RoleController, RoleController),
    ActivityLogController: Object.assign(ActivityLogController, ActivityLogController),
    NotificationController: Object.assign(NotificationController, NotificationController),
    TokenController: Object.assign(TokenController, TokenController),
    SystemHealthController: Object.assign(SystemHealthController, SystemHealthController),
    AiFeedbackController: Object.assign(AiFeedbackController, AiFeedbackController),
}

export default Admin