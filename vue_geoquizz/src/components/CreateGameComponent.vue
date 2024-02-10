<script>
export default {
  props: {
    series: {
      type: Object,
      required: true
    },
    levels: {
      type: Object,
      requird: true
    }
  }
}

</script>

<template>
  <div class="new-game-banner">
    <select v-model="series.serie_id">
      <option disabled selected>Choisir une ville</option>
      <option v-for="serie in series" :key="serie.id" :value="serie.id">{{ serie.city }}</option>
    </select>
    <select v-model="levels.level_id">
      <option disabled selected value="">Choisir un niveau</option>
      <option v-for="level in levels" :key="level.id" :value="level.id">{{ level.title }}</option>
    </select>
    <button class="new-game-button">Lancer</button>
  </div>
</template>

<style scoped lang="scss">
.new-game-banner {
  color: black;
  background-color: rgba(26, 26, 45, 0.42);
  border: 1px solid rgb(57, 56, 91);
  border-radius: 1rem;
  width: fit-content;
  margin-right: auto;
  margin-left: auto;
  padding: 1.5em;
  display: flex;
  flex-direction: row;
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
</style>