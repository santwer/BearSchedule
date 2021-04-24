import LocalizationSelect from "./components/tools/LocalizationSelect";

window.$ = require('jquery');
window.Vue = require('vue');
window.Buefy = require('buefy');


const Content = new Vue({
    el: '#sectionContent',
    components: {
        LocalizationSelect
    },
    data: {
    },
});
