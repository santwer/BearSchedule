<template>


    <form ref="logout" :action="logout" method="POST" class="d-none">
        <input type="hidden" name="_token" :value="csrf">
    </form>
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion scollablefit"
        :class="{'toggled': toggled}">

        <div class="position-absolute bottom-0 small bottom-footer tofadeOut">
            <BButtonGroup size="sm" class="mx-1">
                <BDropdown v-model="showLang" size="sm" :variant="isDark ? 'outline-light' : 'outline-light'">
                    <template #button-content>
                        <div :style="!toggled ? 'min-width: 160px' : ''"></div>
                        <mdicon name="earth" size="16"/>
                        <span v-if="!toggled">{{ $t('locale.' + this.$i18n.locale) }}</span>
                    </template>
                    <BDropdownItem @click="changeLocale('en')">{{ $t('locale.en') }}</BDropdownItem>
                    <BDropdownItem @click="changeLocale('de')">{{ $t('locale.de') }}</BDropdownItem>
                </BDropdown>

                <BButton :variant="isDark ? 'outline-light' : 'outline-light'" @click="toggleTheme"
                         :style="!toggled ? 'min-width: 35px' : ''"
                         :title="$t('general.dark_mode_toogle')">
                    <mdicon name="theme-light-dark" size="16"/>
                </BButton>
            </BButtonGroup>
            <div v-if="!toggled">
                <div class="px-3">&copy; {{ new Date().getFullYear() }}
                    <a target="_blank" href="https://github.com/santwer/BearSchedule">{{ appName }}</a>
                </div>
                <ul class="inline-with-dot-inbetween px-3">
                    <li><a href="/disclaimer" target="_blank">{{ $t('menu.imprint') }}</a></li>
                    <li><a href="/privacy" target="_blank">{{ $t('menu.privacy') }}</a></li>
                </ul>
            </div>
            <div v-else>
                <div class="px-3">&copy; {{ new Date().getFullYear() }}
                    <a target="_blank" href="https://github.com/santwer/BearSchedule">
                        <mdicon name="teddy-bear" size="16"/>
                    </a>
                </div>
            </div>
        </div>

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
            <a class="nav-link dropdown-toggle"   @click="goHomeAndOpen" role="button">
                <span>{{ user.user_name }}</span>
                <img class="img-profile rounded-circle float-end" v-if="!toggled" :src="user.user_avatar">
            </a>
            <!-- Dropdown - User Information -->
            <div class="collapse" :class="{'hidden': !accountOpen, 'show': accountOpen}">
                <div class=" py-2 collapse-inner rounded "
                     :class="{'bg-white': !isDark, 'bg-gray-400': isDark, 'text-gray-800': isDark}">
                    <a class="collapse-item" @click="goTo('settings')">
                        <mdicon name="account-cog" class="mr-2 text-gray-800 float-end" size="16"/>
                        {{ $t('project_settings') }}
                    </a>
                    <a class="collapse-item" @click=" goTo('activity-log')">
                        <mdicon name="file-document-outline" class="mr-2 text-gray-800 float-end" size="16"/>
                        {{ $t('project_logs') }}
                    </a>
                    <a class="collapse-item" @click="$refs.logout.submit()">
                        <mdicon name="logout" class="mr-2 text-gray-800 float-end" size="16"/>
                        {{ $t('general.logout') }}
                    </a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider mb-0">

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item" :class="{'active': activeProjects}">
            <a class="nav-link" @click="activeProjects = !activeProjects">
                <mdicon name="chart-gantt" class="float-end"/>
                <span>{{ $t('general.projects') }}</span>
            </a>
            <div id="collapsePages" class="collapse" :class="{hidden: !activeProjects, 'show': activeProjects}"
                 data-parent="#accordionSidebar">
                <div class=" py-2 collapse-inner rounded "
                     :class="{'bg-white': !isDark, 'bg-gray-400': isDark, 'text-gray-800': isDark}">
                    <a class="collapse-item" :class="{active: project.id === $route.params.id}"
                       v-for="project in projects"
                       @click="goToProject(project.id)">{{ project.name }}

                    </a>

                </div>
                <div class=" py-2 collapse-inner rounded "
                     :class="{'bg-white': !isDark, 'bg-gray-400': isDark, 'text-gray-800': isDark}">
                    <a class="collapse-item" href="/project/create">
                        <mdicon name="plus" class="mr-2 float-end" size="16"/>
                        {{ $t('menu.new_project') }}
                    </a>
                </div>
            </div>
        </li>

        <li class="nav-item" :class="{'active': activeArchive}" v-if="archivedProjects.length > 0">
            <a class="nav-link" @click="activeArchive = !activeArchive">
                <mdicon name="archive-outline" class="float-end"/>
                <span>{{ $t("general.archive") }}</span>
            </a>
            <div id="collapsePages" class="collapse" :class="{hidden: !activeArchive, 'show': activeArchive}">
                <div class=" py-2 collapse-inner rounded "
                     :class="{'bg-white': !isDark, 'bg-gray-800': isDark, 'text-gray-400': isDark}">
                    <a class="collapse-item"
                       v-for="project in archivedProjects"
                       @click="goToProject(project.id)">{{ project.name }}</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="sidebar-closer d-none d-md-inline" :class="{'sidebar-closer-closed': toggled}">
            <button class="rounded-circle border-0 py-1 shadow-sm bg-body rounded" @click="toggled = !toggled">

                <mdicon name="chevron-right" size="32" v-if="toggled"/>
                <mdicon name="chevron-left" size="32" v-else/>
            </button>
        </div>


    </ul>
</template>

<script>
import Logo from '../icons/Logo.vue'
import {mapActions, mapGetters} from "vuex";
import RouteMixin from "@/mixins/RouteMixin";
import ThemeMixin from "@/mixins/ThemeMixin";
import {BButtonGroup, BButton, BDropdownDivider, BDropdownItem, BDropdown} from "bootstrap-vue-next";

export default {
    components: {
        Logo, BButtonGroup, BButton, BDropdownDivider, BDropdownItem, BDropdown
    },
    mixins: [RouteMixin, ThemeMixin],
    data() {
        return {
            appName: import.meta.env.VITE_APP_NAME,
            toggled: false,
            showLang: false,
            activeProjects: true,
            activeArchive: false,
            accountOpen: false,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

        }

    },
    computed: {
        ...mapGetters(['archivedProjects', 'projects', 'user']),
        logout() {
            return '/' + this.$i18n.locale + '/logout';
            this.goHome();
        }
    },
    methods: {
        ...mapActions(['getMeta']),
        goHomeAndOpen() {
            this.goTo('home');
            this.$nextTick(() => {
                this.accountOpen = !this.accountOpen;
            });
        },
        changeLocale(locale) {
            //find current locale in route and replace with new
            let url = this.$route.fullPath;
            let currentLocale = this.$i18n.locale;
            if (currentLocale === locale) {
                return;
            }

            if (url.includes('/' + currentLocale + '/')) {
                url = url.replace('/' + currentLocale + '/', '/' + locale + '/');
            }

            window.location.href = url;

        }
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

.scollablefit {
    padding-bottom: 50px;
}

.collapse-item .editbtn {
    display: none;
    margin-top: -5px;
    position: absolute;
    right: 10px;
}
.toggled .collapse-item .editbtn {
    right: -35px;
}
.collapse-item:hover .editbtn {
    display: inline;
}


.tofadeOut {
    /*  z-index: 10;
      min-width: 225px;
      background: rgb(0,39,84);
      background: linear-gradient(0deg, rgba(0,39,84,1) 50%, rgba(0,39,84,0) 100%);
      padding-top: 50px; */
}
</style>
