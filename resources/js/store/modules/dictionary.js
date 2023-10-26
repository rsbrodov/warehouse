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
        },
    },
    actions: {
        async getDictionary({commit}, params){
            commit('setLoading', true);
            await axios.get('/dictionary/findDictionary', params)
                .then(response => {
                    commit('UPDATE', response.data)
                })
                .catch(err => {
                    console.log(err)
                })
                .finally(() => {
                    commit('setLoading', false);
                });

        },
        async getDictionaryID(ctx, id){
            const dictionary = await axios.get('/dictionary/findDictionaryID/'+id);
            ctx.commit('ONE', dictionary.data.data)
        },
        async newDictionary(ctx, form){
            const new_dictionary = await axios.post('/dictionary/store', form);
            /*const dictionary = await axios.get('/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary.data.data)*/
        },
        async updateDictionary(ctx, form){
            const new_dictionary = await axios.post('/dictionary/'+form.id, form);
        },
        async deleteDictionary(ctx, id){
            await axios.delete('/dictionary/'+id);
            const dictionary = await axios.get('/dictionary/findDictionary');
            ctx.commit('UPDATE', dictionary.data.data)
        },
        async archiveDictionary(ctx, id){
            await axios.get('/dictionary/'+id+ '/archive');
        },
    },
}
