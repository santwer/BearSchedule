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
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Sven Antwertinger</span>
                                    <img class="img-profile rounded-circle"
                                    src="https://www.gravatar.com/avatar/45c48cce2e2d7fbdea1afc51c7c6ad26?f=y&d=robohash">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" :class="{'show': accountOpen}">
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Settings
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Activity Log
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" @click="$refs.logout.submit()">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
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

export default {
    components: {Sidebar},
    computed: {
        logout() {
            return this.$i18n.locale  + '/logout';
            this.$router.push({name: 'home', params: {locale: this.$i18n.locale}});
        },
    },
    data() {
        return {
            appName: import.meta.env.VITE_APP_NAME,
            accountOpen: false,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }

    },
    methods: {

    },
    mounted() {
        if (window.user_locale) {
            this.$i18n.locale = window.user_locale;
        }
    },

    created()
    {

    }
}
</script>

<style scoped>

</style>
