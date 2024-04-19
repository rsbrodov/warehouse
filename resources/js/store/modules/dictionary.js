export default{
    state: {
        dictionary: [],
        new_dictionary: '',
        one_dictionary: '',
        dictionary_errors: null
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
        dictionaryErrors(state){
            return state.dictionary_errors
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
        D_ERRORS(state, errors){
            state.dictionary_errors = errors
        },
    },
    actions: {
        async getDictionary({commit}, params){
            commit('setLoading', true);
            await axios.get('/tariffs/getListTariffs', params)
                .then(response => {
                    commit('UPDATE', response.data)
                    commit('D_ERRORS', null)
                })
                .catch(err => {
                    console.log(err)
                    commit('D_ERRORS', {status: err.response.status, code: err.response.data.message})
                    commit('UPDATE', [])
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
            const new_dictionary = await axios.post('/tariffs/'+form.id, form);
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
