import axios from "axios";
import { store } from "@/store";

axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';

const tokenName = process.env.VUE_APP_USER_TOKEN_NAME

axios.interceptors.request.use(function(config) {
    store.commit('resetApiErrors')
    const token = localStorage.getItem(tokenName);
    config.baseURL = process.env.VUE_APP_API_BASE_URL;

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, function(err) {
    return Promise.reject(err);
});

axios.interceptors.response.use(function(response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response;
}, function(error) {
    const status = error.response.status;
    if (status == 401) {
        store.dispatch('logout');
    } else if (status == 422) {
        store.commit('setErrors', error.response.data.errors)
    }
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error);
});

export { axios };