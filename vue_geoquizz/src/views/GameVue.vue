<template>
  <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""
  />
  <div class="container">
    <img :src="imageUrl" alt="Image dynamique" class="dynamic-image" />
    <div id="map" class="map-container"></div>
  </div>
  <div class="confirm">
    <button :disabled="!currentMarker" @click="onSubmit">Confirmer</button>
  </div>
  <div class="pause">
    <button @click="pause()">Mettre en pause</button>
  </div>
  <div class="player">
    <img class="avatar" src="/avatar.svg" />
    <div class="progression">
      <p class="text">Score : {{ totalScore }}</p>
      <p class="text">Round : {{ roundNumber }}/{{totalRounds}}</p>
    </div>
  </div>
  <div class="minutor">
    <p>Temps restant : {{ timer }}s</p>
  </div>
  <div v-if="showPopup" class="leaflet-popup">
    <div id="popupMap" class="popup-map-container"></div>
    <div class="flex">
      <p><b>Distance :</b> {{ distanceBtwPoints }}m</p>
      <p><b>Score :</b> {{ score }}</p>
      <button class="button-next" @click="nextRound">Suivant</button>
    </div>
  </div>
</template>

<script lang="ts">
import * as L from 'leaflet'
import { ws } from '@/utils/WebSocketService'
import { GameProgression, User, Game, Photo, CurrentGame, Serie} from '@/utils/types' // @TODO: à utiliser pour gerer et bloquer certains éléments du jeu

export default {
  data() {
    return {
      game: null as Game,
      photos: [] as Photo[],
      currentMarker: [],
      showPopup: false,
      distanceBtwPoints: 0,
      totalScore: 0,
      score: 0,
      roundNumber: 0,
      imageUrl: '',
      initialCenter: [],
      gameProgression: {} as GameProgression,
      roundsData: [] as Photo[],
      actualRound: null as CurrentGame,
      originalPosition: [],
      map: null,
      totalRounds : 0,
      timer: 20,
      timerInterval: null,
	  	maxTime: 20,
	  	D: 100,
    }
  },
  beforeMount() {
    clearInterval(this.timerInterval);
},
  // @ts-ignore // typescript n'arrive pas à lire correctement le fichier tsconfig
  async mounted() {
    await this.$api.get(`games/${this.$route.params.id}`).then((resp) => {
      console.log(" données de jeu ", resp.data)
      this.game = resp.data.game as Game;
      this.roundNumber = resp.data.advancement;
      this.photos = JSON.parse(this.game.photos)
      this.totalRounds = this.photos.length;
      this.$api.get(`series/${this.game.serie_id}`).then(resp1 => {
        this.initialCenter = resp1.data.data.cityCenter.toString().split(',');
        console.log("city Center dans le série ", this.initialCenter)
      })
      //this.game.photos.replace("[",'')
      //this.game.photos.replace("]",'')
      this.photos.forEach((photo) => {
        this.$api.get(`/photos/${photo}`).then( resp2 => {
          //console.log("resp2 ", resp2);
          let coordinates = resp2.data.data.coordinates.split(',').map(coord => parseFloat(coord));
          console.log("coordinates ", coordinates)
          this.roundsData.push({
            coords : coordinates,
            imageUrl : resp2.data.data.imageUrl
          })
        })
      })
      this.roundsData = this.roundsData.slice(resp.data.advancement)

      console.log( "rounds Data ", this.roundsData)


    }).catch(err => {
      console.log("erreur dans le get games ", err)
    })

    //mettre le status à 1
    await this.$api.put(`games/${this.$route.params.id}`, {
      status: 1
    }).then(resp => {
      //console.log(" changement du status à 1 = en cours, ", resp.data)
    }).catch (err => {
      console.log("erreur dans le changement de status", err)
    })
    this.startTimer(); // Démarre le minuteur pour le premier round

    ws.init();
    console.log("this roundsData dans le mounted", this.roundsData);
    this.actualRound = this.roundsData.shift();
    console.log("actualRound", this.actualRound);
    this.originalPosition = this.actualRound.coords;
    //this.initialCenter = this.game.initialCenter;
    this.imageUrl = this.actualRound.imageUrl;
    this.roundNumber += 1;

    this.map = L.map('map').setView(this.initialCenter, 13).setMinZoom(12)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution:
          'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(this.map)

    this.map.on('click', (e) => {
      if (this.currentMarker) {
        this.map.removeLayer(this.currentMarker)
      }
      this.currentMarker = L.marker(e.latlng).addTo(this.map)
    })
    ws.sendMessage('newGame')
  },
  methods: {
    pause() {
      this.$api.put(`/games/${this.$route.params.id}`, {
        status: 1,
        score: this.totalScore,
        advancement: this.roundNumber
      })
      this.$router.push("/")
    },
	startTimer() {
      this.timer = this.maxTime; // réinitialise le timer

      if (this.timerInterval) {
        clearInterval(this.timerInterval); 
      }

      this.timerInterval = setInterval(() => {
      this.timer--; 

      if (this.timer <= 0) {
        clearInterval(this.timerInterval);
        	this.endRound(); // termine le tour si le temps est écoulé
      	}
      }, 1000);
    },
    endRound() {
      this.currentMarker = null;
      this.onSubmit();
    },
    nextRound() {
      this.startTimer();
      console.log("next ROund !", this.roundNumber)
      this.$api.put(`games/${this.$route.params.id}`, {
        score: this.totalScore,
        advancement: this.roundNumber
      })
      if (this.roundsData.length > 0 && this.roundNumber < 10) {
        this.actualRound = this.roundsData.shift();
        this.imageUrl = this.actualRound.imageUrl;
        this.originalPosition = this.actualRound.coords;
        this.roundNumber += 1

        if (this.currentMarker) this.map.removeLayer(this.currentMarker)
        this.currentMarker = null
        this.showPopup = false
        this.score = 0
        this.distanceBtwPoints = 0
        this.map.setView(this.initialCenter, 13).setMinZoom(12)
      } else {
        console.log('Fin du jeu ou limite des rounds atteinte.')
        ws.sendMessage(`endGame&${this.totalScore}`)
        this.$api.put(`/games/${this.$route.params.id}`, {
          status: 2,
          score: this.totalScore,
          advancement: this.roundNumber
        })
        this.$router.push('/')
      }
    },
		calculateScore(distance: number) {
      const timeElapsed = this.maxTime - this.timer; // Calcul du temps écoulé basé sur le timer
      let score = 0;
      
      if (distance < this.D) {
        score = 5;
      } else if (distance < (2 * this.D)) {
        score = 3;
      } else if (distance < (3 * this.D)) {
        score = 1;
      }

      if (timeElapsed <= 5) { // Moins de 5 secondes
        score *= 4;
      } else if (timeElapsed <= 10) { // Moins de 10 secondes
        score *= 2;
      } else if (timeElapsed > 20) { // Plus de 20 secondes
        score = 0; // Les points ne sont pas acquis
      }

      return score;
    },
    onSubmit() {
      clearInterval(this.timerInterval);

      if (this.currentMarker !== null) {
        this.showPopup = true
        this.$nextTick(() => {
          const userPosition = this.currentMarker.getLatLng()
          const originalPosition = this.originalPosition
          const distance = userPosition.distanceTo(originalPosition)
          this.distanceBtwPoints = Math.round(distance)
          this.score = this.calculateScore(this.distanceBtwPoints)
          this.totalScore += this.score

          const popupMap = L.map('popupMap', {
            layers: [
              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; OpenStreetMap contributors'
              })
            ]
          }).setMinZoom(12)
              .fitBounds([userPosition, this.actualRound.coords], {
                padding: [50, 50]
              })
          L.marker(userPosition, {
            icon: L.divIcon({
              html: '<img src="/avatar.svg" style="height: 40px; width: 40px; border: 3px solid red; border-radius: 50%" />'
            })
          })
              .bindTooltip('Votre choix', {permanent: true, direction: 'top'})
              .addTo(popupMap)
          L.marker(originalPosition, {title: 'test'})
              .bindTooltip('Réponse', {permanent: true, direction: 'top'})
              .addTo(popupMap)
          L.polyline([userPosition, originalPosition] as any, {dashArray: [10]}).addTo(popupMap)
          popupMap.invalidateSize()
        })
      }
     else {
      this.distanceBtwPoints = 0;
      this.score = 0;
      this.showPopup = true; // Affiche les résultats avec 0 à distance et 0 à score
      this.$nextTick(() => {
        const originalPosition = this.originalPosition


        const popupMap = L.map('popupMap', {

          layers: [
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: 'Map data &copy; OpenStreetMap contributors'
            })
          ]
        }).setMinZoom(12)
            .fitBounds([this.actualRound.coords], {
              padding: [50, 50]
            })
        L.marker(originalPosition)
            .bindTooltip('Réponse', {permanent: true, direction: 'top'})
            .addTo(popupMap)
        popupMap.invalidateSize()
      })
    }
  }
}}
</script>

<style scoped>
.minutor {
  position: fixed;
  bottom: 30px;
  left: 30px;
  width: 300px;
  height: 100px;
  background-color: #fff;
  color: #000;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 2em;
  border-radius: 10px;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
  padding: 10px;
}
.pause {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 1000;
}
.pause button{
  width: 200px;
  cursor: pointer;
  height: 60px;
  background-color: #fff;
  color: #000;
  font-size: 1.2em;
  border-radius: 10px;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
  padding: 10px;
}
body {
  margin: 0 !important;
}
p,
span {
  color: black !important;
}
.button-next {
  background-color: aliceblue;
  width: 100px;
  height: 30px;
  font-size: 16px;
  color: rgb(0, 0, 0);
  border: 1px solid;
  cursor: pointer;
}
.leaflet-popup {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1010;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
}
.leaflet-popup .flex {
  display: flex;
  justify-content: space-around;
  align-items: center;
  width: 100%;
}

.popup-map-container {
  height: 300px;
  width: 400px;
}
.confirm {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1000;
}
.confirm button {
  background-color: chartreuse;
  width: 100px;
  height: 30px;
  font-size: 16px;
  border-radius: 10px;
  color: rgb(0, 0, 0);
  cursor: pointer;
}
.confirm button:disabled {
  background-color: grey;
  color: white;
  cursor: not-allowed;
  opacity: 0.7;
}
.player {
  position: absolute;
  top: 30px;
  left: 30px;
  display: flex;
  align-items: center;
  justify-content: left;
  background-color: white;
  border-radius: 10px;
  padding: 5px;
  width: 210px;
}
.player .avatar {
  height: 50px;
  width: 50px;
  border-radius: 50%;
}
.player .progression {
  margin-left: 10px;
  display: flex;
  justify-content: left;
  align-items: start;
  flex-direction: column;
}
.player .progression .text {
  margin: 0;
}
#map {
  opacity: 0.8;
  height: 400px;
  width: 400px;
  animation: 0.3s;
  transition: all 0.1s ease-in-out;
}
#map:hover {
  opacity: 1;
  height: 500px;
  width: 500px;
}
.no-hover {
  visibility: hidden !important;
}
.container {
  position: relative;
  height: 100%;
  width: 100%;
}
.dynamic-image {
  width: 70vw;
  margin-left: auto;
  margin-right: auto;
  max-height: 100%;
  object-fit: cover;
  overflow: hidden;
}
.map-container {
  position: fixed;
  bottom: 80px;
  right: 20px;
  z-index: 1000;
  height: 200px;
  width: 300px;
}
@media screen and (max-width: 1500px) {
  #map.no-hover:hover {
    height: 300px;
    width: 300px;
  }

  #map {
    height: 300px;
    width: 300px;
  }
  #map:hover {
    height: 400px;
    width: 400px;
  }
}
</style>
