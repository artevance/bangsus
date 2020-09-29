import Vue from 'vue'
import VueProgressBar from 'vue-progressbar'

export default function init() {
  Vue.use(VueProgressBar, {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
      speed: '0.2s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'left',
    inverse: false
  })  
}