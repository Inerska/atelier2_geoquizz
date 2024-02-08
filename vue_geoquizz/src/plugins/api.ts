import axios from 'axios';

export default {
    install: function (app) {
        app.config.globalProperties.$api = axios.create({
            baseURL : '/gateway',
            headers: {
                'Content-Type': 'application/json',
            }
        })
    }
}