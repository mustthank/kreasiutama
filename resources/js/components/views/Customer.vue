<template>
<div>
    <form @submit.prevent="add" method="POST" @keydown="form.onKeydown($event)">
        <!-- <form ref="form" v-model="valid" method="POST" @keydown="form.onKeydown($event)"> -->
        <v-layout>
            <v-expansion-panel>
                <v-expansion-panel-content>
                    <div slot="header">
                        <v-icon>save</v-icon>
                        <span class="expansion-panel__header__label"> Tambah Customer</span>
                    </div>
                    <!-- <v-card> -->
                    <!-- <v-card-text> -->
                    <v-container fluid grid-list-md>
                        <v-layout align-center wrap>
                            <v-flex d-flex xs12 sm4 md4 align-center="true">
                                <v-layout row wrap>
                                    <v-flex d-flex xs12 sm12 md12 align-center="true" class="text-no-wrap">
                                        <my-upload url="" img-format="jpg" img-bgc="#fff" v-model="show1" field="avatar1" ki="1" :width="500" :height="500" lang-type="en" @crop-success="cropSuccess" @crop-upload-success="cropUploadSuccess" @crop-upload-fail="cropUploadFail" :no-rotate="false" :headers="headers" :params="otherParams"></my-upload>
                                        <v-avatar size="200">
                                            <div style="position:absolute">
                                                <v-btn icon color="green" flat fab large class="v-btn--simple" @click="toggleShow1">
                                                    <v-icon>camera_alt</v-icon>
                                                </v-btn>
                                            </div>
                                            <img v-if="avatarUrl1" :src="avatarUrl1" />
                                            <img v-else class="grey  mb-3" :src="avatarUrl1" />
                                        </v-avatar>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex xs12 sm8 md8>
                                <v-layout row wrap>
                                    <v-flex d-flex xs12 sm6 md6>
                                        <v-text-field outline v-model="form.nama_pelanggan" :rules="nameRules" :counter="10" label="Nama" required></v-text-field>
                                    </v-flex>
                                    <v-flex d-flex xs12 sm6 md6>
                                        <v-text-field outline v-model="form.alamat" label="Alamat" required></v-text-field>
                                    </v-flex>
                                    <v-flex d-flex xs12 sm6 md6>
                                        <v-text-field outline v-model="form.telpon" label="Phone" required></v-text-field>
                                    </v-flex>
                                    <v-flex d-flex xs12 sm6 md6>
                                        <v-text-field outline v-model="form.email" label="E-mail" required></v-text-field>
                                    </v-flex>
                                    <v-flex d-flex xs12 sm8 md8>
                                    </v-flex>
                                    <v-flex d-flex xs12 sm4 md4 class="text-no-wrap">
                                        <v-btn outline color="blue darken-1" flat :ripple="{class:'success--text'}" type="submit">Add Customer</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-layout>

    </form>
    <v-dialog v-model="dialog" persistent hide-overlay max-width="1000px">
        <v-card>
            <form @submit.prevent="add" method="POST" @keydown="form.onKeydown($event)">
                <v-card-title>
                    <span class="headline">User Profile</span>
                </v-card-title>
                <v-card-text>
                    <v-container fluid grid-list-md>
                        <v-layout align-center wrap>
                            <v-flex d-flex xs12 sm4 md4 align-center="true">
                                <v-layout row wrap>
                                    <v-flex d-flex xs12 sm12 md12 align-center="true" class="text-no-wrap">
                                        <my-upload url="" img-format="jpg" img-bgc="#fff" v-model="show1" field="avatar1" ki="1" :width="500" :height="500" lang-type="en" @crop-success="cropSuccess" @crop-upload-success="cropUploadSuccess" @crop-upload-fail="cropUploadFail" :no-rotate="false" :headers="headers" :params="otherParams"></my-upload>
                                        <v-avatar size="150">
                                            <div style="position:absolute">
                                                <v-btn icon color="green" flat fab large class="v-btn--simple" @click="toggleShow1">
                                                    <v-icon>camera_alt</v-icon>
                                                </v-btn>
                                            </div>
                                            <img v-if="avatarUrl1" :src="avatarUrl1" />
                                            <img v-else class="grey  mb-3" :src="avatarUrl1" />
                                        </v-avatar>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex xs12 sm8 md8>
                                <v-layout row wrap>
                                    <v-flex d-flex xs12 sm6 md6>
                                        <v-text-field outline v-model="form.nama_pelanggan" :rules="nameRules" :counter="10" label="Nama" required></v-text-field>
                                    </v-flex>
                                    <v-flex d-flex xs12 sm6 md6>
                                        <v-text-field outline v-model="form.alamat" label="Alamat" required></v-text-field>
                                    </v-flex>
                                    <v-flex d-flex xs12 sm6 md6>
                                        <v-text-field outline v-model="form.telpon" label="Phone" required></v-text-field>
                                    </v-flex>
                                    <v-flex d-flex xs12 sm6 md6>
                                        <v-text-field outline v-model="form.email" label="E-mail" required></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn outline color="blue darken-1" flat @click="dialog = false">Close</v-btn>
                    <v-btn outline color="blue darken-1" flat type="submit">Save</v-btn>
                </v-card-actions>
            </form>
        </v-card>
    </v-dialog>
    <material-card :color="$root.isDark ? 'green' : this.$root.themeColor + ' white--text'" title="Data Customer">
        <v-data-table :headers="headerstable" :items="customers" :search="search" :pagination.sync="pagination" class="elevation-22">
            <template slot="items" slot-scope="props">
                <td>{{ (props.index + 1) + ((pagination.page - 1) * pagination.rowsPerPage) }}.
                    <v-tooltip top content-class="top">
                        <v-btn slot="activator" class="v-btn--simple" color="green" @click="edit" icon small>
                            <v-icon small color="white">far fa-edit</v-icon>
                        </v-btn>
                        <span>Edit</span>
                    </v-tooltip>
                    <v-tooltip top content-class="top">
                        <v-btn slot="activator" class="v-btn--simple" color="red" icon small @click="deleteCustomer(props.item.id_customer)">
                            <v-icon small color="white">far fa-trash-alt</v-icon>
                        </v-btn>
                        <span>Delete</span>
                    </v-tooltip>
                </td>
                <td>
                    <v-avatar slot="offset" class="mx-auto d-block ma-3" size="64">
                        <img v-if="props.item.photo" :src="`/images/profile/`+props.item.photo" />
                        <img v-else src="/images/photo.jpg" />
                    </v-avatar>
                </td>
                <td>{{ props.item.nama_pelanggan }}</td>
                <td>{{ props.item.alamat }}</td>
                <td>{{ props.item.email }}</td>
            </template>
            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                Your search for "{{ search }}" found no results.
            </v-alert>
        </v-data-table>
    </material-card>
</div>
</template>

<script>
import Vue from 'vue';
import myUpload from 'vue-image-crop-upload';
import axios from 'axios'
export default {
    components: {
        'my-upload': myUpload
    },
    mounted() {
        if (this.customers.length) {
            return;
        }

        this.$store.dispatch('getCustomers');
    },
    data: () => ({
        valid: false,
        show1: false,
        avatarUrl1: null,
        photo: null,
        dialog: false,
        form: new Form({
            nama_pelanggan: '',
            alamat: '',
            telpon: '',
            email: '',
            photo: ''
        }),
        otherParams: {
            token: '123456798',
            name: 'img'
        },
        nameRules: [
            v => !!v || 'Name is required',
            //   v => v.length <= 10 || 'Name must be less than 10 characters'
        ],
        search: '',
        pagination: {
            rowsPerPage: 10,
        },
        selected: [],
        headers: {
            smail: '*_~'
        },
        headerstable: [{
                text: 'No',
                align: 'left',
                value: ''
            },

            {
                text: 'Photo',
                align: 'left',
                value: 'photo'
            },
            {
                text: 'Nama Pelanggan',
                align: 'left',
                value: 'nama_pelanggan'
            },
            {
                text: 'Alamat',
                align: 'left',
                value: 'alamat'
            },
            {
                text: 'Email',
                align: 'left',
                value: 'email'
            },
        ],
    }),
    methods: {
        refreshcustomers() {
            // return this.$store.getters.customers;
             this.$store.dispatch('getCustomers');
        },
        edit() {
            this.dialog = true
        },
        reset() {
            this.form.nama_pelanggan = ''
            this.form.alamat = ''
            this.form.telpon = ''
            this.form.email = ''
            this.form.photo = ''
            this.avatarUrl1 =""
        },
        toggleShow1: function toggleShow1() {
            var show1 = this.show1;

            this.show1 = !show1;
        },
        add() {
            this.form.photo = this.avatarUrl1
            this.form.post('/api/customers/new')
                .then((response) => {
                    this.$router.push('/customers');
                    Fire.$emit('AfterCreate');
                    this.reset()
                });

        },
        cropSuccess: function cropSuccess(data, field, key) {
            if (field == 'avatar1') {
                this.avatarUrl1 = data;
            } else if (field == 'avatar2') {
                this.avatarUrl2 = data;
            } else {
                this.avatarUrl3 = data;
            }
            console.log('-------- 剪裁成功 --------');
        },
        cropUploadSuccess: function cropUploadSuccess(data, field, key) {
            console.log('-------- 上传成功 --------');
            console.log(data);
            console.log('field: ' + field);
            console.log('key: ' + key);
        },
        cropUploadFail: function cropUploadFail(status, field, key) {
            console.log('-------- 上传失败 --------');
            console.log(status);
            console.log('field: ' + field);
            console.log('key: ' + key);
        },
        deleteCustomer(id) {
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
                    this.form.delete(`/api/customers/delete/` + id).then(() => {
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
    },
    computed: {
        pages() {
            if (this.pagination.rowsPerPage == null ||
                this.pagination.totalItems == null
            ) return 0

            return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
        },
        customers() {
            return this.$store.getters.customers;
        },

    },
    created() {
        Fire.$on('AfterCreate', () => {
            this.refreshcustomers();
        });
    }

}
</script>
