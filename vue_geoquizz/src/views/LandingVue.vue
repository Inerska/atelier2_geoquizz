<script lang="ts">
import Game from '@/components/Game.vue'
import Tooltip from '@/components/Tooltip.vue'
import HeaderComponent from '@/components/HeaderComponent.vue'
import FooterComponent from '@/components/FooterComponent.vue'
import {ws} from '@/utils/WebSocketService'
// import CreateGameComponent from "@/components/CreateGameComponent.vue";
// import axios from 'axios'
import {useUserStore} from '@/store/user'

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
      seriesList: [],
      levelsList: [],
      currentGames: [],
      newGame: {
        serie_id: "",
        level_id: "",
        public: false
      },
      createdGames: [],
      publicGames: []
    }
  },
  created() {
    const userStore = useUserStore();
    this.$api.get('/games')
        .then(resp => {
          //this.seriesList = resp.data.data
          //console.log(" get games ", resp.data)
          resp.data.forEach((game) => {
            if (game.isPublic) {
              this.publicGames.push(game)
            }
          })
          //console.log(this.publicGames)
        }).catch(err => {
      console.log(err)
    })

    if (userStore.getProfileId !== null) {
      this.$api.get(`profiles/${userStore.getProfileId}/playedGames/`)
          .then(resp => {
            //console.log("toutes les Games ", resp.data)
            resp.data.forEach(game => {
              if (game.status == 0 ) {
                this.createdGames.push(game)
              }
              if (game.status == 1) {
                this.currentGames.push(game)
                console.log("currentGame qui a été push ",game)
              }
            })
          }).catch(err => {
        console.log(err)
      })
    }

    this.$api.get('/series')
        .then(resp => {
          this.seriesList = resp.data.data
        }).catch(err => {
      console.log(err)
    })

    this.$api.get('/levels')
        .then(resp => {
          this.levelsList = resp.data.data
        }).catch(err => {
      console.log(err)
    })

  },
  methods: {
    createGame() {
      //console.log('createGame')
      this.$api.post('/games', {
        serie_id: this.newGame.serie_id,
        level_id: this.newGame.level_id,
        profile_id: useUserStore().getProfileId,
        is_public: this.newGame.public
      }).then(resp => {
        //console.log("createGame, id du playedGame ", resp.data)
        this.$router.push(`/jeu/${resp.data.id}`)
      }).catch(err => {
        console.log(err)
      })
    },
    linkSerie(id) {
      this.$router.push("/serie/" + id)
    },
    launchGame(id) {
      this.$router.push(`/jeu/${id}`)
    }
  }
}
</script>

<template>
  <HeaderComponent/>
  <div class="game-header">
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
        <input v-model="newGame.public" type="checkbox" id="public" name="public" checked/>
        <label for="public">Publique ? </label>
        <button @click="createGame()" class="new-game-button">Lancer</button>
      </div>
    </div>
<!--    <div v-if="currentGames.length >0" class="current-game" v-for="currentGame in currentGames">
      <div @click="launchGame(currentGame.playedGamesId)" class="current-game-card">
        &lt;!&ndash; TODO: aller fetch li'mage correspondante &ndash;&gt;
        <img alt="NYC" class="current-game-img" src="/img/nyc.jpg"/>
        <div class="current-game-button-1"> {{currentGame.city}}</div>
        <div class="current-game-button-2"> Continuer la partie</div>
      </div>
    </div>-->
  </div>

  <div class="started-games">
    <div v-if="currentGames.length >0" class="current-game" v-for="currentGame in currentGames">
      <div @click="launchGame(currentGame.playedGamesId)" class="current-game-card">
        <!-- TODO: aller fetch li'mage correspondante -->
        <img alt="NYC" class="current-game-img" src="/img/nyc.jpg"/>
        <div class="current-game-button-1"> {{currentGame.city}}</div>
        <div class="current-game-button-2"> Continuer la partie</div>
      </div>
    </div>
  </div>


  <div class="all-series">
    <div class="title">
      <h2>Villes à jouer</h2>
      <Tooltip desc="Découvre les parties publiques sur une ville !" width="25"/>
    </div>
    <div class="series-cards">
      <button v-for="serie in seriesList" @click="linkSerie(serie.id)" :key="serie.id" class="serie-card">{{
          serie.city
        }}
      </button>
    </div>
  </div>

  <div class="public-games">
    <div class="title">
      <h2>Parties publiques</h2>
      <Tooltip desc="Découvre les parties créées par d'autres joueurs !" width="27"/>
    </div>
    <div v-if="publicGames.length > 0" class="public-games-cards">
      <Game v-for="game in publicGames" :level="game.level" :key="game.id" :link="game.id" :serie="game.serie"
            class="card"/>
    </div>
    <div v-else>Il n'y a pas de parties publiques.</div>
  </div>

</template>

<style scoped lang="scss">
$offwhite: darken(white, 10%);
$darkblue: rgb(57, 56, 91);
h2 {
  margin: 0;
  font-size: 2em; // 2.5em
  padding: .2em;
}

h3 {
  font-size: 1em; //1.2em
  color: rgba(255, 255, 255, 0.3);
  text-align: center;
  margin: 0;
  padding-bottom: 1em;
  font-weight: 400;
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
    border-radius: 0px; //8px
    width: fit-content;
    margin-left: auto;
    margin-right: auto;
  }

  &-banner {
    background-color: rgba(26, 26, 45, 0.42);
    border: 1px solid rgb(57, 56, 91);
    border-radius: 0; //1rem
    //width: fit-content;
    margin-right: auto;
    margin-left: auto;
    padding: 1.5em;
    display: flex;
    flex-direction: column; // row
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
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 1em;

    button {
      text-transform: uppercase;
      position: relative;
      font-size: 1em; //1.2em
      transition: color 0.5s, transform 0.2s, background-color 0.2s;
      outline: none;
      border-radius: 3px;
      margin: 0 .5em;
      padding: .5em 1em;
      border: 3px solid transparent;
      cursor: pointer;
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

@media only screen and (min-width: 768px) and (max-width: 1200px) {
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
  .new-game {
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

    .info {
      border-radius: 8px;
    }

    &-banner {
      border-radius: 1rem;
      width: fit-content;
      flex-direction: row;
    }
  }
}

@media only screen and (min-width: 1200px) {
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
  .game-header {
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    align-items: center;
    padding: 2em;
    gap: 1em;

    .new-game {

      .info {
        border-radius: 8px;
      }

      &-banner {
        border-radius: 1rem;
        width: fit-content;
        flex-direction: row;
      }
    }
  }
}
</style>
