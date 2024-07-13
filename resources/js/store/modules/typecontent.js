export default{
    state: {
        type_contents: [],
        new_type_contents: '',
        type_content_one: null,
        tariffs_list: null,
        type_contents_all_version: [],
        type_content_errors: null
    },
    getters: {
        typeContents(state){
            return state.type_contents
        },
        typeContentOne(state){
            return state.type_content_one
        },
        tariffList(state){
            return state.tariffs_list
        },
        newTypeContents(state){
            return state.new_type_contents
        },
        typeContentsAllVersion(state){
            return state.type_contents_all_version
        },
        typeContentErrors(state){
            return state.type_content_errors
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
        TP_ERRORS(state, errors){
            state.type_content_errors = errors
        },
    },
    actions: {
        async getTypeContents({commit}, params) {
            console.log('updateTypeContents')
            commit('setLoading', true);
            await axios.get('/clients/getListClients', params)
                .then(response => {
                    commit('updateTypeContents', response.data)
                    commit('TP_ERRORS', null)
                    commit('setLoading', false);

                })
                .catch(err => {
                    console.log(err.response)
                    commit('TP_ERRORS', {status: err.response.status, code: err.response.data.message})
                    commit('updateTypeContents', [])
                })
                .finally(() => {
                    commit('setLoading', false);
                });

        },
        async getTypeContentsAllVersion({commit}, id) {
            commit('setLoading', true);
            await axios.get('/type-content/getAllVersionTypeContent/'+id)
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
            const new_dish = await axios.post('/type-content/store', form);
        },
        async getTypeContentOne({commit}, id) {
            //commit('setLoading', true);
            await axios.get('/clients/getClientByID/'+id)
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
            const type_content = await axios.post('/type-content/'+id, form);
        },
        async getTariffsList({commit}, params){
            commit('setLoading', true);
            await axios.get('/tariffs/getListTariffs', params)
                .then(response => {
                    console.log(response)
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
    },
}
