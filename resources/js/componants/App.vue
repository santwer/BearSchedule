<template>

        <!-- Page Wrapper -->
        <div id="wrapper">
            <sidebar></sidebar>

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <nav class="mb-4 shadow bg-body rounded row">
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



</template>

<script>
import {vBColorMode,useColorMode} from 'bootstrap-vue-next'
import Sidebar from "@/componants/parts/Sidebar.vue";
import {mapActions, mapGetters} from "vuex";
import RouteMixin from "@/mixins/RouteMixin";
import {routeLocationKey} from "vue-router";
import ThemeMixin from "@/mixins/ThemeMixin";
export default {
    components: {Sidebar},
    mixins: [RouteMixin, ThemeMixin],
    computed: {
        routeLocationKey() {
            return routeLocationKey
        },

        ...mapGetters(['user']),
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
        this.themeOnCreated();
    }
}
</script>

<style scoped>
.nav-link {
    cursor: pointer;
}

</style>
