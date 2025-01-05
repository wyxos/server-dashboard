import './app.css'

import {createApp} from 'vue'

import App from './App.vue'

import Home from './views/Home.vue'

import {createRouter, createWebHistory} from 'vue-router'
import Applications from "./views/Applications.vue";

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
    }
]

const router = createRouter({
    history: createWebHistory('/dashboard'),
    routes,
})

createApp(App).use(router).mount('#app')
