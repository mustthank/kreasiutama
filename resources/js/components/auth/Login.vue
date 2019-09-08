<template>
    <v-content>
        <v-container fluid fill-height>
            <v-layout align-center justify-center>
                <v-flex xs12 sm8 md4>
                    <form  @submit.prevent="authenticate">
                        <v-card class="elevation-12">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Login form</v-toolbar-title>
                                <v-spacer></v-spacer>
                                
                            </v-toolbar>
                            <v-card-text>
                                <v-text-field v-model="form.email" :rules="[rules.required, rules.email]" prepend-icon="person" label="E-mail" outline required></v-text-field>
                                <v-text-field v-model="form.password" prepend-icon="lock" outline
                                :append-icon="show1 ? 'visibility_off' : 'visibility'"
                                :rules="[rules.required, rules.min]"
                                :type="show1 ? 'text' : 'password'"
                                name="input-10-1"
                                label="Normal with hint text"
                                hint="At least 8 characters"
                                @click:append="show1 = !show1"
                                ></v-text-field>
                                <!-- <v-text-field prepend-icon="person" name="login" label="Login" ></v-text-field> -->
                                <!-- <v-text-field prepend-icon="lock" name="password" label="Password" id="password" type="password"></v-text-field> -->
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary"  @click="authenticate">Login</v-btn>
                            </v-card-actions>
                        </v-card>
                    </form>
                </v-flex>
            </v-layout>
        </v-container>
    </v-content>
    <!-- <div class="login row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">login</div>
                <div class="card-body">
                    <form @submit.prevent="authenticate">
                        <div class="form-group row">
                            <label for="email">Email:</label>
                            <input type="email" v-model="form.email" class="form-control" placeholder="Email Address">
                        </div>
                        <div class="form-group row">
                            <label for="password">Password:</label>
                            <input type="password" v-model="form.password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group row">
                            <input type="submit" value="Login">
                        </div>
                        <div class="form-group row" v-if="authError">
                            <p class="error">
                                {{ authError }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
</template>

<script>
    import {login} from '../../helpers/auth';
    export default {
        name: "login",
        data() {
            return {
                show1: false,
                show2: true,
                show3: false,
                show4: false,
                password: 'Password',
                rules: {
                    required: value => !!value || 'Required.',
                    min: v => v.length >= 6 || 'Min 6 characters',
                    email: value => {
                        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        return pattern.test(value) || 'Invalid e-mail.'
                    }
                },
                form: {
                    email: '',
                    password: ''
                },
                error: null
            };
        },
        
        methods: {
            authenticate() {
                this.$store.dispatch('login');
                login(this.$data.form)
                .then((res) => {
                    this.$store.commit("loginSuccess", res);
                    this.$router.push({path: '/home'});
                })
                .catch((error) => {
                    this.$store.commit("loginFailed", {error});
                });
            }
        },
        computed: {
            authError() {
                return this.$store.getters.authError;
            }
        }
    }
</script>

<style scoped>
    .error {
        text-align: center;
        color: red;
    }
</style>