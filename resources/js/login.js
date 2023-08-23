import LocalizationSelect from "./components/tools/LocalizationSelect";

import $ from 'jquery';
window.$ = $;
import Vue from 'vue';
window.Vue = Vue;
import Buefy from 'buefy';
window.Buefy = Buefy;


const Content = new Vue({
    el: '#sectionContent',
    components: {
        LocalizationSelect
    },
    data: {
    },
});
