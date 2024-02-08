import './assets/main.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import api from './plugins/api'
import 'leaflet/dist/leaflet.css';

const app = createApp(App)

app.use(router)
app.use(api)


app.mount('#app')
