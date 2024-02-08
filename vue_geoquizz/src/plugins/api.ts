import axios from 'axios';

export default {
    install: function (app) {
        app.config.globalProperties.$api = axios.create({
            baseURL : 'http://gateway_nginx/api/v1/',
            //baseURL : 'http://localhost:8084/api/v1/',
            headers: {
                'Content-Type': 'application/json',
                //'Access-Control-Allow-Origin' : '*',
                //scheme: 'http',
            }
        })
    }
}