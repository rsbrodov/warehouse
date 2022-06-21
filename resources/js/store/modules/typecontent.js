export default{
    state: {
        type_contents: [],
        new_type_contents: '',
        type_content_one: null,
    },
    getters: {
        typeContents(state){
            return state.type_contents
        },
        typeContentOne(state){
            return state.type_content_one
        },
        newTypeContents(state){
            return state.new_type_contents
        },
    },
    mutations: {
        updateTypeContents(state, type_contents){
            state.type_contents = type_contents
        },
        updateTypeContentOne(state, type_content_one){
            state.type_content_one = type_content_one
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
        async getTypeContentOne({commit}, id) {
            //commit('setLoading', true);
            await axios.get('http://127.0.0.1:8000/type-content/getTypeContentID/'+id)
                .then(response => {
                    commit('updateTypeContentOne', response.data)
                })
                .catch(err => {
                    console.log(err)
                })
                /*.finally(() => {
                    commit('setLoading', false);
                });*/
            

        },
        async update(ctx, form, id){
            const type_content = await axios.post('http://127.0.0.1:8000/type-content/'+id, form);
            const type_contents = await axios.get('http://127.0.0.1:8000/type-content/getListTypeContent');
            ctx.commit('updateTypeContents', type_contents.data)
        },
    },
}
