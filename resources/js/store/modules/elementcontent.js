export default{
    state: {
        element_content: [],
    },
    getters: {
        ElementContent(state){
            return state.element_content
        },
    },
    mutations: {
        UPDATE(state, element_content){
            state.element_content = element_content
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
    },
}
