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
import { defineComponent, onMounted, ref, nextTick } from 'vue'
import * as L from 'leaflet'
import { ws } from '@/utils/WebSocketService'

export default defineComponent({
  setup() {
    const currentMarker = ref<L.Marker | null>(null)
    const showPopup = ref(false)
    const distanceBtwPoints = ref(0)
    const totalScore = ref(0)
    const score = ref(0)
    const roundNumber = ref(0)
    const imageUrl = ref('')
    const initialCenter = ref([48.693623, 6.183672] as L.LatLngExpression)

    const roundsData = ref([
      { coords: [48.693623, 6.183672] as L.LatLngExpression, imageUrl: '/img/Nancy.jpg' },
      { coords: [45.693623, 7.183672] as L.LatLngExpression, imageUrl: '/img/Nancy2.jpg' },
      { coords: [48.693623, 6.183672] as L.LatLngExpression, imageUrl: '/img/Nancy3.jpg' },
      { coords: [48.693623, 6.183672] as L.LatLngExpression, imageUrl: '/img/Nancy4.jpg' },
      { coords: [48.693623, 6.183672] as L.LatLngExpression, imageUrl: '/img/Nancy5.jpg' }
    ])
    const actualRound = ref(roundsData.value.shift())
    const originalPosition = actualRound.value.coords as L.LatLngExpression
    imageUrl.value = actualRound.value.imageUrl as string
    roundNumber.value += 1

    const map = ref(null)

    onMounted(() => {
      ws.connect('ws://localhost:5200')

      ws.sendMessage('newGame')

      map.value = L.map('map').setView(initialCenter.value, 13).setMinZoom(12)

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:
          'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map.value)

      map.value.on('click', (e) => {
        if (currentMarker.value) {
          map.value.removeLayer(currentMarker.value as L.Marker)
        }
        currentMarker.value = L.marker(e.latlng).addTo(map.value)
      })
    })

    const nextRound = () => {
      if (roundsData.value.length > 0 && roundNumber.value < 10) {
        actualRound.value = roundsData.value.shift() // Retire le premier élément du tableau
        imageUrl.value = actualRound.value.imageUrl // Met à jour l'URL de l'image
        roundNumber.value += 1 // Incrémente le numéro de round

        map.value.removeLayer(currentMarker.value as L.Marker)

        currentMarker.value = null
        showPopup.value = false
        score.value = 0
        distanceBtwPoints.value = 0

        // Met à jour la carte avec les nouvelles coordonnées
        map.value.setView(actualRound.value.coords, 13)
        // Assurez-vous de nettoyer la carte des marqueurs précédents si nécessaire
      } else {
        // Gérer la fin du jeu ou la réinitialisation complète ici
        console.log('Fin du jeu ou limite des rounds atteinte.')
      }
    }

    const calculateScore = (distance: number) => {
      const maxDistance = 1000 //5 km
      const maxScore = 6000 // Score maximal
      return Math.round(Math.max(0, maxScore - maxScore * (distance / maxDistance)))
    }

    const onSubmit = () => {
      if (currentMarker.value) {
        showPopup.value = true

        // S'assurer que la popup est visible avant d'initialiser la carte
        nextTick(() => {
          const userPosition = currentMarker.value.getLatLng()
          const popupMap = L.map('popupMap')
            .setMinZoom(12)
            .fitBounds([userPosition, roundsData.value[0].coords] as any, {
              padding: [50, 50]
            })

          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution:
              'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(popupMap)

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

          distanceBtwPoints.value = Math.round(userPosition.distanceTo(originalPosition))
          score.value = calculateScore(distanceBtwPoints.value) // Calculer le score
          totalScore.value = totalScore.value + score.value

          popupMap.invalidateSize()
        })
      }
    }

    return {
      currentMarker,
      nextRound,
      showPopup,
      onSubmit,
      distanceBtwPoints,
      score,
      imageUrl,
      roundNumber,
      totalScore
    }
  }
})
</script>

<style>
body {
  margin: 0 !important;
}
p {
  color: black;
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
  position: absolute;
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
