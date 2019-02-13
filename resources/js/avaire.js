
require('./bootstrap');

import Vue from 'vue';
import router from './router';

import App from './views/App';

const app = new Vue({
    el: '#avaire',
    components: {
        App
    },
    router,
});