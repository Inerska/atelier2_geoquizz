<script lang="ts">
import {RouterView} from 'vue-router'
import Game from '@/components/Game.vue'

export default {
  components: {
    RouterView,
    Game
  },
  data() {
    return {
      publicGames: {
        game1: {
          serie: "Paris",
          level: "Facile",
          photo: "../assets/img/Nancy.jpg"
        },
        game2: {
          serie: "Montpellier",
          level: "Moyen",
          photo: ""
        },
        game3: {
          serie: "Nancy",
          level: "Difficile",
          photo: ""
        },
        game4: {
          serie: "Nancy",
          level: "Facile",
          photo: ""
        }
      }
    }
  }
}
</script>

<template>
  <div>le header</div>
  <h1>GeoQuizz</h1>
  <div class="new-game">
    <h2>Lancer une nouvelle partie</h2>
    <div class="new-game-form">
      <select>
        <option value="" selected disabled>Choisir une ville</option>
        <option value="Paris">Paris</option>
        <option value="Montpellier">Montpellier</option>
        <option value="Nancy">Nancy</option>
      </select>
      <select>
        <option value="" selected disabled>Choisir un mode de jeu</option>
        <option value="easy">Facile</option>
        <option value="medium">Moyen</option>
        <option value="hard">Difficile</option>
      </select>
      <button>Lancer</button>
    </div>
  </div>

  <div class="public-games">
    <h2>Parties publiques</h2>
    <Game v-for="game in publicGames" :serie="game.serie" :level="game.level" :photo="game.photo"></Game>
  </div>

</template>