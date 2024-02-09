Nv v
<script lang="ts">
import Game from "@/components/Game.vue"
import * as L from "leaflet";
import HeaderComponent from "@/components/HeaderComponent.vue";

export default {
  components: {
    Game,
    HeaderComponent
  },
  data() {
    return {
      //game: null,
      serie: [],
      levelsList: [],
      gamesList: [],
      newGame: {
        serie_id: "",
        level_id: "",

      }
    }
  },
  created() {
    this.$api.get('/levels').then(resp => {
      this.levelsList = resp.data.data
    })
    this.$api.get('/series/' + this.$route.params.id).then(resp => {
      this.serie = resp.data.data

      const map = L.map('map').setView(this.serie.cityCenter.split(","), 13).setMinZoom(12)

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:
            'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map)

      map.dragging.disable();
      map.zoomControl.remove();
      map.scrollWheelZoom.disable()
    })
    this.$api.get('games').then(resp => {
      resp.data.forEach((game) => {
        if (game.serie_id == this.$route.params.id || game.isPublic) {
          this.gamesList.push(game)
        }
      })
    })
//get les public games qui sont sur la serie là et les afficher et les stocker dans gamesList
},
methods: {
  createGame() {
    //console.log('createGame')
    this.$api.post('/games', {
      serie_id: this.$router.params.id,
      level_id: this.newGame.level_id,
      profile_id: this.getProfileId,
      is_public: this.newGame.public
    }).then(resp => {
      //console.log("createGame, id du playedGame ", resp.data)
      this.$router.push(`/jeu/${resp.data.id}`)
    }).catch(err => {
      console.log(err)
    })
  },
}
}
</script>

<template>
  <HeaderComponent/>
  <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""
  />
  <div class="game-header">
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
          <input v-model="newGame.public" type="checkbox" id="public" name="public" checked/>
          <label for="public">Publique ? </label>
          <button @click="createGame()" class="new-game-button">Lancer</button>
        </div>
		<div class="info">
			<span>Dans <b>GeoQuizz</b>, tu devras localiser des lieux sur une carte à partir d'images !</span>
			<br>
			<span><b>Règle du jeu :</b></span>
			<ul>
				<li>
				<span>Une partie consiste en une séquence de <b>photos</b> issues aléatoirement d'une même série, à placer sur la carte d'une ville,</span>
				</li>
				<li>
				<span>Une série est un ensemble de photos concernant la <b>même ville</b> et la même carte ; une série peut contenir un nombre arbitraire de photos.</span>
				</li>
				<li>
				<span>Chaque réponse permet de gagner un certain nombre de points, en fonction de la <b>précision du placement</b> et de la <b>rapidité pour répondre</b>,</span>
				</li>
				<li>
				<span>L'objectif pour une partie est d'<b>obtenir le maximum de points</b>.</span>
				</li>
				<li>
				<span>La partie est terminée lorsque les <b>photos ont été positionnées</b>.</span>
				</li>
			</ul>
			<span><b>Règles de calcul des points :</b></span>
			<ul>
				<li>
				  <span>Pour 1 réponse placée à une distance <b>inférieur à 100m</b> : <b>5pts</b></span>
				</li>
				<li>
				  <span>Pour 1 réponse placée à une distance <b>inférieur à 200m</b> : <b>3pts</b></span>
				</li>
				<li>
				  <span>Pour 1 réponse placée à une distance <b>inférieur à 300m</b> : <b>1pt</b></span>
				</li>
			</ul>
		  </div>
      </div>
      <div id="map"></div>
    </div>
    <div class="public-games">
      <Game v-for="game in gamesList" :level="game.level" :link="game.id" :serie="game.serie" class="card"/>
    </div>
  </div>


</template>

<style scoped lang="scss">
.new-game {
  padding: 0em; //5em;
  display: flex;
  flex-direction: column; //row;
  justify-content: space-between;
  align-items: start;
  gap: 1em; //2em;
  .title {
    width: fit-content;
    margin-left: auto; //0;
    margin-right: auto; //0;
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
    text-align: center;
    width: 80vw; //0;
    margin-left: auto; //0;
    margin-right: auto; //0;
    font-size: 1.2em;
    font-weight: 300;
  }
}

#map {
  width: 100vw;
  height: 30em;
}

.new-game-banner {
  color: black;
  background-color: rgba(26, 26, 45, 0.42);
  border: 1px solid rgb(57, 56, 91);
  border-radius: 0; //1rem;
  //width: fit-content;
  padding: 1.5em;
  display: flex;
  flex-direction: row;
  gap: 1em;
  justify-content: center;
  align-items: center;
  margin-bottom: 1em;

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
  text-align: left;
  width: 100vw;
  background-color: rgb(51 65 85);
  border-radius: 0px; //8px;
  //width: fit-content;
  margin-left: auto;
  margin-right: auto;
}
.info ul li {
	margin-bottom: 0.2em;
}

.public-games {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(20em, 1fr));
  grid-row-gap: 1em;
  grid-column-gap: 1em;
  padding: 2em;
}


</style>
