require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import store from './store';


Vue.use(VueRouter);
Vue.use(Vuex);

window.Vue = require('vue');
window.swal = require('sweetalert');





Vue.component('products', require('./components/Products.vue').default);
Vue.component('dashboard', require('./components/etc.vue').default);



const app = new Vue({
    el: '#app',
    store,
});
