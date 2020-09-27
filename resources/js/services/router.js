import Vue from 'vue'
import VueRouter from 'vue-router'
import Multiguard from 'vue-router-multiguard'
import middleware from './middleware.js'

Vue.use(VueRouter)

const routes = [
  {
    path: '/app',
    component: { template: `<router-view></router-view>`},
    redirect: '/app/dashboard',
    children: [
      { path: '/', redirect: { name: 'dashboard' } },
      { path: 'login', name: 'login', component: require('../views/Login').default, meta: { layout: 'plain', title: 'Login' } },
      { path: 'logout', name: 'logout', component: require('../views/Logout').default, meta: { layout: 'plain', title: 'Logout' } },

      {
        path: 'dashboard',
        name: 'dashboard',
        component: { template: `<router-view></router-view>` },
        meta: { layout: 'default', title: 'Dashboard', sidebar: 'dashboard' },
        beforeEnter: Multiguard([middleware.auth, middleware.access])
      },
      {
        path: 'master',
        component: { template: `<router-view></router-view>` },
        redirect: { name: 'master.tipeKontak' },
        children: [
          { path: 'tipe_kontak', name: 'master.tipeKontak', component: require('../views/master/TipeKontak').default, meta: { layout: 'default', title: 'Tipe Kontak', sidebar: 'master', item: 'tipeKontak' } }
        ]
      }
    ]
  },
]

export default new VueRouter({
  mode: 'history',
  routes
})