export default {
    namespaced: true,
    state:{
        authenticated:false,
        user:{},
        token: null
    },
    getters:{
        authenticated(state){
            return state.authenticated
        },
        user(state){
            return state.user
        },
        token(state){
           return state.token
        }
    },
    mutations:{
        SET_AUTHENTICATED (state, value) {
            state.authenticated = value
        },
        SET_USER (state, value) {
            state.user = value
        },
        SET_TOKEN (state, value) {
            state.token = value
        }
    },
    actions:{
        signin({commit}, payload){
           commit('SET_TOKEN', payload.access_token)
           commit('SET_USER', payload.user)
           commit('SET_AUTHENTICATED', true)
        },
        logout({commit}){
            commit('SET_TOKEN', null)
            commit('SET_USER', {})
            commit('SET_AUTHENTICATED', false)
        },
    }
}
