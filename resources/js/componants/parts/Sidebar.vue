<template>


    <form ref="logout" :action="logout" method="POST" class="d-none">
        <input type="hidden" name="_token" :value="csrf">
    </form>
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" :class="{'toggled': toggled}">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" @click="goHome">
            <div class="sidebar-brand-icon1">
                <logo class="bear-logo "/>
            </div>
            <div class="sidebar-brand-text mx-2">{{ appName }}</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">


        <!-- Nav Item - Dashboard -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" @click="accountOpen = !accountOpen" role="button">
                <span>{{ user.user_name }}</span>
                <img class="img-profile rounded-circle float-end" v-if="!toggled" :src="user.user_avatar">
            </a>
            <!-- Dropdown - User Information -->
            <div class="collapse" :class="{'hidden': !accountOpen, 'show': accountOpen}">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" @click="goTo('settings')">
                    <mdicon name="account-cog" class="mr-2 text-gray-400 float-end" size="16"/>
                    Account Settings
                </a>
                <a class="collapse-item" @click="goTo('activity-log')">
                    <mdicon name="file-document-outline" class="mr-2 text-gray-400 float-end" size="16"/>
                    Activity Log
                </a>
                <a class="collapse-item" @click="$refs.logout.submit()">
                    <mdicon name="logout" class="mr-2 text-gray-400 float-end" size="16"/>
                    Logout
                </a>
            </div>
            </div>
        </li>

        <hr class="sidebar-divider my-0">
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" @click="goTo('home')">
                <mdicon name="view-dashboard" class="float-end"/>
                <span>{{ $t('menu.home')}}</span>
            </a>
            <a class="nav-link" @click="goTo('settings')">
                <mdicon name="cogs" class="float-end"/>
                <span>{{ $t('menu.settings')}}</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider mb-0">

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item" :class="{'active': activeProjects}">
            <a class="nav-link" @click="activeProjects = !activeProjects">
                <mdicon name="chart-gantt" class="float-end"/>
                <span>{{ $t('general.projects')}}</span>
            </a>
            <div id="collapsePages" class="collapse" :class="{hidden: !activeProjects, 'show': activeProjects}"
                 data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" :class="{active: project.id === $route.params.id}"
                       v-for="project in projects"
                       @click="goToProject(project.id)">{{ project.name}}</a>
                </div>
            </div>
        </li>

        <li class="nav-item" :class="{'active': activeArchive}" v-if="archivedProjects.length > 0">
            <a class="nav-link" @click="activeArchive = !activeArchive">
                <mdicon name="archive-outline" class="float-end"/>
                <span>{{ $t("general.archive") }}</span>
            </a>
            <div id="collapsePages" class="collapse"  :class="{hidden: !activeArchive, 'show': activeArchive}">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item"
                       v-for="project in archivedProjects"
                       @click="goToProject(project.id)">{{ project.name}}</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="sidebar-closer d-none d-md-inline" :class="{'sidebar-closer-closed': toggled}">
            <button class="rounded-circle border-0 py-1 shadow-sm bg-body rounded" @click="toggled = !toggled">

                <mdicon name="chevron-right" size="32" v-if="toggled"/>
                <mdicon name="chevron-left" size="32" v-else />
            </button>
        </div>
        <div class="position-absolute bottom-0 small bottom-footer" v-if="!toggled">
            <div class="px-3">&copy; {{ new Date().getFullYear() }} <a target="_blank" href="https://github.com/santwer/BearSchedule">{{ appName }}</a></div>
            <ul class="inline-with-dot-inbetween px-3">
                <li><a href="/imprint" target="_blank">{{ $t('menu.imprint') }}</a></li>
                <li><a href="/privacy" target="_blank">{{ $t('menu.privacy') }}</a></li>
            </ul>
        </div>

    </ul>
</template>

<script>
import Logo from '../icons/Logo.vue'
import {mapActions, mapGetters} from "vuex";
import RouteMixin from "@/mixins/RouteMixin";
export default {
    components: {
        Logo
    },
    mixins: [RouteMixin],
    data() {
        return {
            appName: import.meta.env.VITE_APP_NAME,
            toggled: false,
            activeProjects: true,
            activeArchive: false,
            accountOpen: false,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

        }

    },
    computed: {
        ...mapGetters(['archivedProjects','projects','user']),
        logout() {
            return '/' + this.$i18n.locale  + '/logout';
            this.goHome();
        },
    },
    methods: {
        ...mapActions(['getMeta']),
    }
}
</script>

<style scoped>
    .nav-link {
        cursor: pointer;
    }
    .sidebar-closer {
        cursor: pointer;
        position: absolute;
        top: 50%;
        margin-top: -20px;
        left: 200px;
    }
    .sidebar-closer-closed {
        left: 80px;
    }
    ul.inline-with-dot-inbetween li {
        display: inline;
    }
    ul.inline-with-dot-inbetween li:not(:last-child):after {
        content: "•";
        margin: 0 5px;
    }
    .bottom-footer a {
        color: #ffffff;
    }
</style>
