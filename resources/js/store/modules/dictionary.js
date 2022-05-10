export default{
    state: {
        dictionary: [],
        new_dictionary: '',
        one_dictionary: '',
    },
    getters: {
        Dictionary(state){
            return state.dictionary
        },
        newDictionary(state){
            return state.new_dictionary
        },
        oneDictionary(state){
            return state.one_dictionary
        },
    },
    mutations: {
        UPDATE(state, dictionary){
            state.dictionary = dictionary
        },
        ADDING(state, new_dictionary){
            state.dictionary.unshift(new_dictionary)
        },
        ONE(state, one_dictionary){
            state.one_dictionary = one_dictionary
        }
    },
    actions: {
        async getDictionary(ctx){
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary.data)
        },
        async getDictionaryID(ctx, id){
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionaryID/'+id);
            ctx.commit('ONE', dictionary.data)
        },
        async newDictionary(ctx, form){
            const new_dictionary = await axios.post('http://127.0.0.1:8000/dictionary/store', form);
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary.data)
        },
        async updateDictionary(ctx, id, form){
            const new_dictionary = await axios.put('http://127.0.0.1:8000/dictionary/'+id, form);
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary.data)
        },
        async deleteDictionary(ctx, id){
            await axios.delete('http://127.0.0.1:8000/dictionary/'+id);
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary.data)
        },
    },
}
