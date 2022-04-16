import Vue from 'vue';
import Vuex from 'vuex';

import type_content from './modules/typecontent';

Vue.use(Vuex);
export default new Vuex.Store({
    modules:{type_content}
});
