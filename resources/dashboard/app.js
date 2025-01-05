import './app.css'

import {createApp} from 'vue'

import App from './App.vue'

import Dashboard from './views/Dashboard.vue'

import {createRouter, createWebHistory} from 'vue-router'

const routes = [
    {
        path: '/',
        component: Dashboard,
        name: 'dashboard',
    }
]

const router = createRouter({
    history: createWebHistory('/dashboard'),
    routes,
})

createApp(App).use(router).mount('#app')
