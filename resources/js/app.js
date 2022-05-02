require('./bootstrap');

window.Vue = require('vue').default;
import Vue from 'vue';
import Vuelidate from 'vuelidate'
import Moment from 'vue-moment';
import BootstrapVue from 'bootstrap-vue';
import store from './store/index'

Vue.component('Index', require('./components/type-content/Index.vue').default);
Vue.component('Indexdictionary', require('./components/dictionary/Index.vue').default);
Vue.component('Indexdictionaryelement', require('./components/dictironary-element/Index.vue').default);
Vue.use(Vuelidate)
Vue.use( Moment );
Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
    store
});
