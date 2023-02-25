/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.axios = require('axios');
window.moment = require('moment');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Vue from 'vue'

import App from './App.vue'
import router from './router'

import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'

Vue.use(ElementUI, {
    locale
});


Vue.filter('formatDate', function (v) {
    return v ? moment(v).format('DD MMMM YYYY H:mm:ss') : ''
})



const app = new Vue({
    el: '#app',
    router,
    render: h => h(App)
});
