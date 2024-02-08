<script>
import Game from "@/components/Game.vue"
import * as L from "leaflet";

export default {
  components: {
    Game
  },
  data() {
    return {
      //game: null,
      serie: {
        id: 1,
        city: "Nancy",
        cityCenter: [48.6937223,6.1834097],
        photo: "/img/Nancy.jpg"
      },
      levelsList: [],
      gamesList: [],
      newGame: {
        serie_id: "",
      }
    }
  },
  /*  beforeRouteEnter(to, from, next) {
      //get le public game avec l'id
      this.$api.get("games/" + to.params.id).then(resp => {
        next(vm => {
          vm.game = resp.data
          // récup la première photo pr l'afficher dans la vue
          this.newGame.serie_id = this.serie.id
        })
      })
    },*/
  created() {
    /*this.$api.get('levels').then((resp) => {
      this.levelsList = resp.data.data
    }).catch((err) => {
      console.log(err)
    })*/
    //get les public games qui sont sur la serie là et les afficher et les stocker dans gamesList
  },
  mounted() {
    const map = L.map('map').setView(this.serie.cityCenter, 13).setMinZoom(12)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution:
          'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map)

    map.dragging.disable()
    map.zoomControl.disable()
  },
  methods: {
    createGame() {
      //Create une game avec cette serie et un level à choisir
    }
  }
}
</script>

<template>
  <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""
  />
  <div class="new-game">
    <div class="new-game-header">
      <div class="title">
        <img src="/icons/map-pin.svg"/>
        <h1>{{ serie.city }}</h1>
      </div>
      <h2>Créer une partie sur cette ville :</h2>
      <div class="new-game-banner">
        <select v-model="newGame.serie_id">
          <option value="" selected disabled>Choisir un niveau</option>
          <option v-for="level in levelsList" :key="level.id" :value="level.id">{{ level.title }}</option>
        </select>
        <button class="new-game-button">Lancer</button>
      </div>
      <div class="info">
        Dans GeoQuizz, tu devras localiser des lieux sur une carte à partir d'images !
      </div>
    </div>
    <div id="map"></div>
  </div>
  <div class="public-games">
    <Game v-for="game in gamesList" :level="game.level" :link="game.id" :serie="game.serie" class="card"/>
  </div>

</template>

<style scoped lang="scss">
.new-game {
  padding: 5em;
  display: flex;
  flex-wrap: nowrap;
  flex-direction: row;
  justify-content: space-between;
  align-items: start;
  .title {
    display: flex;
    align-items: center;
    gap: .5em;
    h1 {
      font-size: 2em;
      margin: 0;
    }
    img {
      width: 1.8em;
      margin-right: 0.5em;
    }
  }

  h2 {
    font-size: 1.2em;
    font-weight: 300;
  }
}

#map {
  flex-shrink: 2;
  height: 22em;
  width: 50em;
}

.new-game-banner {
  color: black;
  background-color: rgba(26, 26, 45, 0.42);
  border: 1px solid rgb(57, 56, 91);
  border-radius: 1rem;
  width: fit-content;

  padding: 1.5em;
  display: flex;
  flex-direction: row;
  gap: 1em;
  justify-content: center;
  align-items: center;
  margin-bottom:1em;

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

</style>