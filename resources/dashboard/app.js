import './app.css'

import {createApp} from 'vue'

import App from './App.vue'

import Home from './views/Home.vue'

import {createRouter, createWebHistory} from 'vue-router'

const routes = [
    {
        path: '/',
        component: Home,
        name: 'home',
    }
]

const router = createRouter({
    history: createWebHistory('/dashboard'),
    routes,
})

createApp(App).use(router).mount('#app')
