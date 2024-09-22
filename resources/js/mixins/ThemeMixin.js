export default {
    data() {
        return {
            theme: null,
        }
    },
    computed: {
        isDark() {
            return this.theme === 'dark';
        }
    },
    methods: {
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            this.saveTheme();
            this.applyTheme(this.theme);
            this.$nextTick(() => {
               //reload page
               window.location.reload();
            });
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
            this.theme = this.detectTheme();
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
