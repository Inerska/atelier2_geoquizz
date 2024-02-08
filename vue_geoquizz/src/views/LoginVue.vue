<template>
  <div class="login-page">
    <HeaderComponent />
    <div class="login-container">
      <form class="login-form" @submit.prevent="onSubmit">
        <h2>Connexion</h2>
        <div class="form-group">
          <input type="email" id="email" placeholder=" " required />
          <label for="email">Email</label>
        </div>
        <div class="form-group">
          <input type="password" id="password" placeholder=" " required />
          <label for="password">Mot de passe</label>
        </div>
        <button type="submit" class="login-button">Se connecter</button>
      </form>
    </div>
    <FooterComponent />
  </div>
</template>

<script lang="ts">
import HeaderComponent from '@/components/HeaderComponent.vue'
import FooterComponent from '@/components/FooterComponent.vue'

export default {
  components: {
    HeaderComponent,
    FooterComponent
  },
  methods: {
    onSubmit() {
      console.log('Formulaire soumis !')
    }
  }
}
</script>

<style scoped>
h2,
label {
  color: #333 !important;
}
.form-group {
  position: relative;
  margin-bottom: 20px;
}

.form-group input {
  box-sizing: border-box;
  width: 100%;
  padding: 12px 10px 12px 15px !important;
  border: 1px solid #ccc;
  border-radius: 4px;
  transition: all 0.3s ease;
  font-size: 16px;
  margin: auto;
}

.form-group label {
  position: absolute;
  top: 12px;
  left: 10px;
  color: #000000;
  transition: all 0.2s ease;
  background-color: white;
  padding: 0 5px;
}

.form-group input:focus + label,
.form-group input:not(:placeholder-shown) + label {
  top: -8px;
  font-size: 12px;
  color: #007bff;
}

.form-group input:focus {
  border-color: #007bff;
}
.login-page {
  display: flex;
  flex-direction: column;
  height: 100vh;
  justify-content: space-between;
}

.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-grow: 1;
}

.login-form {
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  background-color: white;
}

.login-form h2 {
  margin-bottom: 2rem;
  text-align: center;
  color: #333;
}

.input-group {
  margin-bottom: 1rem;
}

.input-group input {
  width: 100%;
  padding: 0.75rem;
  border-radius: 4px;
  border: 1px solid #ccc;
  margin-top: 0.5rem;
}

.login-button {
  width: 100%;
  padding: 0.75rem;
  border-radius: 4px;
  border: none;
  background-color: #007bff;
  color: white;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-button:hover {
  background-color: #0056b3;
}
</style>
