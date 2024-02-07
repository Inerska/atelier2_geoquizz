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
      required: false,
      default: "/public/img/Nancy.jpg"
    }
  }
}
</script>

<template>
  <div class="game-card">
    <img class="game-card-img" :src="photo" alt="photo de la ville">
    <div class="game-gard-text">
      <img v-if="level === 'easy'" class="level" src="/icons/easy.svg" alt="easy">
      <img v-if="level === 'medium'" class="level" src="/icons/medium.svg" alt="medium">
      <img v-if="level === 'hard'" class="level" src="/icons/hard.svg" alt="hard">
      <div class="serie">{{ serie }}</div>
    </div>
  </div>

</template>

<style scoped>

.game-card {
  color: white;
  border-radius: 7px;
  overflow: hidden;
  box-shadow: rgba(0, 0, 0, 0.19) 0px 8px 24px;
}

.game-card-img {
  width: 100%;
  height: 10em;
  object-fit: cover;
  transform: scale(1.1);
  filter: blur(1px) brightness(80%);
  transition: transform 0.5s, filter 0.5s;
  cursor: pointer;
}

.game-card-img:hover {
  filter: blur(1px) brightness(60%);
  transform: scale(1.2);
}

.game-gard-text {
  position: relative;
  z-index: 100;
  top: -2.3em;
  height: 0;
  display: flex;
  flex-direction: row;
  align-content: center;
  padding-right: 1.2em;
  padding-left: 1.2em;
  justify-content: space-between;
}

.serie {
  font-size: 1.4em;
  height: 1.2em;
  font-weight: bold
}

.level {
  height: 1.3em;
}

</style>