import { createRouter, createWebHistory } from 'vue-router'

import home from './components/order/index.vue'
import googleCode from './components/order/google_code.vue'


const routes = [
    //project routes
    {
        path: '/',
        component: home,
        name: 'home'
    },
    {
        path: '/google/:code?',
        component: googleCode,
        name: 'googleCode'
    },

]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router
