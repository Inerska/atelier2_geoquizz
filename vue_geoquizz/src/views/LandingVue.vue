<script lang="ts">
import Game from '@/components/Game.vue'
import HeaderComponent from '@/components/HeaderComponent.vue'
import FooterComponent from '@/components/FooterComponent.vue'
import { ws } from '@/utils/WebSocketService'

//TODO : mettre currentGame et createGame sur une seule ligne en desktop, et comme mtntn en mobile

export default {
  components: {
    Game,
    HeaderComponent,
    FooterComponent
  },
  data() {
    return {
      publicGames: {
        game1: {
          id: 1,
          serie: 'Paris',
          level: 'easy',
          photo: '../assets/img/Nancy.jpg'
        },
        game2: {
          id: 2,
          serie: 'Montpellier',
          level: 'medium',
          photo: ''
        },
        game3: {
          id: 3,
          serie: 'Nancy',
          level: 'hard',
          photo: ''
        },
        game4: {
          id: 4,
          serie: 'Nancy',
          level: 'easy',
          photo: ''
        }
      }
    }
  },
  methods: {
    createGame() {
      console.log('createGame')
    }
  },
  mounted() {
    ws.connect("ws://localhost:5200")
  }
}
</script>

<template>
  <HeaderComponent />
  <div class="new-game">
    <h2>Lancer une nouvelle partie</h2>
    <h3>Alexis à toi de trouver le sous-titre</h3>
    <div class="new-game-banner">
      <select>
        <option value="" selected disabled>Choisir une ville</option>
        <option value="Paris">Paris</option>
        <option value="Montpellier">Montpellier</option>
        <option value="Nancy">Nancy</option>
      </select>
      <select>
        <option value="" selected disabled>Choisir un niveau</option>
        <option value="easy">Facile</option>
        <option value="medium">Moyen</option>
        <option value="hard">Difficile</option>
      </select>
      <button class="new-game-button">Lancer</button>
    </div>
  </div>
  <div class="current-game">
    <div class="current-game-card">
      <img class="current-game-img" src="/img/Nancy.jpg" alt="NYC" />
      <div class="current-game-button-1">MONTPELLIER</div>
      <div class="current-game-button-2">Continuer la partie</div>
    </div>
  </div>

  <div class="public-games">
    <h2>Parties publiques</h2>
    <div class="public-games-cards">
      <Game
        class="card"
        v-for="game in publicGames"
        :key="game.id"
        :serie="game.serie"
        :level="game.level"
      />
    </div>
  </div>
  <FooterComponent />
</template>

<style scoped>
.new-game {
  text-align: center;
  color: white;
}

h2 {
  margin: 0;
  font-size: 2em;
  padding: 0.2em;
}

h3 {
  font-size: 1.5em;
  color: rgba(255, 255, 255, 0.3);
  text-align: center;
  margin: 0;
  padding-bottom: 1em;
  font-weight: 400;
}

.new-game-banner {
  background-color: rgba(26, 26, 45, 0.42);
  border: 1px solid rgb(57, 56, 91);
  border-radius: 1rem;
  width: fit-content;
  margin-right: auto;
  margin-left: auto;
  padding: 1.5em;
  display: flex;
  flex-direction: row;
  gap: 1em;
  justify-content: center;
  align-items: center;
}

select {
  appearance: none;
  line-height: 2;
  font-size: 1.1rem;
  padding: 0 3em 0 1em;
  background-color: #fff;
  border: 1px solid #caced1;
  border-radius: 0.5rem;
  color: black;
  cursor: pointer;
}

.new-game-button {
  font-size: 1.2em;
  cursor: pointer;
  background-color: rgb(29, 25, 41);
  color: white;
  border: none;
  border-radius: 1rem;
  padding: 0.5em 1.5em;
  transition: 0.8s;
}

.new-game-button:hover {
  background-color: rgb(18, 16, 24);
  transition: 0.8s;
}

.current-game-card {
  color: white;
  border-radius: 7px;
  overflow: hidden;
  box-shadow: rgba(0, 0, 0, 0.19) 0px 8px 24px;
  width: 20em;
  height: 10em;
  cursor: pointer;
}

.current-game-img {
  object-fit: cover;
  filter: blur(1px) brightness(0.5);
  transition: 0.4s;
  width: 100%;
  height: 10em;
  transform: scale(1.1);
  cursor: pointer;
}

.current-game-img:hover {
  object-fit: cover;
  filter: blur(1px) brightness(0.4);
  transform: scale(1.2);
  transition: 0.4s;
}

.current-game-button-1,
.current-game-button-2 {
  position: relative;
  z-index: 100;
  right: 1em;
  height: 0;
  float: right;
  font-size: 1.2em;
  font-weight: 300;
}

.current-game-button-1 {
  top: -3.5em;
  font-weight: bold;
}

.current-game-button-2 {
  top: -2em;
}

.public-games,
.current-game {
  padding-top: 2em;
  padding-right: 2em;
  padding-left: 2em;
}

.public-games-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(13em, 1fr));
  column-gap: 2em;
  row-gap: 1em;
}
</style>
