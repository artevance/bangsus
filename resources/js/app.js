/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Importing services and other modules.
 */
import router from './services/router.js'
import store from './services/store.js'
import psstyle from 'perfect-scrollbar/css/perfect-scrollbar.css'

/**
 * Installing Vue prototype global variable.
 */
Vue.prototype.$axios = require('axios').default;
Vue.prototype.$moment = require('moment').default;


/**
 * Importing necessary mixins.
 */
Vue.mixin(require('./services/mixin.js').title)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Views
 */
Vue.component('root-view', require('./views/Root.vue').default);

/**
 * Layouts
 */
Vue.component('default-layout', require('./layouts/Default.vue').default);
Vue.component('plain-layout', require('./layouts/Plain.vue').default);

/**
 * Components
 */
Vue.component('sidebar-component', require('./components/Sidebar.vue').default);
Vue.component('navbar-component', require('./components/Navbar.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: '#app',
  router,
  store,
  psstyle
});
