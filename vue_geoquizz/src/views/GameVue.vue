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
  <div class="player">
    <img class="avatar" src="/avatar.svg" />
    <div class="progression">
      <p class="text">Score : {{ totalScore }}</p>
      <p class="text">Round : {{ roundNumber }}/10</p>
    </div>
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
import { GameProgression, User,  AxiosResponseGame, Game, Photo, CurrentGame, Serie} from '@/utils/types' // @TODO: à utiliser pour gerer et bloquer certains éléments du jeu

export default {
  data() {
    return {
      game: null as Game,
      photos: [] as Photo[],
      currentMarker: null as L.LatLngExpression,
      showPopup: false,
      distanceBtwPoints: 0,
      totalScore: 0,
      score: 0,
      roundNumber: 0,
      imageUrl: '',
      initialCenter: [48.693623, 6.183672] as L.LatLngExpression,
      gameProgression: {} as GameProgression,
      roundsData: [] as Photo[],
      actualRound: null as CurrentGame,
      originalPosition: [],
      map: null
    }
  },
  created() {
    //TODO: récup aussi le inital center avec directus serie CityCenter
    this.$api.get(`games/${this.$route.params.id}`).then((resp: AxiosResponseGame) => {
      console.log(" données de jeu ",resp.data)
      this.game = resp.data.game as Game;
      console.log(this.game)
      this.photos = JSON.parse(this.game.photos)
      //this.game.photos.replace("[",'')
      //this.game.photos.replace("]",'')
      this.photos.forEach((photo: Photo) => {
        this.$api.get(`/photos/${photo.id}`).then( resp2 => {
          //console.log("resp2 ", resp2);
          const coordinates = resp2.data.data.coordinates.split(',').map(function(coord) {
            return parseFloat(coord);
          });
          //console.log("coordinates ", coordinates)
          this.roundsData.push({
            coords : coordinates,
            imageUrl : resp2.data.data.imageUrl
          })
        })
      })
      console.log( "rounds Data ", this.roundsData)
    }).catch(err => {
      console.log("erreur dans le get games ", err)
    })

    //mettre le status à 1
    this.$api.put(`games/${this.$route.params.id}`, {
        status: 1
    }).then(resp => {
      console.log(" changement du status à 1 = en cours, ", resp.data)
    }).catch (err => {
      console.log("erreur dans le changement de status", err)
    })

  },
  mounted() {
    // this.ws.init();
    this.actualRound = this.roundsData.shift()
    console.log(this.actualRound)
    this.originalPosition = this.actualRound.coords
    this.imageUrl = this.actualRound.imageUrl
    this.roundNumber += 1

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
    nextRound() {
      if (this.roundsData.length > 0 && this.roundNumber < 10) {
        this.actualRound = this.roundsData.shift()
        this.imageUrl = this.actualRound.imageUrl
        this.roundNumber += 1

        if (this.currentMarker) this.map.removeLayer(this.currentMarker)
        this.currentMarker = null
        this.showPopup = false
        this.score = 0
        this.distanceBtwPoints = 0
        this.map.setView(this.actualRound.coords, 13)
      } else {
        console.log('Fin du jeu ou limite des rounds atteinte.')
      }
    },
    calculateScore(distance) {
      const maxDistance = 1000 // Distance maximale pour calculer le score
      const maxScore = 6000 // Score maximal possible
      return Math.max(0, maxScore - maxScore * (distance / maxDistance))
    },
    onSubmit() {
      if (this.currentMarker) {
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
              html: '<img src="/avatar.svg" style="height: 40px; width: 40px; border-radius: 50%" />'
            })
          })
            .bindTooltip('Votre choix', { permanent: true, direction: 'top' })
            .addTo(popupMap)
          L.marker(originalPosition, { title: 'test' })
            .bindTooltip('Réponse', { permanent: true, direction: 'top' })
            .addTo(popupMap)
          L.polyline([userPosition, originalPosition] as any, { dashArray: [10] }).addTo(popupMap)
          popupMap.invalidateSize()
        })
      }
    }
  }
}
</script>

<style scoped>
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
  width: auto;
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
