export default{
    state: {
        element_content: [],
        element_content_one: null,
        element_content_all: null,
        new_element_content: null,
        element_contents_all_version: [],
    },
    getters: {
        ElementContent(state){
            return state.element_content
        },
        NewElementContent(state){
            return state.new_element_content
        },
        elementContentOne(state){
            return state.element_content_one
        },
        ElementContentAll(state){
            return state.element_content_all
        },
        elementContentsAllVersion(state){
            return state.element_contents_all_version
        },
    },
    mutations: {
        UPDATE(state, element_content){
            state.element_content = element_content
        },
        NEW(state, new_element_content){
            state.new_element_content = new_element_content
        },
        updateElementContentOne(state, element_content_one){
            state.element_content_one = element_content_one
        },
        updateElementContentAll(state, element_content_all){
            state.element_content_all = element_content_all
        },
        updateElementContentsAllVersion(state, element_contents_all_version){
            state.element_contents_all_version = element_contents_all_version
        },
    },
    actions: {
        async getElementContent(ctx, params){
            ctx.commit('setLoading', true);
            await axios.get(BASE_URL + 'element-content/findElementContentID/'+params.id, {params:params.params})
            .then(response => {
                ctx.commit('UPDATE', response.data)
            })
            .catch(err => {
                console.log(err)
            })
            .finally(() => {
                ctx.commit('setLoading', false);
            });
        },
        async newElementContents(ctx, form){
            const new_element_content = await axios.post(BASE_URL + 'element-content/store/'+ form.type_content_id, form);
            ctx.commit('NEW', new_element_content.data.data);
            const element_content = await axios.get(BASE_URL + 'element-content/findElementContentID/'+form.type_content_id);
            ctx.commit('UPDATE', element_content.data.data)
        },
        async getElementContentOne({commit}, id) {
            //commit('setLoading', true);
            await axios.get(BASE_URL + 'element-content/getElementContentID/' + id)
                .then(response => {
                    commit('updateElementContentOne', response.data.data)
                })
                .catch(err => {
                    console.log(err)
                })
            /*.finally(() => {
                commit('setLoading', false);
            });*/
        },
        async getElementContentAll({commit}, params) {
            //commit('setLoading', true);
            await axios.get(BASE_URL + 'element-content/findElementContentAll', params)
                .then(response => {
                    commit('updateElementContentAll', response.data)
                })
                .catch(err => {
                    console.log(err)
                })
            /*.finally(() => {
                commit('setLoading', false);
            });*/
        },
        async updateElementContent(ctx, form){
            await axios.post(BASE_URL + 'element-content/'+form.id, form);
        },
        async getElementContentsAllVersion({commit}, id) {
            commit('setLoading', true);
            await axios.get(BASE_URL + 'element-content/getAllVersionElementContent/'+id)
                .then(response => {
                    commit('updateElementContentsAllVersion', response.data.data)
                })
                .catch(err => {
                    console.log(err)
                })
                .finally(() => {
                    commit('setLoading', false);
                });

        },
    },
}
