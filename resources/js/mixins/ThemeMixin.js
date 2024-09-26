import {mapGetters} from "vuex";

export default {
    data() {
        return {
           // theme: null,
        }
    },
    computed: {
        isDark() {
            return this.theme === 'dark';
        },
        ...mapGetters(['theme'])
    },
    methods: {
        toggleTheme() {
            this.$store.commit('setTheme', this.theme === 'light' ? 'dark' : 'light');
            this.saveTheme();
            this.applyTheme(this.theme);
        },
        applyTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', this.theme);
        },
        saveTheme() {
            localStorage.setItem('theme', this.theme);
        },
        detectTheme() {
            const theme = localStorage.getItem('theme');
            if (theme) {
                return theme;
            }
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                return 'dark';
            }
            return 'light';
        },
        detectThemeSet() {
            this.$store.commit('setTheme', this.detectTheme());

        },
        themeOnCreated() {
            this.detectThemeSet();
            this.applyTheme(this.theme);
        }
    },
    created() {
        this.detectThemeSet();
    }
}
