window.$ = require('jquery');
window.Vue = require('vue');
window.Buefy = require('buefy');



import StudentsTimeline from "./components/Timeline/StudentsTimeline";
import { DataSet, Timeline } from 'vue2vis';
import Lang from 'lang.js';
const default_locale = window.default_locale;
const fallback_locale = window.fallback_locale;
const messages = window.messages;
Vue.prototype.trans = new Lang({messages, locale: default_locale, fallback: fallback_locale});

const Content = new Vue({
    el: '#project-timeline',
    components: {
        StudentsTimeline,
        DataSet,
    },
    data: {
    },
    mounted() {
        //csrf_token()
    },
    beforeCreate() {
        var script = document.currentScript;
        var fullUrl = script.src;
        var re = new RegExp("share/(.*)/share.js");
        var result = fullUrl.match(re);
        if(typeof result[1] !== "undefined" && result !== "") {
            $('#project-timeline').html('<students-timeline datapath="/share/' + result[1] + '/ajax/getdata"></students-timeline>');
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{$csrf_token}'
            }
        });
    }
});
