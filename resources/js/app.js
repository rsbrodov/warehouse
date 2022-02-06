require('./bootstrap');

window.Vue = require('vue').default;
import Vue from 'vue';
import Vuelidate from 'vuelidate'

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.use(Vuelidate)
Vue.component("login", require('./vue/login.vue').default);

const app = new Vue({
    el: '#app',
    //login
});
