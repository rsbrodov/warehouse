export default{
    state: {
        dictionary: [],
        new_dictionary: '',
    },
    getters: {
        Dictionary(state){
            return state.dictionary
        },
        newDictionary(state){
            return state.new_dictionary
        },
    },
    mutations: {
        UPDATE(state, dictionary){
            state.dictionary = dictionary
        },
        ADDING(state, new_dictionary){
            state.dictionary.unshift(new_dictionary)
        }
    },
    actions: {
        async getDictionary(ctx){
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary.data)
        },
        async newDictionary(ctx, form){
            const new_dictionary = await axios.post('http://127.0.0.1:8000/dictionary/store', form);
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
