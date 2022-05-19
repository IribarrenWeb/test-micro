import { axios } from '@/axios';
import { store as storage } from '@/store';
import $Swal from 'sweetalert2';

const url = process.env.VUE_APP_API_BASE_URL;
const tokenName = process.env.VUE_APP_USER_TOKEN_NAME
const $swal = $Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-outline-primary'
    },
    buttonsStyling: false,
    cancelButtonText: "Cancelar"
})

function login(user_data) {
    return axios.post(url + '/login', user_data).then((response) => {
        console.log(response);
        setToken(response)
        return response
    });
}

function isAuth() {
    console.log('asdasd');
    var token = localStorage.getItem(tokenName);
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
}

function setToken(response) {
    if (response.data.status == 'false') {
        return false
    }

    var token = response.data.token;

    if (token) {
        localStorage.setItem(tokenName, token);
    }
};

function logout() {
    if (!isAuth()) {
        return alert('There is no currently authenticated user')
            // return new Error('There is no currently authenticated user')
    }
    localStorage.removeItem(tokenName);
    return true
}

function getToken() {
    return localStorage.getItem(tokenName);
}

function get() {

    return axios.get(`${url}/me`).then((response) => {
        return {
            list: response.data,
            meta: response.data.meta,
        };
    });
}

async function index(page = 1, params) {
    storage.commit('loading');
    let url_model = `${url}/users?`;

    if (page != null) {
        url_model += `page=${page}&`
    }

    url_model += params

    return await axios.get(url_model).then((response) => {
        storage.commit('loading');
        return response
    }).catch(err => {
        storage.commit('loading');
        const status = err.response.status
        let message = null;
        if (status == 422) {
            message = err.response.data.message
        }
        errors(status, message)
    });
}

async function types(params) {
    storage.commit('loading');
    let url_model = `${url}/types?`;

    url_model += params

    return await axios.get(url_model).then((response) => {
        storage.commit('loading');
        return response
    }).catch(err => {
        storage.commit('loading');
        const status = err.response.status
        let message = null;
        if (status == 422) {
            message = err.response.data.message
        }
        errors(status, message)
    });
}

function store(payload) {
    const options = {};

    storage.commit('loading');
    return axios.post(`${url}/users`, payload, options).then((response) => {
        $swal.fire('Registro completado', 'El registro se ha completado correctamente', 'success')
        storage.commit('loading');
        return response
    }).catch(err => {
        storage.commit('loading');
        const status = err.response.status
        console.log(status);

        let message = null;
        if (status == 422) {
            message = err.response.data.message
        }
        errors(status, message)
        throw Error('Error');

    });;
}

function show(id, params = "") {

    storage.commit('loading');
    return axios.get(`${url}/users/${id}?${params}`).then((response) => {
        storage.commit('loading');
        return response
    }).catch(err => {
        storage.commit('loading');
        const status = err.response.status

        let message = null;
        if (status == 422) {
            message = err.response.data.message
        }
        errors(status, message)
        throw Error('Error');
    });
}

async function destroy(id) {
    storage.commit('loading');
    let deletes = false
    await $swal.fire({
        title: "¿Estas seguro de esto?",
        text: "Una vez eliminado no podra recuperarse este registro",
        icon: "warning",
        showCancelButton: true,
        showLoaderOnConfirm: true
    }).then((res) => {
        if (res.isConfirmed) {
            deletes = true
        } else {
            deletes = false
        }
    })

    if (deletes) {
        return axios.delete(`${url}/users/${id}`).then((response) => {
            storage.commit('loading');
            return response
        }).catch(err => {
            storage.commit('loading');
            const status = err.response.status

            let message = null;
            if (status == 422) {
                message = err.response.data.message
            }
            errors(status, message)
            throw Error('Error');
        })
    } else {
        storage.commit('loading');
        return null
    }
}

function update(id, data) {
    storage.commit('loading');
    const options = {};


    return axios.post(`${url}/users/${id}?_method=PUT`, data, options).then((response) => {
        storage.commit('loading');
        $swal.fire('Registro actualizado', 'El registro se ha actualizado correctamente', 'success')
        return response
    }).catch(err => {
        storage.commit('loading');
        const status = err.response.status

        let message = null;
        if (status == 422 || status == 403) {
            message = err.response.data.message
        }
        errors(status, message)
        throw Error(message ? message : 'Error');
    });
}

function errors(code, message = null) {
    switch (code) {
        case 500:
            // router.back()
            $swal.fire('Error', message != null ? message : 'Parece que ocurrio un error en el servidor, porfavor intentalo mas tarde.', 'error')
            break;

        case 404:
            $swal.fire('No encontrado', message != null ? message : 'Parece que ocurrio un error al intentar entontrar el recurso solicitado.', 'error')
            break;

        case 422:
            $swal.fire('Algunos campos son incorrectos', message != null ? message : "Algunos de los datos enviados son incorrectos.", 'error')
            break;

        case 401:
            window.location.href = '/';
            break

        case 403:
            $swal.fire('Algunos campos son incorrectos', message != null ? message : "Algunos de los datos enviados son incorrectos.", 'error')
            break

        default:
            // router.back()
            $swal.fire('Error inesperado', message != null ? message : 'Ocurrio un error inesperado', 'error')
            break;
    }
}

export default {
    login,
    isAuth,
    logout,
    getToken,
    get,
    index,
    show,
    update,
    destroy,
    store,
    types
}