<template>
    <div>
        <div v-if="dummeLoop">
            <div class="buttons">
                <timeline-item-model ref="itemmodel"></timeline-item-model>

                <b-button size="is-small" icon-left="plus-thick">Add Group</b-button>
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

    export default {
        name: "StudentsTimeline",

        components: {
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
                    editable: true,
                },

                currentItem: null,
            }
        },
        methods: {
            getData: function () {
                var that = this;
                $.get('/ajax/timeline/getdata', function (data) {
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
                    console.log(that.dummeLoop);
                }, 'json')
            },
            dpClick: function () {
                if(this.currentItem !== null) {
                    this.itemDpClick(this.currentItem);
                } else {
                    console.log('dp');
                }
            },
            itemDpClick: function (itemId) {
                console.log('dp on ' + itemId);
                var item = this.findObjectInArrayByProperty(this.items, 'id', itemId);
                this.$refs.itemmodel.openModelItem(item);
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
