<template>
<div>
    <v-flex md12>
        <material-card :color="$root.isDark ? 'green' : this.$root.themeColor + ' white--text'"  title="Data Transaki">
            <v-layout wrap>
                <v-flex xs12 sm6>
                    <v-combobox v-model="customer" :items="customers" item-text="nama_pelanggan" label="Nama Pelanggan" @change="getTotal">
                        <template slot="selection" slot-scope="data">
                            {{ data.item.nama_pelanggan }}
                        </template>
                    </v-combobox>
                </v-flex>
                <v-flex xs12 sm6 md6>
                    <v-radio-group v-model="jenistransaksi" row @change="getTotal">
                        <v-radio label="Semua" value="semua"></v-radio>
                        <v-radio label="Sudah Lunas" value="cash"></v-radio>
                        <v-radio label="Blm Lunas" value="utang"></v-radio>
                    </v-radio-group>
                </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-data-table :headers="headers" :items="filteredItems" hide-actions :search="search" :pagination.sync="pagination" :loading="this.$store.loading">
                <template slot="headerCell" slot-scope="{ header }">
                    <span    class="body-2 font-weight-bold text-success text--darken-3"    v-text="header.text"    />
                </template>
                <template slot="items" slot-scope="props">
                <td>{{ (props.index + 1) + ((pagination.page - 1) * pagination.rowsPerPage)  }}</td>
                <td>{{ formatDate (props.item.tanggal) }}</td>
                <td>{{ props.item.nama_barang }}</td>
                <td class="text-xs-right">{{ props.item.quantity }}</td>
                <td>{{ formatPrice(props.item.harga_satuan) }}</td>
                <td class="text-xs-right">{{formatPrice(props.item.total)}}</td>
                <td class="text-xs-right">{{ formatPrice(props.item.DP) }}</td>
                <td class="text-xs-right">{{ formatPrice(props.item.sisa) }}</td>
                <td>{{ props.item.keterangan }}</td>
                </template>
                <template slot="footer" >
                        <tr class="subheading font-weight-bold text-danger text--darken-3">
                        <td colspan='2' ></td>
                        <td colspan='2' class="blue white--text">Total : {{formatPrice(this.total)}},-</td>
                        <td colspan='2' class="green white--text">Bayar : {{formatPrice(this.bayar)}},-</td>
                        <td colspan='4' class="red white--text" >Sisa  : {{formatPrice(this.sisa)}},-</td>
                        </tr>
                </template>
            </v-data-table>
            <div class="text-xs-center pt-2">
                <v-pagination circle v-model="pagination.page" :length="pages" :total-visible="7"></v-pagination>
            </div>
        </material-card>
    </v-flex>
</div>
</template>

<script>
export default {
    //  import   './components/material/card';
    data: () => ({
        search: '',
        total: 0,
        bayar: 0,
        sisa: 0,
        // loading: true,
        jenistransaksi: 'semua',
        loading: true,
        pagination: {
            rowsPerPage: 10
        },
        selected: [],
        customer: '',
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
        datatransaksi() {
            return this.$store.getters.dataTransaksi;
        },
        customers() {
            return this.$store.getters.customers;
        },

        filteredItems() {
            return this.datatransaksi.filter((i) => {
                if (!this.customer == '') {
                    if (this.jenistransaksi === 'cash') {
                        return !this.customer.id_customer || (i.id_customer === this.customer.id_customer) && (i.status == 'lunas');
                    } else if (this.jenistransaksi === 'utang') {
                        return !this.customer.id_customer || (i.id_customer === this.customer.id_customer) && (i.status == 'bon');
                    } else {
                        return !this.customer.id_customer || (i.id_customer === this.customer.id_customer);
                    }
                } else {
                    if (this.jenistransaksi === 'cash') {
                        return i.sisa == 0;
                    } else if (this.jenistransaksi === 'utang') {
                        return i.DP == 0;
                    } else {
                        return i.total >= 0;
                    }
                }
            })
        },
        pages() {
            this.pagination.totalItems = this.filteredItems.length
            // this.pagination.rowsPerPage = 10
            if (this.pagination.rowsPerPage == null ||
                this.pagination.totalItems == null
            ) return 0

            return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
        }

    },
    methods: {
        
        getTotal() {
            this.total = 0;
            this.bayar = 0;
            this.sisa = 0;
            for (let index = 0; index < this.filteredItems.length; index++) {
                this.total += this.filteredItems[index].total;
                this.bayar += this.filteredItems[index].DP;
                this.sisa += this.filteredItems[index].sisa;
            }
            //  this.filteredItems
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        formatDate(value){
            const months = ["Januari", "Februari", "Maret","April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
let current_datetime = new Date(value)
let formatted_date = current_datetime.getDate() + " " + months[current_datetime.getMonth()] + " " + current_datetime.getFullYear()
return formatted_date;
        }
    },
    mounted() {
        if (this.datatransaksi.length) {
            return;
        }
        this.$store.dispatch('getDataTransaksi');
        // this.$store.dispatch('getTotal');
        this.$store.dispatch('getCustomers');
        // this.getTotal();
    },
    created() {
        Fire.$on('searching', (value) => {
            this.search = value;
            // console.log(value)
        })
    }
}
</script>
