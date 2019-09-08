import { getLocalUser } from "./helpers/auth";
import Vue from 'vue';
import Vuex from 'vuex';
Vue.use (Vuex);

const user = getLocalUser();

export default  new Vuex.Store ({
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loading: true,
        auth_error: null,
        customers: [],
        total:'',
        dataTransaksi:[],
    },
    getters: {
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        currentUser(state) {
            return state.currentUser;
        },
        authError(state) {
            return state.auth_error;
        },
        customers(state) {
            return state.customers;
        },
        total(state) {
            return state.total;
        },
        dataTransaksi(state){
            return state.dataTransaksi;
        },
    },
    mutations: {
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload) {
            state.auth_error = null;
            state.isLoggedIn = true;
            state.loading = false;
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
        },
        updateCustomers(state, payload) {
            state.customers = payload;
        },
        getTotal(state, payload) {
            state.total = payload;
        },
        updateDataTransaksi(state,payload){
            state.dataTransaksi = payload;
        },
    },
    actions: {
        login(context) {
            context.commit("login");
        },
        getCustomers(context) {
            axios.get('api/customers')
            .then((response) => {
                context.commit('updateCustomers', response.data.customers);
                
            })
            .catch((error)=>{
                if (error.response.status == 401) {
                    context.commit('logout');
                    router.push('/login');
                }
            });
        },
        getDataTransaksi(context){
            this.loading = true
            axios.get('api/datatransaksi')
            .then((response)=> {
                context.commit('updateDataTransaksi', response.data.datatransaksi);
                context.commit('getTotal',response.data.total)
                this.loading = false
            })
            .catch((error)=>{
                if (error.response.status == 401) {
                    context.commit('logout');
                    router.push('/login');
                }
            });
        }
    }
});