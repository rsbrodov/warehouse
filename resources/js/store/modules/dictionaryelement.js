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
            await axios.post('http://127.0.0.1:8000/dictionary-element/create/', form);
            const dictionary_elements = await axios.get('http://127.0.0.1:8000/dictionary/findElementDictionaryID/' + form.form.dictionary_id);
            ctx.commit('UPDATE', dictionary_elements.data)
        },
        async deleteDictionaryElement(ctx, id){
            const dictionary_id_after_delete = await axios.delete('http://127.0.0.1:8000/dictionary-element/'+id);
            const dictionary_elements = await axios.get('http://127.0.0.1:8000/dictionary/findElementDictionaryID/'+dictionary_id_after_delete.data);
            ctx.commit('UPDATE', dictionary_elements.data)
        },
    },
}
