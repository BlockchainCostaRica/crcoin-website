 require('./bootstrap');
 import Vue from 'vue'
 import BuyCoin from './components/BuyCoin.vue'

window.Vue = require('vue');


const app = new Vue({
    el: '#app',
    components: {'buy-coin':BuyCoin }
});

