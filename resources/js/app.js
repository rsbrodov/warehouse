require('./bootstrap');

window.Vue = require('vue').default;
import Vue from 'vue';
import Vuelidate from 'vuelidate'
import Moment from 'vue-moment';
import BootstrapVue from 'bootstrap-vue';

Vue.component('Index', require('./components/type-content/Index.vue').default);
Vue.component('DictionaryIndex', require('./components/dictionary/Index.vue').default);
Vue.use(Vuelidate)
Vue.use( Moment );
Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
});
