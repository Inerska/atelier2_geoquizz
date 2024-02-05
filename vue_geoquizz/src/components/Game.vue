<script lang="ts">
export default {
  props: {
    serie: {
      type: String,
      required: true
    },
    level: {
      type: String,
      required: true
    },
    photo: {
      type: String,
      required: true
    }
  }

}
</script>

<template>
  <div class="game-card">
    <img :src="photo" alt="photo" />
    <div class="card-body">
      <p class="card-text">Partie sur {{serie}}</p>
      <p class="card-text">Niveau {{level}}</p>
    </div>
  </div>

</template>