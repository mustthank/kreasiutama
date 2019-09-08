
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue';
window.Vue = require('vue');
import Vuetify from 'vuetify';
import VueRouter from 'vue-router';
import {routes} from './routes';
import store from './store.js';
import MainApp from './components/MainApp.vue';
import {initialize} from './helpers/general';
import { Form, HasError, AlertError } from 'vform';
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
// import Gate from "./Gate";
// Vue.prototype.$gate = new Gate(window.user);
// Vue.prototype.$user = window.user;
Vue.use(VueRouter);
Vue.use(Vuetify);
const swal = require('sweetalert2');
window.swal = swal;


const router=new VueRouter({
    routes,
    mode:'history',
})

Vue.component('material-stats-card',require('./components/material/StatsCard.vue'));
Vue.component('material-card',require('./components/material/Card.vue'));
Vue.component('helper-offset',require('./helpers/Offset.vue'));
initialize(store, router);
window.Fire = new Vue();

const app = new Vue({
    el: '#app',
    router,
    store,
   components:{
       MainApp
   },
   data () {
    return {
        isDark:true,
        themeColor:'green'
    }
}
});
