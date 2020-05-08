import Vue from 'vue'
import instantiate from './instantiate.js'

/* Components */
import JoRecordingStatus from './components/recordingStatus/JoRecordingStatus'

/* CSS */
import '../assets/css/admin.scss'

/* Options */
Vue.config.productionTip = false
Vue.prototype.$joel = Object.assign(
  { templatePath: '', assetPath: '' },
  window._joel,
)

/* Instantiate Components */
instantiate.component('JoRecordingStatus', JoRecordingStatus)
