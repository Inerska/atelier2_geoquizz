<template>
  <div class="login-page">
    <HeaderComponent />
    <div class="login-container">
      <div class="login-form">
        <h2>Inscription</h2>
        <div v-if="errorMessage" class="error">{{errorMessage}}</div>
        <div class="form-group">
          <input v-model="username" type="text" id="username" placeholder=" " required />
          <label for="username">Nom d'utilisateur</label>
        </div>
        <div class="form-group">
          <input v-model="mail" type="email" id="email" placeholder=" " required />
          <label for="email">Email</label>
        </div>
        <div class="form-group">
          <input v-model="password" type="password" id="password" placeholder=" " required />
          <label for="password">Mot de passe</label>
        </div>
        <div class="form-group">
          <input v-model="password2" type="password" id="password" placeholder=" " required />
          <label for="password">Confirmer le mot de passe</label>
        </div>
        <button @click="register()" class="login-button">S'inscrire</button>
      </div>
    </div>
    <FooterComponent />
  </div>
</template>

<script lang="ts">
import HeaderComponent from '@/components/HeaderComponent.vue'
import FooterComponent from '@/components/FooterComponent.vue'
import { mapState, mapActions } from 'pinia'
import { useUserStore } from '@/store/user'

export default {
  components: {
    HeaderComponent,
    FooterComponent
  },
  data() {
    return {
      mail : "",
      password : "",
      password2 : "",
      username: "",
      errorMessage : null
    }
  },
  methods: {
    ...mapActions(useUserStore, ['loginUser']),
    register() {
      console.log('Inscription !')
      this.$api.post('/register', {
        username: this.username,
        mail: this.mail,
        password: this.password,
        confirm_password: this.password2
      }).then(resp => {
        this.loginUser(resp.data.profileId, resp.data.refreshToken, resp.data.accessToken)
        this.errorMessage = null;
        console.log(resp.data)
        this.$router.push('/')
      }).catch (err => {
        console.log(err.response.data)
        this.errorMessage = err.response.data.message
      })
    }
  }
}
</script>

<style scoped lang="scss">
h2,
label {
  color: #333 !important;
}
.error {
  color: black;
  background-color: lighten(red, 30%);
  padding: 1em;
  text-align: center;
  border-radius: 10px;
  margin-bottom: 2em;
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
  color: #999;
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
