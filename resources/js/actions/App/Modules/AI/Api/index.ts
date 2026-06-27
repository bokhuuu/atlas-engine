import HealthCheckController from './HealthCheckController'
import FeedbackController from './FeedbackController'
import ChatController from './ChatController'
import WebhookController from './WebhookController'

const Api = {
    HealthCheckController: Object.assign(HealthCheckController, HealthCheckController),
    FeedbackController: Object.assign(FeedbackController, FeedbackController),
    ChatController: Object.assign(ChatController, ChatController),
    WebhookController: Object.assign(WebhookController, WebhookController),
}

export default Api