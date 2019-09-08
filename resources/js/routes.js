import Transaksi from './components/views/Transaksi.vue';
import TransaksiCash from './components/views/TransaksiCash.vue';
import Customer from './components/views/Customer.vue';
import Pembayaran from './components/views/Pembayaran.vue';
import Home from './components/views/Home.vue';
import Login from './components/auth/Login.vue';;
import DataTransaksi from './components/views/data.vue';
import UserProfile from './components/views/UserProfile.vue';
// import data_karyawan from './components/Karyawan.vue';
// import data_transaksi from './components/Transaksi.vue';
// import data_faktur from './components/Faktur.vue';
// import lembur from './components/Lembur.vue';

export const routes = [
    {
        path: '/home',
        component: Home,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/transaksi',
        component: Transaksi,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/transaksicash',
        component: TransaksiCash,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/customers',
        component: Customer,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/data',
        component: DataTransaksi,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/pembayaran',
        component: Pembayaran,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/profil',
        component: UserProfile,
        meta: {
            requiresAuth: true
        }
    },
    // {
        // path: '/data_karyawan',
        // component: data_karyawan,
    // },
    // {
        // path: '/data_transaksi',
        // component: data_transaksi,
    // },
    // {
        // path: '/data_faktur',
        // component: data_faktur,
    // },
    // {
        // path: '/lembur',
        // component: lembur,
    // },

];