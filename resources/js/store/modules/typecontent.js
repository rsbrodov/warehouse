export default{
    state: {
        type_contents: [],
        new_type_contents: '',
        type_content_one: null,
        type_contents_all_version: [],
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
        typeContentsAllVersion(state){
            return state.type_contents_all_version
        },
    },
    mutations: {
        updateTypeContents(state, type_contents){
            state.type_contents = type_contents
        },
        updateTypeContentsAllVersion(state, type_contents_all_version){
            state.type_contents_all_version = type_contents_all_version
        },
        updateTypeContentOne(state, type_content_one){
            state.type_content_one = type_content_one
        },
        addingTypeContents(state, newTypeContents){
            state.type_contents.unshift(newTypeContents)
        },
    },
    actions: {
        async getTypeContents({commit}, params) {
            commit('setLoading', true);
            await axios.get(BASE_URL + 'type-content/getListTypeContent', params)
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
        async getTypeContentsAllVersion({commit}, id) {
            commit('setLoading', true);
            await axios.get(BASE_URL + 'type-content/getAllVersionTypeContent/'+id)
                .then(response => {
                    commit('updateTypeContentsAllVersion', response.data.data)
                })
                .catch(err => {
                    console.log(err)
                })
                .finally(() => {
                    commit('setLoading', false);
                });

        },
        async newTypeContents(ctx, form){
            const new_dish = await axios.post(BASE_URL + 'type-content/store', form);
        },
        async getTypeContentOne({commit}, id) {
            //commit('setLoading', true);
            await axios.get(BASE_URL + 'type-content/getTypeContentID/'+id)
                .then(response => {
                    commit('updateTypeContentOne', response.data.data)
                })
                .catch(err => {
                    console.log(err)
                })
                /*.finally(() => {
                    commit('setLoading', false);
                });*/


        },
        async update(ctx, form, id){
            const type_content = await axios.post(BASE_URL + 'type-content/'+id, form);
        },
    },
}
