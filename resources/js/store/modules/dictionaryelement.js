export default{
    state: {
        dictionary_element: [],
        dictionary_element_one: '',
    },
    getters: {
        DictionaryElement(state){
            return state.dictionary_element
        },
        DictionaryElementOne(state){
            return state.dictionary_element_one
        },
    },
    mutations: {
        UPDATE(state, dictionary_element){
            state.dictionary_element = dictionary_element
        },
        DELETE(state, dictionary_element){
            state.dictionary_element = splice(dictionary_element);
        },
    },
    actions: {
        async getDictionaryElement(ctx, id){
            const dictionary_element = await axios.get('http://127.0.0.1:8000/dictionary/findElementDictionaryID/'+id);
            ctx.commit('UPDATE', dictionary_element.data)
        },
        async newDictionaryElement(ctx, form){
            const new_dictionary = await axios.post('http://127.0.0.1:8000/dictionary-element/create/', form);
            const dictionary_element = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary_element.data)
        },
        async deleteDictionaryElement(ctx, id){
            const dictionary_element_one = await axios.get('http://127.0.0.1:8000/dictionary/findID/'+id);
            //let dictionary = dictionary_element_one.data[0];
            await axios.delete('http://127.0.0.1:8000/dictionary-element/'+id);
           // const dictionary_element = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary/' + dictionary.dictionary_id);*/
            ctx.commit('DELETE', id)
        },
    },
}
