import { createRouter, createWebHistory } from 'vue-router'

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
  ]
})

export default router
