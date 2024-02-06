<template>
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
  />
  <div class="container">
    <img src="./../assets/img/Nancy.jpg" alt="Image dynamique" class="dynamic-image" />
    <div id="map" class="map-container"></div>
  </div>
  <div class="confirm">
    <button :disabled="!currentMarker" @click="confirmMarker">Confirmer</button>
  </div>
  <div class="player">
    <img class="avatar" src="/avatar.svg" />
    <div class="progression">
      <p class="text">Score : 0</p>
      <p class="text">Temps restant : 0:00</p>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'
import * as L from 'leaflet'

export default defineComponent({
  setup() {
    const imageUrl = ref('')
    const currentMarker = ref<L.Marker | null>(null) // Utiliser ref pour une réactivité
    const initialCenter = [48.693623, 6.183672] as L.LatLngExpression
    const originalPosition = [48.6939657285046, 6.183735251033795] as L.LatLngExpression

    onMounted(() => {
      const map = L.map('map').setView(initialCenter, 13).setMinZoom(12).setMaxZoom(18)

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:
          'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map)

      map.on('click', (e) => {
        if (currentMarker.value) {
          map.removeLayer(currentMarker.value as any)
        }
          currentMarker.value = L.marker(e.latlng).addTo(map)
                calculateAndDisplayDistance(e.latlng);

      })
    })

    // Méthode pour gérer la confirmation du marqueur
    const confirmMarker = () => {
      if (currentMarker.value) {
        console.log('Marqueur confirmé à :', currentMarker.value.getLatLng())        
        // Ici, vous pouvez ajouter d'autres logiques, comme envoyer les coordonnées à un serveur
      }
    }

    const calculateAndDisplayDistance = (latlng: L.LatLng) => {
      if (!originalPosition) return
      const distance = L.latLng(latlng).distanceTo(originalPosition)
      console.log(distance.toFixed(2))
    }

    return { imageUrl, currentMarker, confirmMarker }
  },
  methods: {}
})
</script>

<style>

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
.container {
  position: relative;
  height: 100%;
  width: 100%;
}
.dynamic-image {
  width: 100%;
  height: auto;
}
.map-container {
  position: absolute;
  bottom: 80px;
  right: 20px;
  z-index: 1000;
  height: 200px;
  width: 300px;
}
@media screen and (max-width: 1500px) {
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
