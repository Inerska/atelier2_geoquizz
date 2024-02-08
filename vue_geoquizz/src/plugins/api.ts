import axios from 'axios';

export default {
    install: function (app) {
        app.config.globalProperties.$api = axios.create({
            baseURL : '/gateway/api/v1',
            headers: {
                'Content-Type': 'application/json',
            }
        })
    }
}