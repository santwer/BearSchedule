export default {
    methods: {
        route(name, params = {}) {
            params.locale = this.$i18n.locale;
            return this.$router.push({name: name, params: params});
        },
        routeWithQuery(name, query = {}) {
            return this.$router.push({name: name, query: query, params: {locale: this.$i18n.locale}});
        },
        routeWithParams(name, params = {}, query = {}) {
            params.locale = this.$i18n.locale;
            return this.$router.push({name: name, params: params, query: query});
        },
        goHome() {
            this.route(home);
        },
        goTo(route) {
            this.route( route);
        },
        goToProject(project) {
            this.route('project',  { id: project});
        }
    }
}
