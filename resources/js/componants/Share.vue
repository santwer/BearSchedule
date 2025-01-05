<template>

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <nav class="mb-3 shadow bg-body rounded row">
                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <timeline
                            :pid="currentPath()"
                        ></timeline>

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->


            </div>
            <!-- End of Content Wrapper -->

        </div>



</template>

<script>
import {vBColorMode,useColorMode} from 'bootstrap-vue-next'
import Sidebar from "@/componants/parts/Sidebar.vue";
import {mapActions, mapGetters} from "vuex";
import RouteMixin from "@/mixins/RouteMixin";
import {routeLocationKey} from "vue-router";
import ThemeMixin from "@/mixins/ThemeMixin";
import Timeline from "@/componants/pages/Timeline.vue";
export default {
    components: {Timeline, Sidebar},
    mixins: [RouteMixin, ThemeMixin],
    computed: {
        routeLocationKey() {
            return routeLocationKey
        },

        ...mapGetters(['user', 'isLoading']),
    },
    data() {
        return {
            mode: null,
            appName: import.meta.env.VITE_APP_NAME,
            accountOpen: false,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }

    },
    methods: {
        changeColorMode() {
            console.log('change color mode')
            //get the current color mode
            let currentMode = this.$root;
            console.log('current mode', currentMode,useColorMode())

        },
        currentPath() {
            //current path without $router or $route
            let pathname = window.location.pathname;
            //get uuid after share/ in pathname
            return pathname.split('/').pop();

        },
        ...mapActions(['getMeta']),
    },
    mounted() {
        if (window.user_locale) {
            this.$i18n.locale = window.user_locale;
        }
    },

    created()
    {

        this.themeOnCreated();
        // this.$router.beforeEach((to, from, next) => {
        //     console.log('test1');
        //     this.$store.commit('setLoading', true);
        //     this.$nextTick(() => {
        //         next();
        //     });
        // });
        // this.$router.afterEach((to, from) => {
        //     setTimeout(() => {
        //         console.log('test2');
        //         this.$store.commit('setLoading', false);
        //     }, 1200);
        // });
    }
}
</script>

<style scoped>
.nav-link {
    cursor: pointer;
}

</style>
