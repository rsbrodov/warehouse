export default{
    state: {
        type_contents: [],
        new_type_contents: '',
    },
    getters: {
        typeContents(state){
            return state.type_contents
        },
        newTypeContents(state){
            return state.new_type_contents
        },
    },
    mutations: {
        updateTypeContents(state, type_contents){
            state.type_contents = type_contents
        },
        addingTypeContents(state, newTypeContents){
            state.type_contents.unshift(newTypeContents)
        },
    },
    actions: {
        async getTypeContents({commit}) {
            commit('setLoading', true);
            await axios.get('http://127.0.0.1:8000/type-content/getListTypeContent')
                .then(response => {
                    commit('updateTypeContents', response.data)
                })
                .catch(err => {
                    console.log(err)
                })
                .finally(() => {
                    commit('setLoading', false);
                });

        },
        async newTypeContents(ctx, form){
            const new_dish = await axios.post('http://127.0.0.1:8000/type-content/store', form);
            ctx.commit('addingTypeContents', new_dish.data);
            const type_contents = await axios.get('http://127.0.0.1:8000/type-content/getListTypeContent');
            ctx.commit('updateTypeContents', type_contents.data)
        },
        async deleteDish(ctx, id){
            await axios.delete('api/dishes/'+id);
            const my_dishes = await axios.get('api/dishes/');
            ctx.commit('updateMyDishes', my_dishes.data)
        },
    },
}
