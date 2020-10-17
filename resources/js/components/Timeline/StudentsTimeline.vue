<template>
    <div>
        <div v-if="dummeLoop" ref="fullline" id="currentContentTime">
            <div class="buttons">
                <timeline-item-model ref="itemmodel" :project="project" v-if="canAddItems()"></timeline-item-model>
                <timeline-group-model ref="groupmodel" :project="project"  v-if="canAddItems()"></timeline-group-model>


                    <b-dropdown aria-role="list" v-model="selectedOption" class="is-float-right">
                    <button class="button is-small  is-float-right" slot="trigger" slot-scope="{ activeMenu }">
                        <span>Display</span>
                        <b-icon :icon="activeMenu ? 'menu-up' : 'menu-down'"></b-icon>
                    </button>

                    <b-dropdown-item aria-role="listitem" value="default">Default</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" value="weekday">weekday</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" value="week">week</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" value="day">day</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" value="month">month</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" value="year">year</b-dropdown-item>
                </b-dropdown>

                <b-dropdown aria-role="list" v-model="selectedZoom">
                    <button class="button is-small is-float-right" slot="trigger" slot-scope="{ activeZoom }">
                        <span>Zoom</span>
                        <b-icon :icon="activeZoom ? 'menu-up' : 'menu-down'"></b-icon>
                    </button>
                    <b-dropdown-item aria-role="listitem" value="day">day</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" value="week">week</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" value="month">month</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" value="year">year</b-dropdown-item>
                </b-dropdown>

            </div>

            <b-message title="No Items" v-if="MsgIsActive" aria-close-label="Close message">
                Since there a no Items, its not possible to show the timeline, yet. Please add Items and Groups first.
            </b-message>
            <timeline ref="timeline"
                      v-if="!MsgIsActive"
                      @double-click="dpClick"
                      @itemover="itemOver"
                      @itemout="itemOut"
                      @rangechanged="rangeChange"
                      @mouse-down="mouseDown"
                      @mouse-move="mouseMove"
                      @mouse-up="mouseUp"
                      :items="items"
                      :groups="groups"
                      :options="options"></timeline>
        </div>
    </div>
</template>

<script>
    import {DataSet, Timeline} from 'vue2vis';
    import TimelineItemModel from "./TimelineItemModel";
    import TimelineGroupModel from "./TimelineGroupModel";
    import TimelineItemShowModel from "./TimelineItemShowModel";
    const Handlebars = require("handlebars");

    export default {
        name: "StudentsTimeline",
        props: ['project', 'role', 'datapath'],
        components: {
            TimelineGroupModel,
            TimelineItemModel,
            Timeline,
            DataSet
        },
        data() {
            return {
                dummeLoop: false,
                groups: [],
                items: [],
                MsgIsActive: false,
                options: {
                    editable: false,
                },
                activeMenu: false,
                activeZoom: false,
                selectedOption: null,
                selectedZoom: null,
                getPath: '',
                currentItem: null,
                handlebarHelperRegistered: false,
                groupMoveable: null,
            }
        },
        methods: {
            canAddItems: function () {
                return this.role === 'ADMIN' || this.role === 'EDITOR';
            },
            registerHandlebarHelper: function () {
                if(!this.handlebarHelperRegistered) {
                    Handlebars.registerHelper('ifEquals', function (arg1, arg2, options) {
                        return (arg1 == arg2) ? options.fn(this) : options.inverse(this);
                    });
                    this.handlebarHelperRegistered = true;
                }
            },
            getData: function () {
                this.registerHandlebarHelper();
                const loadingComponent = this.$buefy.loading.open({
                    container: this.$el
                });
                var that = this;
                $.get(this.getPath, {project: this.project}, function (data) {
                    if (typeof data.groups !== "undefined") {
                        that.groups = data.groups;
                    }
                    if (typeof data.items !== "undefined") {
                        that.items = data.items;
                    }
                    if (typeof data.options !== "undefined") {
                        if(typeof data.options.template !== "undefined") {
                            data.options.template = Handlebars.compile(data.options.template);
                        }
                        if(typeof data.options.groupTemplate !== "undefined") {
                            data.options.groupTemplate = Handlebars.compile(data.options.groupTemplate);
                        }
                        if(typeof data.options.displayscale !== "undefined") {
                            var dates = that.setZoomRange(data.options.displayscale);
                            if(typeof dates[1] !== "undefined") {
                                data.options.start = dates[0];
                                data.options.end = dates[1];
                            }
                            delete data.options.displayscale;
                        }
                        that.options = data.options;
                        that.options['snap'] = function (date, scale, step) {
                            return new Date(date.toDateString());
                        };
                       that.options['groupOrder'] = function (a, b) {
                            return a.order - b.order;
                        };
                        that.options['order'] = function (a, b) {
                            return a.start > b.start;
                        };

                    }

                    that.dummeLoop = true;
                    that.MsgIsActive = that.items.length  === 0;
                    setTimeout(() => loadingComponent.close(), 1000);
                }, 'json')
            },
            dpClick: function (e) {
                if(this.currentItem !== null) {
                    this.itemDpClick(this.currentItem);
                } else if(e.group !== null && e.item === null && e.x < 0) {
                    this.groupDpClick(e.group);
                }
                else if(e.group !== null && e.what === 'background' && typeof e.target !== "undefined") {
                    console.log('dp bg', e);
                }
                else {
                    console.log('dp', e);
                }
            },
            itemDpClick: function (itemId) {
                var item = this.findObjectInArrayByProperty(this.items, 'id', itemId);
                if(typeof this.$refs.itemmodel === "undefined") {
                    this.openItemShowModal(item)
                } else {
                    this.$refs.itemmodel.openModelItem(item);
                    if (this.MsgIsActive) {
                        this.getData();
                    }
                }
            },
            groupDpClick: function (groupId) {
                if(typeof this.$refs.groupmodel !== "undefined") {
                    var item = this.findObjectInArrayByProperty(this.groups, 'id', groupId);
                    this.$refs.groupmodel.openModelItem(item);
                }
            },
            openItemShowModal: function (item) {
                var that = this;
                this.$buefy.modal.open({
                    parent: this,
                    component: TimelineItemShowModel,
                    props: {
                        item: item,
                    },
                    distroyOnHide: false,
                    hasModalCard: true,
                    trapFocus: true
                })
            },
            newItemInTimeLine: function(data) {
                this.items.push(data);
            },
            newGroupInTimeLine: function(data) {
                this.groups.push(data);
            },
            deleteItemInTimeLine: function (id) {
                var item = this.findObjectInArrayByProperty(this.items, 'id', id);
                const index = this.items.indexOf(item);
                if (index > -1) {
                    this.items.splice(index, 1);
                }
            },
            deleteGroupInTimeLine: function (id) {
                var item = this.findObjectInArrayByProperty(this.groups, 'id', id);
                const index = this.groups.indexOf(item);
                if (index > -1) {
                    this.groups.splice(index, 1);
                }
            },
            itemOver: function (e) {
                this.currentItem = null;
                if(typeof e.item !== "undefined") {
                    this.currentItem = e.item;
                }
            },
            itemOut: function (e) {
                this.currentItem = null;
            },
            findObjectInArrayByProperty: function(array, propertyName, propertyValue) {
                return array.find((o) => { return o[propertyName] === propertyValue });
            },
            getCurrentWeek: function (curr) {
               let week = [];

                for (let i = 1; i <= 7; i++) {
                    let first = curr.getDate() - curr.getDay() + i;
                    if(i === 1 || i === 7) {
                        let day = new Date(curr.setDate(first)).toISOString().slice(0, 10);
                        week.push(day);
                    }
                }
                return week;
            },
            /**
             * @param curr Date
             */
            getCurrentMonth: function (curr) {
               var month = curr.getMonth();
               var year = curr.getFullYear();
               var firstday = new Date(year, month, 1);
               var lastday = new Date(year, month + 1, 0);
               return [firstday, lastday];
            },
            getCurrentYear: function (curr) {
                var year = curr.getFullYear();
                var firstday = new Date(year, 0, 1);
                var lastday = new Date(year + 1, 12, 0);
                return [firstday, lastday];
            },
             getCurrentDay: function (curr) {
                var year = curr.getFullYear();
                var month = curr.getMonth();
                var day = curr.getDate();
                var firstday = new Date(year, month, day,0,0);
                var lastday = new Date(year, month, day,23,59);
                return [firstday, lastday];
            },
            rangeChange: function () {
                this.selectedZoom = null;
            },
            setTimeAxisOption: function (value) {
                if (value !== 'default') {
                    this.options.timeAxis = {
                        scale: value,
                        step: 1
                    }
                } else {
                    this.options.timeAxis = {
                        step: 1
                    };
                }
            },
            setZoomRange: function(value) {
                const today = new Date();
                var dates = [];
                switch (value) {
                    case 'week':
                        dates = this.getCurrentWeek(today);
                        break;
                    case 'month':
                        dates = this.getCurrentMonth(today);
                        break;
                    case 'year':
                        dates = this.getCurrentYear(today);
                        break;
                    case 'day':
                        dates = this.getCurrentDay(today);
                        break;
                }
                return dates;
            },
            methodThatForcesUpdate() {
                const loadingComponent = this.$buefy.loading.open({
                    container: this.$el
                });
                this.MsgIsActive = true;
                this.$nextTick(() => {
                    this.MsgIsActive = false;
                    setTimeout(() => loadingComponent.close(), 500);
                });
            },
            updateGroupOrder: function () {
                var that = this;
                $.ajax({
                    method: "PUT",
                    url: "/ajax/timeline/grouporder",
                    data: {groups: that.groups},
                    dataType: 'json'
                })
            },
            mouseUp: function (e) {
                if(this.groupMoveable !== null) {
                    this.groupMoveable = null;
                    this.updateGroupOrder();
                }
            },
            mouseDown: function (e) {
                var $target = $(e.event.target);
                var group = e.group;
                if($target.hasClass('timeline-dots')) {
                    this.groupMoveable = group;
                }
            },
            mouseMove: function (e) {
                if(this.groupMoveable !== null && e.group !== this.groupMoveable && this.canAddItems()) {
                    this.setGroupOrder(e.group);
                }
            },
            setGroupOrder: function (overgrp) {
                overgrp = overgrp || null;
                this.options.groupOrder = 'order';

                var oldIndex = this.groups.findIndex(p => p.id === this.groupMoveable);
                var newIndex = this.groups.findIndex(p => p.id === overgrp);
                if(typeof this.groups[oldIndex] !== "undefined" && typeof this.groups[newIndex] !== "undefined") {
                    var b = this.groups[oldIndex].order;
                    this.groups[oldIndex].order = this.groups[newIndex].order;
                    this.groups[newIndex].order = b;
                }
            }
        },
        watch: {
            selectedOption: function (value, n) {
                this.setTimeAxisOption(value);
                this.$refs.timeline.setOptions(this.options);
            },
            selectedZoom: function (value, n) {
                var dates = this.setZoomRange(value);
                if(typeof dates[1] !== "undefined")
                    this.$refs.timeline.setWindow(dates[0], dates[1]);
            },
            items: function (value, n) {
                if(this.MsgIsActive && value.length > 0) {
                    this.MsgIsActive = false;
                }
            }
        },
        mounted() {
            if(typeof this.datapath !== "undefinded") {
                this.getPath = this.datapath;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                }
            });
            this.getData();
        }
    }
</script>

<style scoped lang="less">
.is-float-right {
    margin-left: auto;
}
</style>
