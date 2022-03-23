/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import VueRouter from "vue-router";
import App from './components/app.vue';
import swal from "sweetalert2";
import Vuelidate from 'vuelidate'

import {stores} from './components/store/store'

import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(Vuelidate);

window.swal = swal;
const toast = swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000
});
window.toast = toast;

const routes=[
    {
        path:'*',
        name:'login',
        component:require("./components/login.vue").default
    },
    {
        path:'/dashboard',
        name:'dashboard',
        component:require("./components/pages/dashboard.vue").default
    },
    {
        path:'/agents',
        name:'agents',
        component:require("./components/pages/agent.vue").default
    },
    {
        path:'/sites',
        name:'sites',
        component:require("./components/pages/sites.vue").default
    },
    {
        path:'/farmers',
        name:'farmers',
        component:require("./components/pages/farmers.vue").default
    },
    {
        path:'/harvest',
        name:'harvest',
        component:require("./components/pages/harvest.vue").default
    },
    {
        path:'/payments',
        name:'payments',
        component:require("./components/pages/payments.vue").default
    },
    {
        path:'/transactions',
        name:'transactions',
        component:require("./components/pages/transaction.vue").default
    },
    {
        path:'/sendSms',
        name:'sendSms',
        component:require("./components/pages/sendSms.vue").default
    },
    {
        path:'/actionSite',
        name:'actionSite',
        component:require("./components/pages/actionSite").default
    },
    {
        path:'/actionAgent',
        name:'actionAgent',
        component:require("./components/pages/actionAgent").default
    },
    {
        path:'/price',
        name:'price',
        component:require("./components/pages/price.vue").default
    },
]
const router = new VueRouter({
    mode: "hash",
    routes
});
const app = new Vue({
    el: '#app',
    render:h=>h(App),
    store:stores,
    router
});
