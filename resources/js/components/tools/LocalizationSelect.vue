<template>
    <b-dropdown
        v-model="currentMenu"
        aria-role="list"
        :position="getPosition()"
        :class="getClasses()"
        @change="changeLocale"
    >
        <template #trigger>
            <b-button
                :label="locals[currentMenu]"

                :class="getButtonClasses()"
                icon-left="earth"
                icon-right="menu-down"/>
        </template>


        <b-dropdown-item
            v-for="(menu, index) in locals"
            :key="index"
            :value="index"

            aria-role="listitem">
            <div class="media">
                <div class="media-content">
                    <h3>{{ menu }}</h3>
                </div>
            </div>
        </b-dropdown-item>
    </b-dropdown>
</template>

<script>
export default {
    name: "localizationSelect",
    props: ['locals', 'init', 'extra'],
    data() {
        return {
            currentMenu: 'en',
            menus: [
                {text: 'Deutsch'},
                {text: 'English'},
            ]
        }
    },
    methods: {
        getButtonClasses() {
            if(this.extra) {
                return 'navbar-item is-small';
            }
            return 'navbar-item';
        },
        getPosition() {
            if(this.extra) {
                return 'is-top-right';
            }
            return null;
        },
        getClasses(){
            console.log(this.extra)
          if(this.extra) {
              return 'extra';
          }
          return '';
        },
        changeLocale(val) {
            var path = window.location.pathname;
            var pathparts = window.location.pathname.split('/');
            if(pathparts[1]) {
                pathparts[1] = val;
                path = pathparts.join('/');
            }
            window.location.href = path;
        }
    },
    mounted() {
        this.currentMenu = this.init;
    }
}
</script>

<style scoped>
    .extra {
        float: right;
        margin-top: -15px;
    }
    .hero.is-success .navbar-item, .hero.is-success .navbar-link {
        color: #4a4a4a;
    }
</style>
