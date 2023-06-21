import AllversionElementContent from "./components/element-content/AllversionElementContent";

require('./bootstrap');

window.Vue = require('vue').default;
import Vue from 'vue';
import Vuelidate from 'vuelidate'
import Moment from 'vue-moment';
import BootstrapVue from 'bootstrap-vue';
import FlashMessage from '@smartweb/vue-flash-message';
import Sortable from 'vue-sortable'


import store from './store/index'
import JwPagination from 'jw-vue-pagination';
Vue.component('jw-pagination', JwPagination);

Vue.component('Index', require('./components/type-content/Index.vue').default);
Vue.component('Indexdictionary', require('./components/dictionary/Index.vue').default);
Vue.component('Indexdictionaryelement', require('./components/dictionary-element/Index.vue').default);
Vue.component('Viewtype', require('./components/type-content/Viewtype.vue').default);
Vue.component('Indexelementcontent', require('./components/element-content/Index.vue').default);
Vue.component('Indexelementcontentall', require('./components/element-content/IndexAll.vue').default);
Vue.component('Allversion', require('./components/type-content/Allversion.vue').default);
Vue.component('Allversionelementcontent', require('./components/element-content/AllversionElementContent.vue').default);
Vue.component('Enter', require('./components/type-content/Enter').default);
Vue.component('Sidebar', require('./components/helpers/Sidebar').default);

Vue.use(Vuelidate)
Vue.use( Moment );
Vue.use(BootstrapVue);
Vue.use(FlashMessage);
Vue.use(Sortable)
window.BASE_URL = process.env.MIX_BASE_URL;
const app = new Vue({
    el: '#app',
    store,
});
