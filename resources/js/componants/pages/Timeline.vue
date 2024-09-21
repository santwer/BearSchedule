<template>
    <div class="row mb-2">
        <div class="col-3">
            <div class="btn-group">
                <button class="btn btn-outline-dark btn-sm" @click="addItem()">
                    <mdicon name="plus" size="20"/>
                    <span class="d-none d-lg-inline">{{ $t('Add Item') }}</span>
                </button>
                <button class="btn btn-outline-dark btn-sm" @click="addGroup()">
                    <mdicon name="folder-plus" size="20"/>
                    <span class="d-none d-lg-inline">{{ $t('Add Group') }}</span>
                </button>
            </div>
        </div>
        <div class="col-9" style="text-align: right">
            <BButtonGroup  size="sm">
                <BButton variant="outline-primary" :title="$t('project_share')">
                    <mdicon name="share-variant" size="20"/>
                    <span class="badge bg-primary rounded-pill">1</span>
                </BButton>
            </BButtonGroup>


            <BButtonGroup>
                <BDropdown v-model="selectedGroupShow"
                           auto-close="outside"
                           variant="outline-dark"
                           class="px-1"
                           size="sm">
                    <template #button-content>
                        <mdicon name="filter" size="20"/>
                        <span class="d-none d-lg-inline">{{ $t('project_groups') }}</span>
                    </template>
                    <div class="mx-3" v-for="(group, i) in groups">
                        <BFormCheckbox
                            v-on:change="setGroups"
                            v-model="groups[i].visible"
                            name="groups[]"
                            :value="1"
                            :unchecked-value="false"
                        >
                            {{ group.content }}
                        </BFormCheckbox>
                    </div>
                </BDropdown>
                <BDropdown v-model="selectedOptionShow" class="px-1" variant="outline-dark" size="sm">
                    <template #button-content>
                        <mdicon name="magnify-plus-outline" size="20"/>
                        <span class="d-none d-lg-inline">{{ $t('project_timelines.display') }}</span>
                    </template>
                    <BDropdownItem @click="selectedOption = null" :active="selectedOption === null">
                        {{ $t('project_display_options.default') }}
                    </BDropdownItem>
                    <BDropdownItem @click="selectedOption = 'weekday'" :active="selectedOption === 'weekday'">
                        {{ $t('project_display_options.weekday') }}
                    </BDropdownItem>
                    <BDropdownItem @click="selectedOption = 'day'" :active="selectedOption === 'day'">
                        {{ $t('project_display_options.day') }}
                    </BDropdownItem>
                    <BDropdownItem @click="selectedOption = 'week'" :active="selectedOption === 'week'">
                        {{ $t('project_display_options.week') }}
                    </BDropdownItem>
                    <BDropdownItem @click="selectedOption = 'month'" :active="selectedOption === 'month'">
                        {{ $t('project_display_options.month') }}
                    </BDropdownItem>
                    <BDropdownItem @click="selectedOption = 'year'" :active="selectedOption === 'year'">
                        {{ $t('project_display_options.year') }}
                    </BDropdownItem>
                </BDropdown>
                <BDropdown v-model="selectedZoomShow" class="px-1" variant="outline-dark" size="sm">
                    <template #button-content>
                        <mdicon name="magnify-plus-outline" size="20"/>
                        <span class="d-none d-lg-inline">{{ $t('project_zoom') }}</span>
                    </template>
                    <BDropdownItem @click="selectedZoom = 'day'">
                        {{ $t('Day') }}
                    </BDropdownItem>
                    <BDropdownItem @click="selectedZoom = 'week'">
                        {{ $t('Week') }}
                    </BDropdownItem>
                    <BDropdownItem @click="selectedZoom = 'month'">
                        {{ $t('Month') }}
                    </BDropdownItem>
                    <BDropdownItem @click="selectedZoom = 'year'">
                        {{ $t('Year') }}
                    </BDropdownItem>
                </BDropdown>

            </BButtonGroup>



            <BButtonGroup  size="sm"  class="mx-1">
                <BButton variant="primary" :title="$t('project_timeline')"><mdicon name="chart-gantt" size="20"/></BButton>
                <BButton variant="outline-dark" :title="$t('project_list')"><mdicon name="view-list" size="20"/></BButton>
            </BButtonGroup>

            <BButtonGroup  size="sm" class="ml-1">
                <BButton variant="outline-dark" :title="$t('project_settings')">
                    <mdicon name="cog" size="20"/>
                </BButton>
            </BButtonGroup>
        </div>
    </div>
    <loading v-if="loading"></loading>
    <div ref="visualization" v-if="renderComponent"></div>
    <ItemModal
        ref="itemmodal"
        :groups="groups"
        v-on:save="saveItem"
    ></ItemModal>
    <GroupModal
        ref="groupmodal"
        :groups="groups"
        v-on:save="saveGroup"
    ></GroupModal>
</template>

<script>
//import vis from "vis-network";
import {Timeline, DataSet} from "vis-timeline/standalone/index";
import Api from "@/Api";
import Handlebars from "handlebars";
import ItemModal from "@/componants/parts/ItemModal.vue";
import Loading from "@/componants/parts/Loading.vue";
import GroupModal from "@/componants/parts/GroupModal.vue";
import moment from 'moment';
import {BModal, BButton, BButtonGroup, BDropdown, BDropdownItem, BFormCheckbox} from "bootstrap-vue-next";

export default {
    components: {
        GroupModal,
        Loading,
        ItemModal,
        BModal,
        BButton,
        BButtonGroup,
        BFormCheckbox,
        BDropdown,
        BDropdownItem
    },
    data() {
        return {
            timeline: null,
            items: [],
            groups: [],
            options: {},
            loading: false,
            selectedItems: null,
            renderComponent: true,
            selectedZoom: null,
            selectedOption: null,
            selectedGroup: [],
            selectedZoomShow: false,
            selectedOptionShow: false,
            selectedGroupShow: false,
        }
    },
    methods: {
        setGroups() {
            console.log('test',this.groups)
          this.timeline.setGroups(this.groups);
            //todo set visible on timeline
        },
        saveGroup(data) {
            if (this.groups.find(x => x.id === data.id)) {
                this.groups = this.groups.map(x => x.id === data.id ? data : x);
            }
            else {
                this.groups.push(data);
            }
            this.timeline.setGroups(this.groups);
        },
        saveItem(data) {
            //if item in items update else add
            if (this.items.find(x => x.id === data.id)) {
                this.items = this.items.map(x => x.id === data.id ? this.removeNullValues(data) : x);
            }
            else {
                this.items.push(this.removeNullValues(data));
            }
            try {
                this.timeline.setItems(this.items);
            } catch (e) {
                console.log(e);
            }
        },
        removeNullValues(item) {
            for (let key in item) {
                if (item[key] === null) {
                    delete item[key];
                }
            }
            return item;
        },
        getData() {
            this.loading = true;
            Api.getTimeline(this.project_id).then(response => {

                this.items = response.data.items.map(x => this.removeNullValues(x));
                this.groups = response.data.groups.map(x => this.removeNullValues(x));
                this.setOptions(response.data.options);
                this.initTimeline();
            });
        },
        setOptions(options) {
            if (typeof options.template !== "undefined") {
                options.template = Handlebars.compile(options.template);
            }
            if (typeof options.groupTemplate !== "undefined") {
                options.groupTemplate = Handlebars.compile(options.groupTemplate);
            }
            if (typeof options.displayscale !== "undefined") {
                var dates = this.setZoomRange(options.displayscale);
                if (typeof dates[1] !== "undefined") {
                    options.start = dates[0];
                    options.end = dates[1];
                }
                delete options.displayscale;
            }
            this.options = options;
            this.options['snap'] = function (date, scale, step) {
                return new Date(date.toDateString());
            };
            this.options['groupOrder'] = function (a, b) {
                return a.order - b.order;
            };
            this.options['order'] = function (a, b) {
                return a.start > b.start;
            };
            this.options['moment'] = function (date) {
                return moment(date).utc();
            }


            this.options.onMove = this.onItemMove;
            this.options.locale = this.$i18n.locale;
        },
        initTimeline() {
            if (this.timeline) {
                this.timeline.destroy();
            }
            // Create a DataSet (allows two way data-binding)

            this.timeline = new Timeline(
                this.$refs.visualization,
                this.items,
                this.options
            );
            this.timeline.setGroups(this.groups);
            //this.timeline.setItems(this.items);

            this.timeline.on('select', function (properties) {
                this.selectedItems = properties.items ?? null;
            });
            this.timeline.on('doubleClick', (properties) => {
                if (properties.item && properties.what === 'item') {
                    let item = this.items.find(x => x.id === properties.item);
                    this.$refs.itemmodal.openItem(item);
                }
                else if (properties.group && properties.what === 'group-label') {
                    let group = this.groups.find(x => x.id === properties.group);
                    this.$refs.groupmodal.openGroup(group);
                }
                console.log(properties)
            });
            this.loading = false;
        },


        addItem() {
            this.$refs.itemmodal.showModal();
        },
        addGroup() {
            this.$refs.groupmodal.showModal();
        },
        onItemMove: function (item, callback) {
            let orgItem = this.items.find(x => x.id === item.id);
            orgItem.start = item.start;
            orgItem.end = item.end;
            orgItem.group = item.group;
            Api.setItem(orgItem);

            callback(item);
        },
        setZoomRange: function (value) {
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
        getCurrentWeek: function (curr) {
            let week = [];

            for (let i = 1; i <= 7; i++) {
                let first = curr.getDate() - curr.getDay() + i;
                if (i === 1 || i === 7) {
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
            var firstday = new Date(year, month, day, 0, 0);
            var lastday = new Date(year, month, day, 23, 59);
            return [firstday, lastday];
        },
        rangeChange: function (e) {
            (new TimelineItemsCollector).get();
            this.selectedZoom = null;
        },

        registerHandlebarHelper: function () {
            if (!this.handlebarHelperRegistered) {
                Handlebars.registerHelper('ifEquals', function (arg1, arg2, options) {
                    return (arg1 == arg2) ? options.fn(this) : options.inverse(this);
                });
                this.handlebarHelperRegistered = true;
            }
        },
        setTimeAxisOption: function (value) {
            if (value !== null) {
                this.options.timeAxis = {
                    scale: value,
                    step: 1
                }
            }
            else {
                this.options.timeAxis = {
                    step: 1
                };
            }
        },
    },
    watch: {
        selectedZoom(value) {
            var dates = this.setZoomRange(value);
            console.log('dates', dates)
            this.timeline.setWindow(dates[0], dates[1]);
        },
        selectedOption: function (value, n) {

            this.setTimeAxisOption(value);
            this.timeline.setOptions(this.options);
        },
    },

    mounted() {
        this.project_id = this.$route.params.id;
        this.registerHandlebarHelper();
        this.getData();
    }
    ,
    created() {
        this.$watch(
            () => this.$route.params.id,
            () => {
                this.project_id = this.$route.params.id;
                this.getData();
            }
        )
    }

}
</script>

<style>
@import "../../../scss/_timeline.scss";
</style>
