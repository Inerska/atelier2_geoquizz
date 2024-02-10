<script setup lang="ts">
import { useUserStore } from '@/store/user'

const userStore = useUserStore()
</script>

<template>
  <header class="header">
    <div class="container">
      <router-link to="/" class="logo">Geoquizz</router-link>
      <nav class="nav">
        <ul>
          <li><router-link to="/">Accueil</router-link></li>
          <li v-if="userStore.isLoggedIn"><router-link to="/profil">Profil</router-link></li>
          <li v-if="userStore.isLoggedIn" @click="userStore.logoutUser()"><router-link to="/connexion">Deconnexion</router-link></li>
          <li v-if="!userStore.isLoggedIn"><router-link to="/connexion">Connexion</router-link></li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
}

.container {
  display: flex;
  justify-content: space-between;
  flex-direction: row;
  align-items: center;
  width: 100%;
}

.logo {
  font-size: 24px;
  font-weight: bold;
  color: #ffffff;
  text-decoration: none;
}

.nav ul {
  display: flex;
  list-style: none;
  padding: 0;
}

.nav ul li {
    display: flex;
    align-items: center;
    margin: 0 20px;
    font-size: 20px;
}

.nav ul li a {
  color: #ffffff;
  text-decoration: none;
}

/* Responsive */
@media (max-width: 500px) {
  .container {
    flex-direction: column;
    text-align: center;
  }

  .nav ul {
    justify-content: space-around;
  }

  .nav ul li {
    width: auto;
    font-size: 18px;
  }
}
</style>
