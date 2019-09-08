<template>
<v-app :id="!this.$root.isDark ? 'inspire' : 'login'" :dark="this.$root.isDark">
    <v-toolbar app fixed clipped-left :color=" $root.isDark ? 'transparent' : this.$root.themeColor + ' darken-2 ' ">
        <v-toolbar-title style="width: 300px" class="ml-0">
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <span>Kreasi Utama</span>
        </v-toolbar-title>
        <v-text-field flat solo-inverted prepend-icon="search" label="Search" v-model="searchfield" @keyup.enter="searchit"></v-text-field>
        <v-spacer></v-spacer>
        <v-menu offset-y bottom origin="center center" transition="scale-transition">
            <v-btn icon slot="activator">

                <v-icon>apps</v-icon>
            </v-btn>
            <v-list>
                <v-list-tile v-for="(item, i) in items" :key="i">
                    <v-list-tile-action>
                        <v-icon>{{ item.icon}}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-menu>
        <v-btn icon>
            <v-icon>notifications</v-icon>
        </v-btn>
        <v-btn icon @click="theme">
            <v-icon v-if="this.$root.isDark == true">brightness_3</v-icon>
            <v-icon v-else>wb_sunny</v-icon>
        </v-btn>
        <v-menu v-if="currentUser" :close-on-content-click="true" :nudge-width="200" offset-y transition="scale-transition">

            <v-btn icon large slot="activator">
                <v-avatar size="32px">
                    <img
            src="/images/photo.jpg"
            alt="Vuetify"
            >
        </v-avatar>
            </v-btn>
            <v-card>
                <v-list>
                    <v-list-tile avatar>
                        <v-list-tile-avatar>
                            <img src="images/photo.jpg" alt="John">
                </v-list-tile-avatar>

                            <v-list-tile-content>
                                <v-list-tile-title>Masrur Afandy</v-list-tile-title>
                                <v-list-tile-sub-title>@masmasrur</v-list-tile-sub-title>
                            </v-list-tile-content>

                            <v-list-tile-action>
                                <v-btn class='red--text' icon>
                                    <v-icon>favorite</v-icon>
                                </v-btn>
                            </v-list-tile-action>

                    </v-list-tile>
                </v-list>

                <v-divider></v-divider>
                <v-list>
                    <v-list-tile>
                        <v-list-tile-action>
                            <v-switch v-model="$root.isDark" color="purple"></v-switch>
                        </v-list-tile-action>
                        <v-list-tile-title>Dark Theme</v-list-tile-title>
                    </v-list-tile>
                    <router-link to="/profil" style="all:unset">
                        <v-list-tile @click="">
                            <v-list-tile-action>
                                <v-icon>fa fa-user</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>My Profil</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </router-link>
                    <v-list-tile @click="">
                        <v-list-tile-action>
                            <v-icon>settings</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Setting</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                </v-list>

                <v-divider></v-divider>
                <v-list>
                    <v-list-tile @click="logout">
                        <v-list-tile-action>
                            <v-icon color="red">exit_to_app</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Sign Out</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-card>
        </v-menu>

    </v-toolbar>
    <v-navigation-drawer v-if="currentUser" :class=" $root.isDark ? 'transparent' : this.$root.themeColor + ' darken-4 white--text ' " dark clipped fixed v-model="drawer" value="true" app>
        <v-toolbar flat class="hidden-lg-and-up" style="width: 300px">
            <v-toolbar-side-icon @click.stop="drawer = !drawer" class="ml-0"></v-toolbar-side-icon>
            <v-list>
                <v-list-tile>
                    <v-list-tile-title class="title red--text">
                        Kreasi Utama
                    </v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-toolbar>
        <v-list>
            <template v-for="item in menus">

                <v-list-group v-if="item.children" v-model="item.model" :key="item.text" :prepend-icon="item.icon">
                    <template v-slot:activator>
                        <v-list-tile>
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ item.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </template>
                    <v-list-tile active-class="menu--active" class="pl-5" v-for="(child, i) in item.children" :key="i" :to="child.path">
                        <v-list-tile-title v-text="child.text">
                        </v-list-tile-title>
                        <v-list-tile-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-group>
                <v-list-tile active-class="menu--active" v-else :to="item.path" :key="item.text">
                    <v-list-tile-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>
                            {{ item.text }}
                        </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </template>
        </v-list>
    </v-navigation-drawer>
    <v-content>
        <v-img v-if="!currentUser"  src="/images/background.jpg" aspect-ratio="1.7" >
            <v-container align-center column justify-center>
                <!-- <h1 class="display-2 font-weight-thin mb-3">Vuetify.js</h1> -->
                <!-- <h4 class="subheading">Build your application today!</h4> -->
                <router-view></router-view>
            </v-container>
        </v-img>
        <v-container v-else fluid>
            <router-view></router-view>
        </v-container>
    </v-content>
    <v-speed-dial v-if="!this.$root.isDark" v-model="fab" bottom right fixed direction="top" transition="scale-transition">
        <template v-slot:activator>
            <v-btn v-model="fab" color="red darken-4" dark fab>
                <v-icon>color_lens</v-icon>
                <v-icon>close</v-icon>
            </v-btn>
        </template>
        <v-btn fab dark small color="green" @click="green">
        </v-btn>
        <v-btn fab dark small color="indigo" @click="indigo">
        </v-btn>
        <v-btn fab dark small color="blue" @click="red">
        </v-btn>
    </v-speed-dial>
    <v-footer :color="!this.$root.isDark ? this.$root.themeColor + ' darken-2' : 'gray'" app fixed>
        <span>&copy; 2017</span>
    </v-footer>
</v-app>
</template>

<script>
export default {
    name: 'main-app',
    data() {
        return {
            drawer: null,
            fab: false,
            transition: 'slide-y-reverse-transition',
            items: [{
                    title: 'Home',
                    icon: 'dashboard'
                },
                {
                    title: 'About',
                    icon: 'question_answer'
                }
            ],
            menus: [{
                    text: 'Home',
                    icon: 'home',
                    path: '/home'
                },
                {
                    text: 'Customers',
                    icon: 'dashboard',
                    path: '/customers'
                },
                {
                    text: 'Transaksi',
                    icon: 'vertical_split',

                    children: [{
                            icon: 'note',
                            text: 'Cash',
                            path: '/transaksicash',
                        },
                        {
                            icon: 'note_add',
                            text: 'Tempo',
                            path: '/transaksi',
                        }
                    ]
                },
                {
                    text: 'Pembayaran',
                    icon: 'money',
                    path: '/pembayaran'
                },
                {
                    text: 'Data',
                    icon: 'storage',
                    path: '/data'
                }
            ],
            searchfield: '',
            // isDark:'true',
        }
    },

    methods: {
        logout() {
            this.$store.commit('logout');
            this.$router.push('/login');
        },
        searchit() {
            Fire.$emit('searching', this.searchfield)

        },
        theme() {
            this.$root.isDark = !this.$root.isDark
        },
        indigo() {
            this.$root.themeColor = "indigo"
        },
        red() {
            this.$root.themeColor = "blue"
        },
        green() {
            this.$root.themeColor = "green"
        },
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser
        },

    },

}
</script>

<style>
tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, .1);
}

.v-table th,
.v-table td {
    border: 1px solid rgba(0, 0, 0, .3);
}

.application.theme--dark {
    background-image: url('/images/1.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}
/* .application.theme--light {
    background-image: url('/images/1.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
} */
.theme--dark.v-sheet {
    background-color: #4242429e;
}
.theme--dark.v-table {
    background: transparent;
    color: #fff;
}
.theme--dark.v-datatable .v-datatable__actions {
    background-color: #4242428c;
}
.theme--dark.v-list {
    background: #3d414487;
    color: #fff;
}
.transparent {
    background: transparent;
    /* opacity: .8; */
     /* background-color: #3d4144cc; */
}
.theme--dark.v-toolbar__content, .theme--dark.v-toolbar__extension {
    background: #3d4144cc;
}
#create .v-speed-dial {
    position: absolute;
}

#create .v-btn--floating {
    position: relative;
}
.theme--dark.v-expansion-panel .v-expansion-panel__container {
    border-top: 1px solid hsla(0,0%,100%,.12);
    background-color: #424242a1;
    /* background: transparent; */
    color: #fff;
}
</style>
