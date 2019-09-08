<template>
<div>
    <form @submit.prevent="bayar" method="POST" @keydown="form.onKeydown($event)">
        <v-card id="create">
            <v-container fluid grid-list-md>
                <v-layout>
                    <v-flex d-flex xs12 sm4 md3 align-center="true">
                        <v-avatar slot="offset" class="mx-auto d-block" size="200">
                            <img              :src="`/images/profile/`+photo"            />
                        </v-avatar>
                    </v-flex>
                    <v-card-text>
                        <v-layout child-flex wrap>
                            <v-flex xs12 sm6 md4>
                                <v-combobox outline v-model="form.id_customer" :items="customers" required item-text="nama_pelanggan" :disabled="isi" label="Nama Pelanggan" value="form.id_customer" @change="getTotal">
                                </v-combobox>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-card dark color="primary">
                                    <v-card-text class="subheading font-weight-bold">Total Utang : {{formatPrice(this.totalUtang)}}</v-card-text>
                                </v-card>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-card dark color="red">
                                    <v-card-text class="subheading font-weight-bold">Sisa Utang : {{formatPrice(this.sisa)}}</v-card-text>
                                </v-card>
                            </v-flex>
                            <v-flex xs12 sm4 md4>
                                <v-text-field @input="hitung" v-model="form.total_pembayaran" mask="###############" outline label="Jumlah Bayar" required :disabled="isi"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm8 md8>
                                <v-text-field v-model="form.detail_pembayaran" outline label="Keterangan" :disabled="isi"></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-layout>
            </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn outline color="blue darken-1" flat type="submit" :disabled="isi">Tampilkan Detail</v-btn>
                <v-btn outline color="blue darken-1" flat @click="cancelAll">Cancel</v-btn>
                <v-btn outline color="blue darken-1" flat @click="simpan" :disabled="!isi">Bayar</v-btn>
            </v-card-actions>
        </v-card>
    </form>
    <material-card :color="$root.isDark ? 'green' : this.$root.themeColor + ' white--text'" title="Item Dibayar">

        <v-data-table :rows-per-page-items='[20,50,100,{"text":"$vuetify.dataIterator.rowsPerPageAll","value":-1}]' :items="tmppembayaran">
            <template slot="headers" slot-scope="props">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Bayar</th>
                </tr>
            </template>
            <template slot="items" slot-scope="props">
                <td>{{props.index+1}}</td>
                <td>{{props.item.nama_barang}}</td>
                <td class="text-right">{{formatPrice(props.item.quantity)}}</td>
                <td class="text-right">{{formatPrice(props.item.harga_satuan)}}</td>
                <td class="text-right">{{formatPrice(props.item.quantity * props.item.harga_satuan)}}</td>
                <td class="text-right">{{formatPrice(props.item.bayar)}}</td>
            </template>
            <template slot="no-data">
                <td :colspan="7">
                    <v-alert :value="true" color="error" icon="warning">
                        Sorry, nothing to display here :(
                    </v-alert>
                </td>
            </template>
        </v-data-table>
    </material-card>
</div>
</template>

<script>
export default {
    //  import   './components/material/card';
    data: () => ({
        sisa: 0,
        totalUtang: 0,
        tmppembayaran: [],
        isi: false,
        photo:null,
        form: new Form({
            total_pembayaran: '',
            id_customer: "",
            detail_pembayaran: "",
            username: '',
        }),
        pagination: {
            rowsPerPage: 10
        },
        headers: [{
                text: 'No',
                value: '',
            },
            {
                text: 'Tanggal',
                value: 'tanggal'
            },
            {
                text: 'Nama Barang',
                value: 'nama_barang'
            },
            {
                text: 'Qty',
                value: 'quantity'
            },
            {
                text: 'Harga Satuan',
                value: 'harga_satuan'
            },
            {
                text: 'Jumlah',
                value: 'total'
            },
            {
                text: 'Bayar',
                value: 'DP'
            },
            {
                text: 'Sisa',
                value: 'sisa'
            },
            {
                text: 'Keterangan',
                value: 'keterangan'
            },
        ],
    }),
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        },
        datatransaksi() {
            return this.$store.getters.dataTransaksi;
        },
        customers() {
            return this.$store.getters.customers;
        },

        filteredItems() {
            // this.form.id_customer = this.customer.id_customer
            if (this.form.id_customer) {
                return this.datatransaksi.filter((i) => {
                    return !this.form.id_customer.id_customer || (i.id_customer === this.form.id_customer.id_customer);
                })
            }

        },
        pages() {
            this.pagination.totalItems = this.filteredItems.length
            if (this.pagination.rowsPerPage == null ||
                this.pagination.totalItems == null
            ) return 0

            return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
        }

    },
    methods: {
        reset() {
            this.form.total_pembayaran = ""
            this.form.id_customer = ""
            this.form.keterangan = ""
            this.sisa = 0
            this.totalUtang = 0
        },
        hitung() {
            this.sisa = this.totalUtang - this.form.total_pembayaran
        },
        getTotal() {
            this.totalUtang = 0;
            if (this.filteredItems) {
                for (let index = 0; index < this.filteredItems.length; index++) {
                    this.totalUtang += this.filteredItems[index].sisa;
                }
            }
            this.photo = this.form.id_customer.photo;
        },
        bayar() {
            this.form.post(`api/pembayaran/create`).then(() => {
                Fire.$emit('AfterCreate')
            })
            this.isi = true
            // this.reset()
        },
        simpan() {
            this.form.post(`api/pembayaran/save`).then(() => {
                Fire.$emit('AfterCreate')
            })
            this.isi = false
            this.reset()
        },
        tmpPembayaran() {
            var vm = this
            axios.get('api/tmppembayaran')
                .then(function (response) {
                    Vue.set(vm.$data, 'tmppembayaran', response.data.tmppembayaran)
                })
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        formatDate(value) {
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            let current_datetime = new Date(value)
            let formatted_date = current_datetime.getDate() + " " + months[current_datetime.getMonth()] + " " + current_datetime.getFullYear()
            return formatted_date;
        },
        cancelAll() {
            if (this.tmppembayaran && this.tmppembayaran.length > 0) {
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
                        this.form.post(`api/pembayaran/delete`).then(() => {
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
            }
            this.isi = false
            this.reset()
        },
    },
    mounted() {
        if (this.datatransaksi.length) {
            return;
        }
        this.$store.dispatch('getDataTransaksi');
        this.$store.dispatch('getCustomers');
    },
    created() {
        this.tmpPembayaran();
        // this.currentUser();
        this.form.username = this.currentUser.name;
        Fire.$on('AfterCreate', () => {
            this.tmpPembayaran();
        });
    }
}
</script>
