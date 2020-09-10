<template>
    <div>
        <div v-if="dummeLoop">
            <div class="buttons">
                <timeline-item-model ref="itemmodel" :project="project"></timeline-item-model>
                <timeline-group-model ref="groupmodel" :project="project"></timeline-group-model>
            </div>
            <timeline ref="timeline"
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
                options: {
                    editable: false,
                },

                currentItem: null,
            }
        },
        methods: {
            getData: function () {
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
                }, 'json')
            },
            dpClick: function (e) {
                if(this.currentItem !== null) {
                    this.itemDpClick(this.currentItem);
                } if(e.group !== null && e.item === null && e.x < 0) {
                    this.groupDpClick(e.group);
                }
                else {
                    console.log('dp', e);
                }
            },
            itemDpClick: function (itemId) {
                var item = this.findObjectInArrayByProperty(this.items, 'id', itemId);
                this.$refs.itemmodel.openModelItem(item);
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
        mounted() {
            this.getData();
        }
    }
</script>

<style scoped>

</style>
