window.$ = require('jquery');
window.Vue = require('vue');
window.Buefy = require('buefy');
window.Pusher = require('pusher-js');

//Vue.component('timeline', vue2vis.Timeline);
//vue2vis.Timeline
import Lang from 'lang.js';

import 'moment/min/locales'

import StudentsTimeline from "./components/Timeline/StudentsTimeline";
import ProjectUsers from "./components/ProjectUsers";
import {DataSet, Timeline} from 'vue2vis';
import EditTable from "./components/tools/EditTable";
import LogTable from "./components/tools/LogTable";
import Activity from "./components/Graph/Activity";
import LocalizationSelect from './components/tools/LocalizationSelect'
import Echo from 'laravel-echo';

if (typeof window.UseWebSocketKouky === "undefined") window.UseWebSocketKouky = false;
if (typeof window.KoukyWebSocket === "undefined") window.KoukyWebSocket = false;
if (window.KoukyWebSocket) {

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        forceTLS: true
    });
    window.Echo.connector.pusher.connection.bind('connected', () => {
        window.UseWebSocketKouky = true;
    });
    window.Echo.connector.pusher.connection.bind('disconnected', () => {
        window.UseWebSocketKouky = false;
    });
} else {
    window.Echo = {};
}
const default_locale = window.default_language;
const fallback_locale = window.fallback_locale;
const messages = window.messages;
//const defaultmessages = {};
Vue.prototype.trans = new Lang({messages, locale: default_locale, fallback: fallback_locale});

// if (localStorage.getItem('localization') !== null && false) {
//     const messages = localStorage.getItem('localization');
//     console.log('test', messages)
//     Vue.prototype.trans = new Lang({messages, locale: default_locale, fallback: fallback_locale});
// } else {
//     $.get('/json/localization', (messages) => {
//         localStorage.setItem('localization', messages)
//         Vue.prototype.trans.setMessages(messages);
//         Content.setTableCols();
//     });
// }


const Content = new Vue({
    el: '#content',
    components: {
        StudentsTimeline,
        DataSet,
        ProjectUsers,
        EditTable,
        LogTable,
        Activity,
        LocalizationSelect
    },
    data: {
        loadedLang: false,
        activeTabProject: 'timeline',
        showMenu: false,
        sharelink: '',
        projectId: null,
        shareswitch: true,
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
                field: 'title',
                label: 'Title',
            },
            {
                field: 'subtitle',
                label: 'Subtitle',
            },
            {
                field: 'type',
                label: 'Type',
                centered: true
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
    methods: {
        isEmpty: function (str) {
            return (!str || 0 === str.length);
        },
        goToUrl: function () {
            window.open(this.sharelink);
        },
        clickItems: function (id) {
            if (typeof this.$refs.sttimeline !== "undefined") {
                this.$refs.sttimeline.itemDpClick(id);
            }
        },
        clickGroup: function (id) {
            if (typeof this.$refs.sttimeline !== "undefined") {
                this.$refs.sttimeline.groupDpClick(id);
            }
        },
        deleteAccount: function () {
            this.$buefy.dialog.confirm({
                message: Vue.prototype.trans.get('settings.confirm_delete_account'),
                onConfirm: () => $('#delete_account').submit()
            })
        },
        deleteProject: function () {
            this.$buefy.dialog.confirm({
                message:  Vue.prototype.trans.get('settings.confirm_delete_project'),
                onConfirm: () => $('#delete_project').submit()
            })
        },
        setTableCols: function () {
            this.loadedLang = true;
            this.groupsColumns =  [
                {
                    field: 'id',
                    label: Vue.prototype.trans.get('project.timeline_tables.columns.id'),
                    width: '40',
                    numeric: true
                },
                {
                    field: 'title',
                    label: Vue.prototype.trans.get('project.timeline_tables.columns.title'),
                },
                {
                    field: 'content',
                    label: Vue.prototype.trans.get('project.timeline_tables.columns.content'),
                },
            ];
            this.itemColumns= [
                {
                    field: 'title',
                    label: Vue.prototype.trans.get('project.timeline_tables.columns.title'),
                },
                {
                    field: 'subtitle',
                    label: Vue.prototype.trans.get('project.timeline_tables.columns.subtitle'),
                },
                {
                    field: 'type',
                    label: Vue.prototype.trans.get('project.timeline_tables.columns.type'),
                    centered: true
                },
                {
                    field: 'start',
                    label: Vue.prototype.trans.get('project.timeline_tables.columns.start'),
                    centered: true
                },
                {
                    field: 'end',
                    label:  Vue.prototype.trans.get('project.timeline_tables.columns.end'),
                    centered: true
                },
            ];
        }
    },
    watch: {
        sharelink: function (neu) {
            this.shareswitch = !this.isEmpty(neu);
        },
        shareswitch: function (neu) {
            var that = this;
            if (neu === true) {
                $.get('/ajax/timeline/getShareLink', {project: this.projectId}, function (data) {
                    that.sharelink = data.data;
                }, 'json')
            } else {
                $.get('/ajax/timeline/getShareLink', {project: this.projectId}, function (data) {
                    that.sharelink = "";
                }, 'json')
            }
        },
        activeTabProject: function (neu) {
            if ('timeline' === neu) {
                if (typeof this.$refs.sttimeline !== "undefined") {
                    this.$refs.sttimeline.methodThatForcesUpdate();
                }
            }
        }
    },
    mounted() {
        if (typeof this.$refs.projecttab !== "undefined") {
            var setTab = $(this.$refs.projecttab.$el).data('tab');
            this.activeTabProject = setTab;
        }
        if (typeof this.$refs.sharelink !== "undefined") {
            var $shareObj = $(this.$refs.sharelink.$el).find('input');
            this.sharelink = $shareObj.data('link');
            this.projectId = $shareObj.data('project');
            this.shareswitch = !this.isEmpty(this.sharelink);
        }


    },
    created() {
        this.setTableCols();
    }
});


const Header = new Vue({
    el: '#header'
});
