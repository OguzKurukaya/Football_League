import './bootstrap';

import {createApp} from 'vue';

import app from './component/app.vue';

import router from './router';

createApp(app).use(router).mount('#app');

import "bootstrap/dist/css/bootstrap.min.css";
