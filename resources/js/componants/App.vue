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

                        <div class="col" style="padding-top: 2.4rem;" v-if="$route.params.id">
                            <div class="nav nav-tabs ">
                                <div class="nav-item">
                                    <a class="nav-link" :class="{active: $route.name === 'project'}" style="height: 2rem"  @click="goToProjectPage($route.params.id, 'timeline')">
                                        <mdicon name="chart-gantt" size="24"/>
                                        Timeline
                                    </a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link"  :class="{active: $route.name === 'project-items'}" style="height: 2rem"  @click="goToProjectPage($route.params.id, 'items')">
                                        <mdicon name="id-card" size="24"/>
                                        Items
                                    </a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link"  :class="{active: $route.name === 'project-groups'}" style="height: 2rem"  @click="goToProjectPage($route.params.id, 'groups')">
                                        <mdicon name="folder-multiple" size="24"/>
                                        Groups
                                    </a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link"  :class="{active: $route.name === 'project-share'}" style="height: 2rem"  @click="goToProjectPage($route.params.id, 'share')">
                                        <mdicon name="share-variant" size="24"/>
                                        Share
                                    </a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link"  :class="{active: $route.name === 'project-settings'}" style="height: 2rem"  @click="goToProjectPage($route.params.id, 'settings')">
                                        <mdicon name="cog" size="24"/>
                                        Settings
                                    </a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link"  :class="{active: $route.name === 'project-logs'}" style="height: 2rem"  @click="goToProjectPage($route.params.id, 'logs')">
                                        <mdicon name="text-box-outline" size="24"/>
                                        Logs
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col" v-else></div>
                        <div class="col-2">
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav  align-self-end text-right">
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow"  style="z-index: 10">
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
                        </div>

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
import {routeLocationKey} from "vue-router";
export default {
    components: {Sidebar},
    mixins: [RouteMixin],
    computed: {
        routeLocationKey() {
            return routeLocationKey
        },
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
.nav-link {
    cursor: pointer;
}
</style>
