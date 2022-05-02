export default{
    state: {
        dictionary_element: [],
    },
    getters: {
        DictionaryElement(state){
            return state.dictionary_element
        },
    },
    mutations: {
        UPDATE(state, dictionary_element){
            state.dictionary_element = dictionary_element
        },
    },
    actions: {
        async getDictionaryElement(ctx, id){
            const dictionary_element = await axios.get('http://127.0.0.1:8000/dictionary/findElementDictionaryID/'+id);
            ctx.commit('UPDATE', dictionary_element.data)
        },
        async newDictionaryElement(ctx, form){
            const new_dictionary = await axios.post('http://127.0.0.1:8000/dictionary/store', form);
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary.data)
        },
        async deleteDictionaryElement(ctx, id){
            await axios.delete('http://127.0.0.1:8000/dictionary/'+id);
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary.data)
        },
    },
}
