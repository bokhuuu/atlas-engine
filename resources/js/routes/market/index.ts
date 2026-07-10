import yerevan from './yerevan'
import baku from './baku'
import tbilisi from './tbilisi'

const market = {
    yerevan: Object.assign(yerevan, yerevan),
    baku: Object.assign(baku, baku),
    tbilisi: Object.assign(tbilisi, tbilisi),
}

export default market