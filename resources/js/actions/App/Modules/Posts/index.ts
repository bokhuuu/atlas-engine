import Api from './Api'
import Controllers from './Controllers'

const Posts = {
    Api: Object.assign(Api, Api),
    Controllers: Object.assign(Controllers, Controllers),
}

export default Posts