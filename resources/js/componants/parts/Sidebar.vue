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
                    <a class="collapse-item" href="login.html">Login</a>
                    <a class="collapse-item" href="register.html">Register</a>
                    <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                </div>
            </div>
        </li>

        <li class="nav-item" :class="{'active': activeArchive}">
            <a class="nav-link" @click="activeArchive = !activeArchive">
                <mdicon name="archive-outline" class="float-end"/>
                <span>{{ $t("general.archive") }}</span>
            </a>
            <div id="collapsePages" class="collapse"  :class="{hidden: !activeArchive, 'show': activeArchive}">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="login.html">Login</a>
                    <a class="collapse-item" href="register.html">Register</a>
                    <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
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

export default {
    components: {
        Logo
    },
    data() {
        return {
            appName: import.meta.env.VITE_APP_NAME,
            toggled: false,
            activeProjects: true,
            activeArchive: false,
        }

    },
    methods: {
        goHome() {
            this.$router.push({name: 'home', params: {locale: this.$i18n.locale}});
        },
        goTo(route) {
            this.$router.push({name: route, params: {locale: this.$i18n.locale}});
        },
        goToProject(project) {
            this.$router.push({name: 'project', params: {locale: this.$i18n.locale, id: project.id}});
        }
    }
}
</script>

<style scoped>
    .nav-link {
        cursor: pointer;
    }
</style>
