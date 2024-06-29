import './bootstrap';


import { createApp } from 'vue'
import {createBootstrap} from 'bootstrap-vue-next'
import { createI18n } from 'vue-i18n'
import router from './router'
import mdiVue from 'mdi-vue/v3'
import * as mdijs from '@mdi/js'

import App from './componants/App.vue'
import appStorage from "@/storage/AppStorage";

import en from '../../lang/en.json'
import de from '../../lang/de.json'

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

const app = createApp(App)
    .use(router)
    .use(appStorage)
    .use(createBootstrap())
    .use(mdiVue, {
        icons: mdijs
    })
    .use(i18n)
    .mount('#app')
