<template>
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
                    <a class="collapse-item"
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
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" @click="toggled = !toggled">

                <mdicon name="chevron-right" size="32" v-if="toggled"/>
                <mdicon name="chevron-left" size="32" v-else />
            </button>
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
        }

    },
    computed: {
        ...mapGetters(['archivedProjects','projects']),
    },
    methods: {

    }
}
</script>

<style scoped>
    .nav-link {
        cursor: pointer;
    }
</style>
