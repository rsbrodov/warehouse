export default{
    state: {
        dictionary: [],
        new_dictionary: '',
    },
    getters: {
        Dictionary(state){
            return state.dictionary
        },
        new_Dictionary(state){
            return state.new_dictionary
        },
    },
    mutations: {
        updateDictionary(state, dictionary){
            state.dictionary = dictionary
        },
        addingDictionary(state, new_Dictionary){
            state.dictionary.unshift(new_Dictionary)
        },

    },
    actions: {
        async getDictionary(ctx){
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('updateDictionary', dictionary.data)
        },
        async newDictionary(ctx, form){
            const new_dictionary = await axios.post('http://127.0.0.1:8000/type-dictionary/store', form);
            ctx.commit('addingDictionary', new_dictionary.data);
            const dictionary = await axios.get('http://127.0.0.1:8000/dictionary/findDictionary');
            ctx.commit('updateDictionary', dictionary.data)
        },
        async deleteDictionary(ctx, id){
            await axios.delete('api/dishes/'+id);
            const my_dishes = await axios.get('api/dishes/');
            ctx.commit('updateMyDishes', my_dishes.data)
        },
    },
}
