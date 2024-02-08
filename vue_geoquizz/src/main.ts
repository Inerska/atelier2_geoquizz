import './assets/main.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import api from './plugins/api'

import 'leaflet/dist/leaflet.css';
import { createPinia } from 'pinia';

const pinia = createPinia();

const app = createApp(App)

app.use(router)
app.use(api)
app.use(pinia);


app.mount('#app')
