import axios from 'axios';

export default {
    install: function (app) {
        app.config.globalProperties.$api = axios.create({
            baseURL : 'https://timely.edu.netlor.fr/api/',
            headers: {
                'Content-Type': 'application/json',
            }
        })
    }
}