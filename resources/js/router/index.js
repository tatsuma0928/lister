import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import App from    '../App.vue'
import Weekly from '../components/pages/WeeklyCardComponent.vue'

const routes = [
    {
        path: '/',
        component: App,
    },
    {
        path: '/:path',
        name: 'week',
        component: Weekly
    }
]

const router = new VueRouter({
    mode: 'history',
    routes: routes
})

export default router

  