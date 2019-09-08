<template>
<div>
    <v-form ref="form" v-model="valid">
        <v-layout column>
            <transition name="fade" mode="out-in">
                <v-expansion-panel>
                    <v-expansion-panel-content>
                        <div slot="header">
                            <v-icon>save</v-icon>
                            <span class="expansion-panel__header__label">Tambah</span>
                        </div>
                        <v-card>
                            <v-card-text>
                                <v-container fluid grid-list-xl>
                                    <v-layout align-center wrap>
                                        <v-flex xs12 sm6>
                                            <v-text-field outline v-model="name" :rules="nameRules" :counter="10" label="Nama" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field outline v-model="alamat" label="Alamat" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field outline v-model="telpon" label="Phone" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field outline v-model="email" label="E-mail" required></v-text-field>
                                        </v-flex>
                                        <!-- <v-btn :ripple="{class:'success--text'}" :disabled="!valid" @click="submit">submit</v-btn> -->
                                        <!-- <v-btn @click="clear" :ripple="{class:'success--text'}">clear</v-btn> -->
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </transition>
        </v-layout>
    </v-form>
    <v-data-table :headers="headers" :items="customers" :search="search" :pagination.sync="pagination" class="elevation-1">
        <template slot="items" slot-scope="props">
            <td>{{ props.item + 1 }}</td>
            <td>{{ props.item.nama_pelanggan }}</td>
            <td class="text-xs-right">{{ props.item.alamat }}</td>
            <!-- <td class="text-xs-right">{{ props.item.fat }}</td> -->
            <!-- <td class="text-xs-right">{{ props.item.carbs }}</td> -->
            <!-- <td class="text-xs-right">{{ props.item.protein }}</td> -->
            <!-- <td class="text-xs-right">{{ props.item.iron }}</td> -->
        </template>
    </v-data-table>
    <div class="text-xs-center pt-2">
        <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
    </div>
</div>
</template>

<script>
// import axios from 'axios'
export default {
    name:'customer',
     
        mounted() {
            if (this.customers.length) {
                return;
            }
            
            this.$store.dispatch('getCustomers');
        },
        computed: {
            customers() {
                return this.$store.getters.customers;
            }
        }
    // data: () => ({
    //     valid: false,
    //     name: '',
    //     alamat: '',
    //     telpon: '',
    //     nameRules: [
    //         v => !!v || 'Name is required',
    //         //   v => v.length <= 10 || 'Name must be less than 10 characters'
    //     ],
    //     email: '',
    //     search: '',
    //     pagination: {},
    //     selected: [],
    //     headers: [{
    //             text: 'Nama Pelanggan',
    //             align: 'left',
    //             value: 'nama_pelanggan'
    //         },
    //         {
    //             text: 'Alamat',
    //             value: 'alamat'
    //         },
    //         // { text: 'Fat (g)', value: 'fat' },
    //         // { text: 'Carbs (g)', value: 'carbs' },
    //         // { text: 'Protein (g)', value: 'protein' },
    //         // { text: 'Iron (%)', value: 'iron' }
    //     ],
    //     customers: [],
    //     url: '/api/customers'

    // }),
    // methods: {
    //     fetchData() {
    //         var vm = this
    //         axios.get(`${this.url}`)
    //             .then(function (response) {
    //                 Vue.set(vm.$data, 'customers', response.data.model)
    //             })
    //     },
    //     clear() {
    //         this.$refs.form.reset()
    //     }
    // },
    // computed: {
    //     pages() {
    //         if (this.pagination.rowsPerPage == null ||
    //             this.pagination.totalItems == null
    //         ) return 0

    //         return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
    //     }
    // },
    // created() {
    //     this.fetchData()
    //     // (Fire.$on('AfterCreate', () => {
    //     //    this.fetchData();
    //     // });)
    // }
}
</script>
