import Vue from 'vue';
import Vuex from 'vuex';

import type_content from './modules/typecontent';
import dictionary from "./modules/dictionary";
import dictionary_element from "./modules/dictionaryelement";

Vue.use(Vuex);
export default new Vuex.Store({
    modules:{type_content, dictionary, dictionary_element}
});
