export default{
    state: {
        element_content: [],
        element_content_one: null,
        new_element_content: null,
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
    },
    actions: {
        async getElementContent(ctx, id){
            ctx.commit('setLoading', true);
            await axios.get('http://127.0.0.1:8000/element-content/findElementContentID/'+id)
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
            const new_element_content = await axios.post('http://127.0.0.1:8000/element-content/store/'+ form.type_content_id, form);
            ctx.commit('NEW', new_element_content.data);
            const element_content = await axios.get('http://127.0.0.1:8000/element-content/findElementContentID/'+form.type_content_id);
            ctx.commit('UPDATE', element_content.data)
        },
        async getElementContentOne({commit}, id) {
            //commit('setLoading', true);
            await axios.get('http://127.0.0.1:8000/type-content/getElementContentID/' + id)
                .then(response => {
                    commit('updateElementContentOne', response.data)
                })
                .catch(err => {
                    console.log(err)
                })
            /*.finally(() => {
                commit('setLoading', false);
            });*/
        }
    },
}
