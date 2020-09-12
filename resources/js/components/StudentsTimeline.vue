<template>
    <div>
        <div v-if="dummeLoop" ref="fullline" id="currentContentTime">
            <div class="buttons">
                <timeline-item-model ref="itemmodel" :project="project"></timeline-item-model>
                <timeline-group-model ref="groupmodel" :project="project"></timeline-group-model>


                <b-dropdown aria-role="list" v-model="selectedOption">
                    <button class="button is-primary" slot="trigger" slot-scope="{ activeMenu }">
                        <span>Timeline mode</span>
                        <b-icon :icon="activeMenu ? 'menu-up' : 'menu-down'"></b-icon>
                    </button>

                    <b-dropdown-item aria-role="listitem" value="default">Default</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" value="week">in Weeks</b-dropdown-item>
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

    export default {
        name: "StudentsTimeline",
        props: ['project'],
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
                selectedOption: null,

                currentItem: null,
            }
        },
        methods: {
            getData: function () {
                const loadingComponent = this.$buefy.loading.open({
                    container: this.$el
                });
                var that = this;
                $.get('/ajax/timeline/getdata', {project: this.project}, function (data) {
                    if (typeof data.groups !== "undefined") {
                        that.groups = data.groups;
                    }
                    if (typeof data.items !== "undefined") {
                        that.items = data.items;
                    }
                    if (typeof data.options !== "undefined") {
                        that.options = data.options;
                    }
                    that.dummeLoop = true;
                    that.MsgIsActive = that.items.length  === 0;
                    console.log(that.items, that.items.length  === 0, that.MsgIsActive);
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
                this.$refs.itemmodel.openModelItem(item);
                if(this.MsgIsActive) {
                    this.getData();
                }
            },
            groupDpClick: function (groupId) {
                var item = this.findObjectInArrayByProperty(this.groups, 'id', groupId);
                this.$refs.groupmodel.openModelItem(item);
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
            }
        },
        watch: {
            selectedOption: function (value, n) {
                if (value === 'week') {
                    this.options.timeAxis = {
                        scale: 'week',
                        step: 1
                    };
                    this.options.format = {
                        minorLabels: {week: 'w'}
                    };
                } else {
                    this.options.timeAxis = {
                    };
                    this.format.timeAxis = {
                    };
                }
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>

<style scoped>

</style>
