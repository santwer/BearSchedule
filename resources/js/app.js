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
        showMenu: true,
        groupsColumns: [
            {
                field: 'id',
                label: 'ID',
                width: '40',
                numeric: true
            },
            {
                field: 'title',
                label: 'Title',
            },
            {
                field: 'content',
                label: 'Content',
            },
        ],
        itemColumns: [
            {
                field: 'id',
                label: 'ID',
                width: '40',
                numeric: true
            },
            {
                field: 'group',
                label: 'Group',
                width: '40',
                numeric: true
            },
            {
                field: 'title',
                label: 'Title',
            },
            {
                field: 'content',
                label: 'Content',
            },
            {
                field: 'start',
                label: 'Start',
                centered: true
            },
            {
                field: 'end',
                label: 'End',
                centered: true
            },
        ]
    },
});
