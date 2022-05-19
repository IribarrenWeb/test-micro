import { createStore } from 'vuex';
import service from './services/auth-service';

export const store = createStore({
    state() {
        return {
            me: null,
            isAuthenticated: null,
            loader: false,
            tokenName: process.env.VUE_APP_USER_TOKEN_NAME,
            apiErrors: {},
        }
    },
    getters: {
        isAuthenticated(state) {
            return state.isAuthenticated;
        },
        getToken() {
            return service.getToken()
        },
        me(state) {
            return state.me
        }
    },
    mutations: {
        loading(state) {
            state.loader = !state.loader
        },
        SET_RESOURCE: (state, me) => {
            state.me = me;
        },
        isAuthenticated(state, payload) {
            state.isAuthenticated = payload.isAuthenticated;
        },
        resetApiErrors(state) {
            state.apiErrors = {}
        },
        setErrors(state, payload) {
            state.apiErrors = payload
        },
    },
    actions: {
        async login(context, payload) {
            console.log('hoallalas');
            return await service.login(payload.user).then(() => {
                // context.state.isAuthenticated = context.dispatch.isAuth()
                store.dispatch('me')
            });
        },
        setApiValidation({ commit }, serverErrors) {
            let errors = {};
            Object.keys(serverErrors).forEach((element) => {
                errors[element] = serverErrors[element];
            });

            commit('setErrors', errors);
        },
        logout({ state }) {
            localStorage.removeItem(state.tokenName);
            window.location.href = ''
            return true
        },
        isAuth({ state }) {
            console.log('aSDKJASHDKJASHDKJASHDKJASHDKJASH');
            var token = localStorage.getItem(state.tokenName);
            if (token) { // Token is present
                if (token.split('.').length === 3) { // Token with a valid JWT format XXX.YYY.ZZZ
                    try { // Could be a valid JWT or an access token with the same format
                        var base64Url = token.split('.')[1];
                        var base64 = base64Url.replace('-', '+').replace('_', '/');
                        var exp = JSON.parse(window.atob(base64)).exp;
                        if (typeof exp === 'number') { // JWT with an optonal expiration claims
                            return Math.round(new Date().getTime() / 1000) < exp;
                        }
                    } catch (e) {
                        return true; // Pass: Non-JWT token that looks like JWT
                    }
                }
                return true; // Pass: All other tokens
            }
            return false
        },
        me({ commit }, params) {
            return service.get(params)
                .then((profile) => {
                    console.log(profile);
                    commit('SET_RESOURCE', profile.list.user);
                });
        },
    },
});