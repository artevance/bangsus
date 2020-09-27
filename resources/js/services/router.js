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
      { path: 'login', component: require('../views/Login').default, meta: { layout: 'plain' } },

      { path: 'dashboard', component: { template: `<router-view></router-view>` }, meta: { layout: 'default', title: 'Dashboard', sidebar: 'dashboard' } },
      {
        path: 'master',
        component: { template: `<router-view></router-view>` },
        redirect: 'master/tipe_kontak',
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