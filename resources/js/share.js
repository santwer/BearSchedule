window.$ = require('jquery');
window.Vue = require('vue');
window.Buefy = require('buefy');

import StudentsTimeline from "./components/StudentsTimeline";
import { DataSet, Timeline } from 'vue2vis';
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
