import Scraper from './Scraper'
import AI from './AI'

const Modules = {
    Scraper: Object.assign(Scraper, Scraper),
    AI: Object.assign(AI, AI),
}

export default Modules