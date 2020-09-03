window.$ = require('jquery');
window.Vue = require('vue');
window.Buefy = require('buefy');
//Vue.component('timeline', vue2vis.Timeline);
//vue2vis.Timeline

import StudentsTimeline from "./components/StudentsTimeline";
import { DataSet, Timeline } from 'vue2vis';
const Content = new Vue({
    el: '#content',
    components: {
        StudentsTimeline,
        DataSet
    },
    data: {

    },
});
