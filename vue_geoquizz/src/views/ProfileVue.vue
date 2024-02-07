<template>
  <HeaderComponent />
  <div class="container">
    <div class="banner">
      <img class="img" src="/img/Nancy.jpg" alt="banner" />
      <div class="gradient-overlay"></div>
      <div class="user">
        <img class="avatar" src="/avatar.svg" />
      </div>
    </div>
    <div class="stats">
      <h1>Username</h1>
      <div class="column-stats">
        <div class="data-stats">
          <span class="value" id="nbGame">10</span>
          <p class="name">Partie</p>
        </div>
        <div class="data-stats">
          <span class="value" id="nbGame">6000</span>
          <p class="name">Score</p>
        </div>
        <div class="data-stats">
          <span class="value" id="nbGame">6</span>
          <p class="name">Level</p>
        </div>
      </div>
    </div>
    <main class="parent-container">
      <div class="small-container">
        <h2 class="title">Historique des parties</h2>
        <div class="game-container">
          <!-- @TODO : Boucler les données depuis la DB -->
          <Game serie="2" photo="../assets/img/Nancy.jpg" level="2" />
          <Game serie="2" photo="../assets/img/Nancy.jpg" level="2" />
          <Game serie="2" photo="../assets/img/Nancy.jpg" level="2" />
          <Game serie="2" photo="../assets/img/Nancy.jpg" level="2" />
          <Game serie="2" photo="../assets/img/Nancy.jpg" level="2" />
          <Game serie="2" photo="../assets/img/Nancy.jpg" level="2" />
        </div>
      </div>
    </main>
    <!-- @TODO : Vérifier si la personne sur le profil est bien la personne propriétaire de celle-ci avant d'afficher cette section -->
    <div class="parent-container">
      <div class="small-container">
        <h2 class="title">Modifier le profil</h2>
        <div class="edit-container">
          <form>
            <div class="form-group">
              <label>Choisir un avatar</label>
              <div class="avatar-selection">
                <img
                  v-for="(avatar, index) in avatars"
                  :src="avatar"
                  :alt="'Avatar ' + (index + 1)"
                  :key="index"
                  :class="{ 'selected-avatar': selectedAvatar === avatar }"
                  @click="selectAvatar(avatar)"
                  class="avatar-image"
                />
              </div>
            </div>
            <div class="form-group">
              <label>Choisir un fond d'écran</label>
              <div class="background-selection">
                <img
                  v-for="(background, index) in backgrounds"
                  :src="background"
                  :alt="'Background ' + (index + 1)"
                  :key="'background-' + index"
                  :class="{ 'selected-background': selectedBackground === background }"
                  @click="selectBackground(background as any)"
                  class="background-image"
                />
              </div>
            </div>
            <div class="form-group">
              <label for="username">Nom d'utilisateur</label>
              <input type="text" class="form-control" id="username" />
            </div>
            <div class="form-group">
              <label for="password">Mot de passe</label>
              <input type="password" class="form-control" id="password" />
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <FooterComponent />
</template>

<script lang="ts">
import HeaderComponent from '@/components/HeaderComponent.vue'
import FooterComponent from '@/components/FooterComponent.vue'
import Game from '@/components/Game.vue'

export default {
  components: {
    HeaderComponent,
    FooterComponent,
    Game
  },
  data() {
    return {
      avatars: ['/avatar.svg', '/avatar.svg', '/avatar.svg', '/avatar.svg', '/avatar.svg'],
      selectedAvatar: '',
      backgrounds: [
        '/src/assets/img/Nancy.jpg',
        '/src/assets/img/Nancy.jpg',
        '/src/assets/img/Nancy.jpg',
        '/src/assets/img/Nancy.jpg'
      ],
      selectedBackground: ''
    }
  },
  methods: {
    selectAvatar(avatar: string) {
      this.selectedAvatar = avatar
    },
    selectBackground(background: string) {
      this.selectedBackground = background
    },
    saveProfile() {}
  }
}
</script>

<style>
body {
  overflow-y: auto;
}
.form-group {
  margin-bottom: 20px;
  display: flex;
  flex-direction: column;
}
.form-group label {
  margin-bottom: 5px;
  font-size: 18px;
  color: #000;
}
.edit-container {
  display: flex;
  flex-direction: column;
  align-items: left;
}
.background-selection {
  display: flex;
  justify-content: space-around;
  padding: 10px 0;
}
.background-image {
  width: 100px; 
  height: 60px; 
  border-radius: 5px;
  cursor: pointer;
  transition: transform 0.2s;
}
.background-image:hover {
  transform: scale(1.1);
}
.selected-background {
  border: 2px solid rgb(193, 48, 255); 
}
.avatar-selection {
  display: flex;
  justify-content: center;
}
.avatar-image {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  cursor: pointer;
  transition: transform 0.2s;
  margin: 10px;
}
.avatar-image:hover {
  transform: scale(1.1);
}
.selected-avatar {
  border: 2px solid blue; 
}
.parent-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.small-container {
  display: flex;
  flex-direction: column;
  align-items: left;
  width: auto;
  min-width: 500px;
}
.small-container .game-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
}
.small-container .game-container .game-card {
  margin: 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.stats {
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
  margin-top: 60px;
}
.stats .column-stats {
  display: flex;
  flex-direction: row;
  align-items: center;
  margin: 0 20px;
}
.stats .column-stats .data-stats {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 25px;
}
.stats .column-stats .data-stats .value {
  font-size: 24px;
  font-weight: 700;
  color: #000;
}
.stats .column-stats .data-stats .name {
  font-size: 18px;
  font-weight: 400;
  color: #000;
}
.container {
  display: flex;
  flex-direction: column;
}
.banner {
  position: relative;
  width: 100%;
}
.banner .img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  filter: grayscale(50%);
}
.banner .gradient-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(180deg, transparent, rgba(68, 68, 68, 0.4));
  z-index: 1;
}
.banner .user {
  display: flex;
  position: absolute;
  bottom: -65px;
  left: 50%;
  transform: translate(-50%, 0);
  z-index: 2;
}
.banner .avatar {
  border: 10px solid #fff;
  height: 130px;
  width: 130px;
  border-radius: 50%;
  bottom: 0;
}
.banner h1 {
  color: #fff;
  margin: 0;
  padding: 10px;
  font-size: 32px;
  font-weight: 700;
  text-shadow: 4px 4px 8px #000000;
}
</style>
