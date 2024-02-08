import {createRouter, createWebHistory} from 'vue-router'
import LandingVue from "@/views/LandingVue.vue";
import SerieVue from "@/views/SerieVue.vue";
import ProfileVue from "@/views/ProfileVue.vue";
import LoginVue from "@/views/LoginVue.vue";
import GameVue from "@/views/GameVue.vue";

const router = createRouter({
    //@ts-ignore
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'landing',
            component: LandingVue
        },
        {
            path: '/jeu',
            name: 'game',
            component: GameVue
        },
        {
            path: '/connexion',
            name: 'login',
            component: LoginVue
        },
        {
            path: '/profil',
            name: 'profile',
            component: ProfileVue
        },
        {
            path: '/serie/:id',
            name: 'serie',
            component: SerieVue
        },
    ]
})

export default router
