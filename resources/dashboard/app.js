import './app.css'
import {createApp} from 'vue'
import App from './App.vue'
import Home from './views/Home.vue'
import {createRouter, createWebHistory} from 'vue-router'
import Vision from '@wyxos/vision'

const routes = [
    {
        path: '/',
        component: Home,
        name: 'home',
    },
    {
        path: '/applications',
        component: () => import('./views/Applications.vue'),
        name: 'applications',
    },
    {
        path: '/databases',
        component: () => import('./views/Databases.vue'),
        name: 'databases',
    },
    {
        path: '/cron',
        component: () => import('./views/Cron.vue'),
        name: 'cron',
    },
    {
        path: '/supervisord',
        component: () => import('./views/Supervisord.vue'),
        name: 'supervisord',
    },
    {
        path: '/logs',
        component: () => import('./views/Logs.vue'),
        name: 'logs',
    },
    {
        path: '/settings',
        component: () => import('./views/Settings.vue'),
        name: 'settings',
    }
]

const router = createRouter({
    history: createWebHistory('/dashboard'),
    routes,
})

createApp(App).use(Vision).use(router).mount('#app')
