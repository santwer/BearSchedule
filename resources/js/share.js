import axios from 'axios';
window.axios = axios;
window.axios.defaults.baseURL = '/share/';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';


import { createApp } from 'vue'
import {createBootstrap} from 'bootstrap-vue-next'
import { createI18n } from 'vue-i18n'
import vueDebounce from 'vue-debounce'
import mdiVue from 'mdi-vue/v3'
import * as mdijs from '@mdi/js'


import Share from './componants/Share.vue'
import appStorage from "@/storage/AppStorage";

import en from '../../lang/en.json'
import de from '../../lang/de.json'
import moment from 'moment/min/moment-with-locales';

//get locale from browser storage if not set from browser
let locale = localStorage.getItem('locale') || navigator.language || navigator.userLanguage || 'en';
locale = locale.split('-')[0];


const i18n = createI18n({
    locale: locale,
    fallbackLocale: window.fallbackLocale || 'en',
    messages: {
        de, en
    }
});


const app = createApp(Share)
    .use(appStorage)
    .use(createBootstrap())
    .directive('debounce', vueDebounce({ lock: true }))
    .use(mdiVue, {
        icons: mdijs
    })
    .use(i18n)
    .mount('#app')
