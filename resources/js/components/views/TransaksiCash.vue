<template>
<v-container fluid grid-list-xl>
        <form @submit.prevent="add" method="POST" @keydown="form.onKeydown($event)">
    <v-card class="mb-3">
        <v-card-title>
            <v-layout wrap>
                <v-flex xs6 sm6 md3>
                  <v-combobox outline v-model="customer" :items="customers" item-text="nama_pelanggan" label="Nama Pelanggan" item-value="id_customer" required :disabled="isi" @change="inputdata">
                <template slot="selection" slot-scope="data">
                    {{ data.item.nama_pelanggan }}
                </template>
            </v-combobox>
                </v-flex>
                <v-flex xs6 sm6 md6>
                    <v-text-field v-model="form.nama_barang" outline label="Nama Barang" required></v-text-field>
                </v-flex>
                <v-flex xs6 sm6 md3>
                    <v-text-field v-model="form.quantity" mask="###############" outline label="Quantity" required></v-text-field>
                </v-flex>
                <v-flex xs6 sm6 md4>
                    <v-text-field v-model="form.harga_satuan" mask="###############" outline label="Harga Satuan" required></v-text-field>
                </v-flex>
                <v-flex xs6 sm6 md4>
                    <v-text-field @input="getTotal" outline label="TOTAL" v-model="form.total" disabled></v-text-field>
                </v-flex>
                <v-flex xs6 sm6 md4>
                    <v-text-field v-model="form.keterangan" outline label="Keterangan"></v-text-field>
                </v-flex>
                
            </v-layout>
        </v-card-title>
        <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn outline color="blue darken-1" flat>Reset</v-btn>
                    <v-btn outline color="blue darken-1" flat type="submit" @click="tambah">Save</v-btn>
                </v-card-actions>
    </v-card>
        </form>
    <v-data-table :rows-per-page-items='[20,50,100,{"text":"$vuetify.dataIterator.rowsPerPageAll","value":-1}]' :items="tmptransaksi">
        <template slot="headers" slot-scope="props">
            <tr>
                <th>
                    <v-btn v-if="$root.isDark" outline round color="success" @click="tambah" dark>Tambah <v-icon right dark>add_circle</v-icon>
                    </v-btn>
                    <v-btn v-else round color="blue darken-4" @click="tambah" dark>Tambah <v-icon right dark>add_circle</v-icon>
                    </v-btn>
                    <v-dialog v-model="dialog" persistent hide-overlay max-width="600px">
                        <v-card>

                        </v-card>
                    </v-dialog>
                </th>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </template>
        <template slot="items" slot-scope="props">
            <td>
                <v-tooltip top content-class="top">
                    <v-btn slot="activator" class="v-btn--simple" color="green" icon small>
                        <v-icon small color="white">far fa-edit</v-icon>
                    </v-btn>
                    <span>Edit</span>
                </v-tooltip>
                <v-tooltip top content-class="top">
                    <v-btn slot="activator" class="v-btn--simple" color="red" icon small @click="deleteTransaksi(props.item.id_transaksi)">
                        <v-icon small color="white">far fa-trash-alt</v-icon>
                    </v-btn>
                    <span>Delete</span>
                </v-tooltip>
            </td>
            <td>{{props.index+1}}</td>
            <td>{{props.item.nama_barang}}</td>
            <td class="text-right">{{formatPrice(props.item.quantity)}}</td>
            <td class="text-right">{{formatPrice(props.item.harga_satuan)}}</td>
            <td class="text-right">{{formatPrice(props.item.quantity * props.item.harga_satuan)}}</td>
            <td>{{props.item.keterangan}}</td>
        </template>
        <template slot="no-data">
            <td :colspan="7">
                <v-alert :value="true" color="error" icon="warning">
                    Sorry, nothing to display here :(
                </v-alert>
            </td>
        </template>
    </v-data-table>
    <v-btn :ripple="{class:'success--text'}" :disabled="!valid" @click="saveTransaksi()">Simpan</v-btn>
    <v-btn @click="cancelAll()" :disabled="!valid" :ripple="{class:'success--text'}">Cancel</v-btn>
</v-container>
</template>

<script>
import axios from 'axios'
export default {
    data: () => ({
        dialog: false,
        valid: false,
        nama: '',
        isi: false,
        alamat: '',
        search: '',
        model: [],
        customer: [],
        tmptransaksi: [],
        jenistransaksi: 'utang',
        form: new Form({
            quantity: '',
            harga_satuan: '',
            total: '0',
            id_customer: "",
            jenistransaksi: '',
            username: "",
        }),

        url: '/api/tmptransaksi',
        quantity: '',
        harga_satuan: '',
        total: '0'

    }),
    computed: {
        customers() {
            return this.$store.getters.customers;
        },
        currentUser() {
            return this.$store.getters.currentUser;
        },
        getTotal() {
            this.form.total = this.form.quantity * this.form.harga_satuan
        }

    },
    methods: {
        reset() {
            this.form.reset()
        },
        inputdata() {
            this.form.id_customer = this.customer.id_customer
            this.tabel = true
            // this.isi = true
        },
        add() {
            this.form.id_customer = this.customer.id_customer
            this.form.username = this.currentUser.name
            this.form.post('/api/datatransaksi/new')
                .then((response) => {
                    // this.$router.push('/customers');
                    this.dialog = false;
                    this.form.reset();
                    Fire.$emit('AfterCreate');
                });
        },
        tmpTransaksi() {
            var vm = this
            axios.get(`${this.url}`)
                .then(function (response) {
                    Vue.set(vm.$data, 'tmptransaksi', response.data.tmptransaksi)
                }).then(function () {
                    if (vm.tmptransaksi.length > 0) {
                        vm.isi = true;
                        vm.valid = true;
                    } else {
                        vm.isi = false;
                        vm.valid = false;
                    }
                })

        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        tambah() {
            if (this.customer.length == 0) {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Pilih Nama Pelanggan'
                })
            } else {
                this.dialog = true
            }
        },
        cancelAll() {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    this.form.username = this.currentUser.name
                    this.form.post(`${this.url}/delete`).then(() => {
                        swal.fire({
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1000,
                            type: 'success'
                        })
                        Fire.$emit('AfterCreate')

                    }).catch(() => {
                        swal.fire("Failed!", "There was something wronge.", "warning");
                    });
                }
            })
        },
        deleteTransaksi(id) {
            // swal.fire('OK');
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    this.form.delete(`${this.url}/` + id).then(() => {
                        swal.fire({
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1000,
                            type: 'success'
                        })
                        Fire.$emit('AfterCreate')

                    }).catch(() => {
                        swal.fire("Failed!", "There was something wronge.", "warning");
                    });
                }
            })
        },
        saveTransaksi() {
            this.form.username = this.currentUser.name
            this.form.post('/api/datatransaksi/save').then(() => {
                swal.fire({
                    title: 'Your data has been saved',
                    showConfirmButton: false,
                    timer: 1000,
                    type: 'success'
                })
                Fire.$emit('AfterCreate')

            }).catch(() => {
                swal.fire("Failed!", "There was something wrong.", "warning");
            });
        }
    },

    mounted() {
        this.$store.dispatch('getCustomers');
        this.tmpTransaksi();
    },
    created() {
        this.tmpTransaksi();
        Fire.$on('AfterCreate', () => {
            this.tmpTransaksi();
        });
    }
}
</script>
