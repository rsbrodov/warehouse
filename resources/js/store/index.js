import Vue from 'vue';
import Vuex from 'vuex';

import type_content from './modules/typecontent';
import dictionary from "./modules/dictionary";

Vue.use(Vuex);
export default new Vuex.Store({
    modules:{type_content, dictionary}
});
