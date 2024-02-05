<script lang="ts">
import {RouterView} from 'vue-router'
import Game from '@/components/Game.vue'
import HeaderComponent from '@/components/HeaderComponent.vue'

export default {
  components: {
    RouterView,
    Game,
    HeaderComponent
  },
  data() {
    return {
      publicGames: {
        game1: {
          serie: "Paris",
          level: "easy",
          photo: "../assets/img/Nancy.jpg"
        },
        game2: {
          serie: "Montpellier",
          level: "medium",
          photo: ""
        },
        game3: {
          serie: "Nancy",
          level: "hard",
          photo: ""
        },
        game4: {
          serie: "Nancy",
          level: "easy",
          photo: ""
        }
      }
    }
  }
}
</script>

<template>
  <HeaderComponent/>
  <div class="top-container">
    <div class="new-game">
      <img class="new-game-img" src="/img/banner.jpg" alt="Nancy" />
      <div class="new-game-form">
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
        <div class="new-game-button">Lancer</div>
      </div>
    </div>
    <div class="actual-game">
      <img class="actual-game-img" src="/img/nyc.jpg" alt="NYC" />
      <div class="actual-game-button"> Continuer la partie</div>
    </div>
  </div>


  <div class="public-games">
    <h2>Parties publiques</h2>
    <div class="public-games-cards">
      <Game class="card" v-for="game in publicGames" :serie="game.serie" :level="game.level"/>
    </div>
  </div>

</template>

<style scoped>
img {
  width: 10em;
}

.top-container {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 3em;
  padding: 2em 5em;
  justify-content: space-between;
  align-items: stretch;
}

.new-game {
  color: white;
  flex-basis: 20em;
  flex-shrink: 1;
  flex-grow: 1;
  background-color: rgb(36,35,61);
  border-radius: 5px;
  border: 1px solid rgb(57, 56, 91);
  display: flex;
  flex-direction: row;
  gap: 1em;
  align-items: center;
  justify-content: space-between;
  padding: 1em;
}

.new-game-img {
  flex-basis: 40%;
  height: 100%;
  object-fit: cover;
  border-radius: 5px;
}

.new-game-form {
  flex-basis: 60%;
  display: flex;
  flex-direction: column;
  row-gap: 1em;
  justify-content: space-between;
}

select {
  appearance: none;
  height: 2rem;
  font-size: 1rem;
  padding: 0 3em 0 1em;
  background-color: #fff;
  border: 1px solid #caced1;
  border-radius: 0.25rem;
  color: #000;
  cursor: pointer;
}

.new-game-button {
  font-size: 1.2em;
  font-weight: bold;
  cursor: pointer;
  text-align: right
}

.actual-game {
  background-color: rgb(39,37,74);
  border-radius: 5px;
  flex-basis: 20em;
  flex-shrink: 1;
  overflow: hidden;
  cursor: pointer;
}

.actual-game-img {
  width: 20em;
  height: 10em;
  object-fit: cover;
  filter: blur(1px) brightness(0.5);
  transition: 0.4s;
  transform: scale(1.1);

}

.actual-game-img:hover {
   width: 20em;
   height: 10em;
   object-fit: cover;
   filter: blur(1px) brightness(0.4);
  transform: scale(1.2);
  transition: 0.4s;

}

.actual-game-button {
  position: relative;
  z-index: 100;
  top: -2em;
  right: 1em;
  height: 0;
  float: right;
  font-size: 1.2em;
  font-weight: 300;
}

.public-games {
  padding: 2em;

}

.public-games-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(13em, 1fr));
  column-gap: 2em;
  row-gap: 1em;
}

</style>