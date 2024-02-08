<script lang="ts">
import Game from '@/components/Game.vue'
import Tooltip from '@/components/Tooltip.vue'
import HeaderComponent from '@/components/HeaderComponent.vue'
import FooterComponent from '@/components/FooterComponent.vue'
import { ws } from '@/utils/WebSocketService'
// import CreateGameComponent from "@/components/CreateGameComponent.vue";
// import axios from 'axios'

//TODO : mettre currentGame et createGame sur une seule ligne en desktop, et comme mtntn en mobile

export default {
  components: {
    Game,
    Tooltip,
    HeaderComponent,
    FooterComponent,
},
  data() {
    return {
      gamesList: [],
      //seriesList: [],
      seriesList: [
        { id: 1, city: "Nancy" },
        { id: 2, city: "Metz" },
        { id: 3, city: "Paris" },
        { id: 4, city: "Strasbourg" }
      ],
      levelsList: [],
      newGame: {
        serie_id: "",
        level_id: ""
      },
      publicGames: {
        game1: {
          id: 1,
          serie: "Paris",
          level: "easy",
          photo: "../assets/img/Nancy.jpg"
        },
        game2: {
          id: 2,
          serie: "Montpellier",
          level: "medium",
          photo: ""
        },
        game3: {
          id: 3,
          serie: "Nancy",
          level: "hard",
          photo: ""
        },
        game4: {
          id: 4,
          serie: "Nancy",
          level: "easy",
          photo: ""
        }
      }
    }
  },
  created() {

    this.$api.post('/login')
      .then((response) => {
        console.log(response)
      })
      .catch((error) => {
        console.log(error)
      })
    fetch('/service_series/serie')
        .then(response => response.json())
        .then(data => {
          console.log(data)
        })
  },
  methods: {
    createGame() {
      console.log('createGame')
    },
    linkSerie(id) {
      this.$router.push("/serie/" + id)
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
    <h3>Et si tu challengais ta culture géographique ?</h3>
    <div class="info">
      Dans GeoQuizz, tu devras localiser des lieux sur une carte à partir d'images !
    </div>
    <div class="new-game-banner">
      <select v-model="newGame.serie_id">
        <option disabled selected value="">Choisir une ville</option>
        <option v-for="serie in seriesList" :key="serie.id" :value="serie.id">{{ serie.city }}</option>
      </select>
      <select v-model="newGame.level_id">
        <option disabled selected value="">Choisir un niveau</option>
        <option v-for="level in levelsList" :key="level.id" :value="level.id">{{ level.title }}</option>
      </select>
      <button class="new-game-button">Lancer</button>
    </div>
  </div>
  <div class="current-game">
    <div class="current-game-card">
      <img alt="NYC" class="current-game-img" src="/img/nyc.jpg"/>
      <div class="current-game-button-1"> MONTPELLIER</div>
      <div class="current-game-button-2"> Continuer la partie</div>
    </div>
  </div>

  <div class="all-series">
    <div class="title">
      <h2>Villes à jouer</h2>
      <Tooltip desc="Découvre les parties publiques sur une ville !" width="25"/>
    </div>
    <div class="series-cards">
      <button v-for="serie in seriesList" @click="linkSerie(serie.id)" :key="serie.id" class="serie-card">{{ serie.city }}</button>
    </div>
  </div>

  <div class="public-games">
    <div class="title">
      <h2>Parties publiques</h2>
      <Tooltip desc="Découvre les parties créées par d'autres joueurs !" width="27"/>
    </div>
    <div class="public-games-cards">
      <Game v-for="game in publicGames" :level="game.level" :key="game.id" :link="game.id" :serie="game.serie" class="card"/>
    </div>
  </div>
  <FooterComponent />
</template>

<style lang="scss">
$offwhite: darken(white, 10%);
$darkblue: rgb(57, 56, 91);

body {
  overflow-y: auto;
}

.new-game {
  text-align: center;
  color: white;

  .info {
    color: white;
    font-size: 1em;
    padding: 1em;
    margin-bottom: 1.5em;
    text-align: center;
    background-color: rgb(51 65 85);
    border-radius: 8px;
    width: fit-content;
    margin-left: auto;
    margin-right: auto;
  }
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

  select {
    appearance: none;
    line-height: 2;
    font-size: 1.1rem;
    padding: 0 3em 0 1em;
    background-color: #fff;
    border: 1px solid #caced1;
    border-radius: .5rem;
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
    padding: .5em 1.5em;
    transition: 0.8s;

    &:hover {
      background-color: rgb(18, 16, 24);
      transition: 0.8s;
    }
  }
}

.current-game-card {
  color: white;
  border-radius: 7px;
  overflow: hidden;
  box-shadow: rgba(0, 0, 0, 0.19) 0px 8px 24px;
  width: 20em;
  height: 10em;
  cursor: pointer;

  .current-game-img {
    object-fit: cover;
    filter: blur(1px) brightness(0.5);
    transition: 0.4s;
    width: 100%;
    height: 10em;
    transform: scale(1.1);
    cursor: pointer;

    &:hover {
      object-fit: cover;
      filter: blur(1px) brightness(0.4);
      transform: scale(1.2);
      transition: 0.4s;
    }
  }

  .current-game-button-1, .current-game-button-2 {
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
}


h2 {
  margin: 0;
  font-size: 2.5em;
  padding: .2em;
}

h3 {
  font-size: 1.2em;
  color: rgba(255, 255, 255, 0.3);
  text-align: center;
  margin: 0;
  padding-bottom: 1em;
  font-weight: 400;
}

.all-series {
  padding-top: 2em;
  padding-right: 2em;
  padding-left: 2em;

  .title {
    display: flex;
    justify-content: start;
    align-items: center;
    margin-bottom: 1em;
  }

  .series-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(5em, 1fr));
    column-gap: 2em;
    row-gap: 1em;

    button {
      text-transform: uppercase;
      position: relative;
      font-size: 18px;
      transition: color 0.5s, transform 0.2s, background-color 0.2s;
      outline: none;
      border-radius: 3px;
      margin: 0 10px;
      padding: 23px 33px;
      border: 3px solid transparent;

      &:active {
        transform: translateY(3px);
      }

      &::after, &::before {
        border-radius: 3px;
      }

      .material-bubble {
        background-color: transparent;
        color: darken($offwhite, 10%);
        border: none;
        overflow: hidden;
        box-shadow: none;

        &:hover {
          color: $offwhite;
        }

        &::before {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          border: 2px solid darken($offwhite, 10%);
          transition: opacity 0.3s, border 0.3s;
        }

        &:hover::before {
          opacity: 0;
        }

        &::after {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          width: 200px;
          height: 200px;
          background-color: lighten($darkblue, 10%);
          border-color: transparent;
          border-radius: 50%;
          transform: translate(-10px, -70px) scale(0.1);
          opacity: 0;
          z-index: -1;
          transition: transform 0.3s, opacity 0.3s, background-color 0.3s;
        }

        &:hover::after {
          opacity: 1;
          transform-origin: 100px 100px;
          transform: scale(1) translate(-10px, -70px);
        }
      }
    }
  }
}

.public-games {
  padding-top: 2em;
  padding-right: 2em;
  padding-left: 2em;

  .title {
    display: flex;
    justify-content: start;
    align-items: center;
    margin-bottom: 1em;
  }

  h2 {
    margin: 0;
    font-size: 2.5em;
    padding: .2em;
  }

  .public-games-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(13em, 1fr));
    column-gap: 2em;
    row-gap: 1em;
  }
}

.public-games, .current-game, .all-series {
  padding-top: 2em;
  padding-right: 2em;
  padding-left: 2em;

}
</style>
