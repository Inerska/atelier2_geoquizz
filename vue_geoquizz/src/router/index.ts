import {createRouter, createWebHistory} from 'vue-router'

const router = createRouter({
    // @ts-ignore : this is a bug in the typescript definition
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'landing',
            component: import('../views/LandingVue.vue')
        },
        {
            path: '/jeu',
            name: 'game',
            component: import('../views/GameVue.vue')
        },
        {
            path: '/connexion',
            name: 'login',
            component: import('../views/LoginVue.vue')
        },
        {
            path: '/profil',
            name: 'profile',
            component: import('../views/ProfileVue.vue')
        },
        {
            path: '/serie/:id',
            name: 'serie',
            component: import('../views/SerieVue.vue')
        }
    ]
})

export default router
