import Vue from 'vue';
import Vuex from 'vuex';

import loader from "./modules/loader";
import type_content from './modules/typecontent';
import dictionary from "./modules/dictionary";
import dictionary_element from "./modules/dictionaryelement";
import element_content from "./modules/elementcontent";

Vue.use(Vuex);
export default new Vuex.Store({
    modules:{loader, type_content, dictionary, dictionary_element, element_content}
});
