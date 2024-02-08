<script>
import Game from "@/components/Game.vue"

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
        cityCenter: "48.6937223,6.1834097",
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
    this.$api.get('levels').then((resp) => {
      this.levelsList = resp.data.data
    }).catch((err) => {
      console.log(err)
    })
    //get les public games qui sont sur la serie là et les afficher et les stocker dans gamesList
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
  <h1>{{ serie.city }}</h1>
  <div class="new-game">
    <h2>Créer une partie sur cette ville :</h2>
    <select v-model="newGame.serie_id">
      <option value="" selected disabled>Choisir un niveau</option>
      <option v-for="level in levelsList" :key="level.id" :value="level.id">{{ level.title }}</option>
    </select>
    <button class="new-game-button">Lancer</button>
  </div>
  <div class="serie-map">
    <l-map ref="map" v-model:zoom="zoom" v-model:center="center" :useGlobalLeaflet="false">
      <l-tile-layer url="https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png"
                    layer-type="base"
                    name="Stadia Maps Basemap"></l-tile-layer>
    </l-map>
  </div>

</template>

<style lang="scss">

</style>