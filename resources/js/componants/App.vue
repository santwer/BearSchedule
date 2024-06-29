<template>

        <!-- Page Wrapper -->
        <div id="wrapper">
            <sidebar></sidebar>

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <nav class="navbar-light bg-white topbar mb-4 static-top shadow row">

                        <div class=" col "></div>
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav col-2 align-self-end text-right">
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow"  style="z-index: 1">
                                <a class="nav-link dropdown-toggle" @click="accountOpen = !accountOpen" role="button">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ user.user_name }}</span>
                                    <img class="img-profile rounded-circle"
                                    :src="user.user_avatar">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" :class="{'show': accountOpen}">
                                    <a class="dropdown-item" @click="goTo('settings')">
                                        <mdicon name="cogs" class="mr-2 text-gray-400 float-end" size="16"/>
                                        Settings
                                    </a>
                                    <a class="dropdown-item" @click="goTo('activity-log')">
                                        <mdicon name="file-document-outline" class="mr-2 text-gray-400 float-end" size="16"/>
                                        Activity Log
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" @click="$refs.logout.submit()">
                                        <mdicon name="logout" class="mr-2 text-gray-400 float-end" size="16"/>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>

                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">


                        <router-view ref="view"></router-view>

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->


            </div>
            <!-- End of Content Wrapper -->

        </div>


        <form ref="logout" :action="logout" method="POST" class="d-none">
            <input type="hidden" name="_token" :value="csrf">
        </form>

</template>

<script>
import Sidebar from "@/componants/parts/Sidebar.vue";
import {mapActions, mapGetters} from "vuex";
import RouteMixin from "@/mixins/RouteMixin";
export default {
    components: {Sidebar},
    mixins: [RouteMixin],
    computed: {
        logout() {
            return this.$i18n.locale  + '/logout';
            this.goHome();
        },
        ...mapGetters(['user']),
    },
    data() {
        return {
            appName: import.meta.env.VITE_APP_NAME,
            accountOpen: false,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }

    },
    methods: {

        ...mapActions(['getMeta']),
    },
    mounted() {
        if (window.user_locale) {
            this.$i18n.locale = window.user_locale;
        }
    },

    created()
    {
        this.getMeta();
    }
}
</script>

<style scoped>

</style>
