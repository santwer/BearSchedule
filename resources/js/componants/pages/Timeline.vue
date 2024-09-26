<template>
    <div class="row mb-2">
        <div class="col-3">
            <BButtonGroup size="sm">

                <BButton :variant=" isDark ? 'secondary' : 'outline-primary'" :title="$t('Add Item')"
                         @click="addItem()">
                    <mdicon name="plus" size="20"/>
                    <span class="d-none d-lg-inline">{{ $t('Add Item') }}</span>
                </BButton>
                <BButton :variant=" isDark ? 'secondary' : 'outline-primary'" :title="$t('Add Group')"
                         @click="addGroup()">
                    <mdicon name="folder-plus" size="20"/>
                    <span class="d-none d-lg-inline">{{ $t('Add Group') }}</span>
                </BButton>
            </BButtonGroup>
        </div>
        <div class="col-9" style="text-align: right">
            <BButtonGroup size="sm" class="px-1">
                <BButton :variant=" isDark ? 'secondary' : 'outline-dark'" :title="$t('project_excel')"
                         :disabled="true">
                    <mdicon name="file-excel-outline" size="20"/>
                </BButton>
            </BButtonGroup>
            <BButtonGroup size="sm">
                <BButton
                    :variant=" isDark ? 'secondary' : 'outline-primary'"
                    @click="$refs.share.show()"
                    :title="$t('project_share')"
                >
                    <mdicon name="share-variant" size="20"/>
                    <span class="badge bg-primary rounded-pill" v-if="shared_count > 1">{{ shared_count }}</span>
                </BButton>
            </BButtonGroup>


            <BButtonGroup>
                <BDropdown v-model="selectedGroupShow"
                           auto-close="outside"
                           :variant="isDark ? 'secondary' : 'outline-dark'"
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
                <BDropdown v-model="selectedOptionShow" class="px-1" :variant="isDark ? 'secondary' : 'outline-dark'"
                           size="sm"
                           :title="$t('project_timelines.display')"
                           v-show="tab === 'timeline'">
                    <template #button-content>
                        <mdicon name="label-multiple-outline" size="20"/>
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
                <BDropdown v-model="selectedZoomShow" class="px-1" :variant="isDark ? 'secondary' : 'outline-dark'"
                           size="sm"
                           :title="$t('project_zoom')"
                           v-show="tab === 'timeline'">
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

            <BButtonGroup size="sm" class="mx-1">
                <BButton :variant="tab === 'timeline' ? 'primary' : (isDark ? 'secondary' : 'outline-dark')"
                         :title="$t('project_timeline')"
                         @click="tab='timeline'">
                    <mdicon name="chart-gantt" size="20"/>
                </BButton>
                <BButton :variant="tab === 'list' ? 'primary' : (isDark ? 'secondary' : 'outline-dark')"
                         :title="$t('project_list')" @click="tab='list'">
                    <mdicon name="view-list" size="20"/>
                </BButton>
            </BButtonGroup>
            <BButtonGroup size="sm" class="mx-1">

                <BDropdown v-model="timelineOptionShow" class="px-1" :variant="isDark ? 'secondary' : 'outline-dark'"
                           size="sm"
                           auto-close="outside"
                           :title="$t('project_settings')"
                           >
                    <template #button-content>
                        <mdicon name="cog" size="20"/>
                    </template>
                    <div class="form-group pb-2 mx-2 input-group-sm">
                        <label>{{ $t('project_item_design') }}</label>
                        <select class="form-select "  v-model="template" v-on:change="updateSetting('template')">
                            <option v-for="temp in templates" :value="temp">{{ $t(temp.text) }}</option>
                        </select>
                    </div>
                    <div class="form-group mx-2 input-group-sm">
                        <label>{{ $t('project_initial_zoom') }}</label>
                        <select class="form-select" v-model="displayscale" v-on:change="updateSetting('displayscale')">
                            <option :value="null">{{ $t('project_zoom_timeline.auto') }}</option>
                            <option value="year">{{ $t('project_zoom_timeline.year') }}</option>
                            <option value="month">{{ $t('project_zoom_timeline.month') }}</option>
                            <option value="week">{{ $t('project_zoom_timeline.week') }}</option>
                            <option value="day">{{ $t('project_zoom_timeline.day') }}</option>
                        </select>
                    </div>
                    <div class="form-group py-2 mx-2 input-group-sm">
                        <label>{{ $t('project_axis_orientation') }}</label>
                        <select class="form-select" v-model="this.options.orientation.axis" v-on:change="updateSetting('orientation.axis')">
                            <option :value="undefined">{{ $t('project_axis_ori.bottom') }}</option>
                            <option value="top">{{ $t('project_axis_ori.top') }}</option>
                            <option value="both">{{ $t('project_axis_ori.both') }}</option>
                            <option value="none">{{ $t('project_axis_ori.none') }}</option>
                        </select>
                    </div>
                    <div class="form-group mx-2 input-group-sm">
                        <label>{{ $t('project_item_orientation') }}</label>
                        <select class="form-select" v-model="this.options.orientation.item" v-on:change="updateSetting('orientation.item')">
                            <option :value="undefined">{{ $t('project_axis_ori.bottom') }}</option>
                            <option value="top">{{ $t('project_axis_ori.top') }}</option>
                        </select>
                    </div>
                    <hr>
                    <BButtonGroup class="px-2" style="width: 100%">
                        <BButton :variant="isDark ? 'secondary' : 'outline-dark'" :title="$t('rename')"><mdicon name="pencil" size="16"/></BButton>
                        <BButton  variant="info" v-if="true" :title="$t('put to archive')"><mdicon name="archive-arrow-down" size="16"/></BButton>
                        <BButton :title="$t('get to archive')" v-else><mdicon name="archive-arrow-up" size="16"/></BButton>
                        <BButton variant="danger" :title="$t('general.delete')"><mdicon name="delete" size="16"/></BButton>
                    </BButtonGroup>
                </BDropdown>
            </BButtonGroup>

        </div>
    </div>
    <loading v-if="loading"></loading>
    <div ref="visualization" v-if="renderComponent" v-show="tab === 'timeline'" class="shadow"></div>
    <div class="" v-if="!loading" v-show="tab === 'list'">
        <div class="alert alert-info" v-if="items.length === 0 && groups.length === 0">
            <h4 class="alert-heading">{{ $t('project_timelines.item.no_group_created_yet') }}</h4>
        </div>
        <!-- Header Group Name -->
        <div class="card mb-1" v-if="items.filter(x => x.group === null || x.group === undefined).length > 0">
            <div class="card-body">
                <h5 class="card-title">No Group</h5>
                <BTable :sort-by="[{key: 'start', order: 'asc'}]"
                        :items="items.filter(x => x.group === null || x.group === undefined)"
                        :fields="sortFields"
                >
                    <template #cell(edit)="row">
                        <BButtonGroup size="sm">
                            <BButton
                                :variant=" isDark ? 'secondary' : 'outline-primary'"
                                :title="$t('Edit')"
                                @click="editItem(row.item.id)">
                                <mdicon name="pencil" size="20"/>
                            </BButton>
                        </BButtonGroup>
                    </template>
                    <template #cell(style)="row">
                        <div class="stylebox" :style="row.item.style"></div>
                    </template>
                    <template #cell(type)="row">
                        {{ $t('project_timelines.item.types.' + row.item.type) }}
                    </template>
                    <template #cell(start)="row">
                        {{ formatDateToUserDate(row.item.start) }}
                    </template>
                    <template #cell(end)="row">
                        {{ formatDateToUserDate(row.item.end) }}
                    </template>
                </BTable>
            </div>
        </div>
        <div class="card mb-1" v-for="group in groups">
            <div class="card-body">
                <h5 class="card-title">{{ group.content }}</h5>
                <BAlert variant="info" :model-value="true" v-if="items.filter(x => x.group === group.id).length === 0">
                    Keine Items in der Gruppe vorhanden.
                </BAlert>
                <BTable :sort-by="[{key: 'start', order: 'asc'}]"
                        :items="items.filter(x => x.group === group.id)"
                        :fields="sortFields"
                        v-else
                >
                    <template #cell(edit)="row">
                        <BButtonGroup size="sm">
                            <BButton
                                :variant=" isDark ? 'secondary' : 'outline-primary'"
                                :title="$t('Edit')"
                                @click="editItem(row.item.id)">
                                <mdicon name="pencil" size="20"/>
                            </BButton>
                        </BButtonGroup>
                    </template>
                    <template #cell(style)="row">
                        <div class="stylebox" :style="row.item.style"></div>
                    </template>
                    <template #cell(type)="row">
                        {{ $t('project_timelines.item.types.' + row.item.type) }}
                    </template>
                    <template #cell(start)="row">
                        {{ formatDateToUserDate(row.item.start) }}
                    </template>
                    <template #cell(end)="row">
                        {{ formatDateToUserDate(row.item.end) }}
                    </template>
                </BTable>
            </div>
        </div>

    </div>
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
    <ShareModal ref="share"></ShareModal>
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
import 'moment/locale/de';
import {
    BModal,
    BButton,
    BAlert,
    BButtonGroup,
    BDropdown,
    BFormInput,
    BDropdownItem,
    BFormCheckbox,
    BTable
} from "bootstrap-vue-next";
import ThemeMixin from "@/mixins/ThemeMixin";
import ShareModal from "@/componants/parts/ShareModal.vue";
import routeMixin from "@/mixins/RouteMixin";

export default {
    mixins: [ThemeMixin, routeMixin],
    components: {
        ShareModal,
        GroupModal,
        Loading,
        ItemModal,
        BModal,
        BButton,
        BButtonGroup,
        BFormCheckbox,
        BDropdown,
        BFormInput,
        BAlert,
        BTable,
        BDropdownItem
    },
    data() {
        return {
            sortFields: [
                {key: 'style', label: '', sortable: false},
                {key: 'title', label: this.$t('project_timeline_tables.columns.title'), sortable: true},
                {key: 'subtitle', label: this.$t('project_timeline_tables.columns.subtitle'), sortable: true},
                {key: 'type', label: this.$t('project_timeline_tables.columns.type'), sortable: true},
                {key: 'start', label: this.$t('project_timeline_tables.columns.start'), sortable: true},
                {key: 'end', label: this.$t('project_timeline_tables.columns.end'), sortable: true},
                {key: 'edit', label: '', sortable: false}
            ],
            timeline: null,
            items: [],
            groups: [],
            options: {
                orientation: {axis: undefined, item: undefined}
            },
            loading: false,
            selectedItems: null,
            renderComponent: true,
            selectedZoom: null,
            selectedZoomValue: null,
            selectedOption: null,
            selectedGroup: [],
            selectedZoomShow: false,
            timelineOptionShow: false,
            selectedOptionShow: false,
            selectedGroupShow: false,
            shared_count: 0,
            tab: 'timeline',
            template: null,
            displayscale: null,
            templates: [
            ],
        }
    },
    methods: {
        updateSetting(name) {
            let value = null;
            if(name === 'displayscale') {
                value = this.displayscale;
            }
            else if(name === 'template') {
                value = this.template.value;
                this.options.template = Handlebars.compile(this.template.template);
            }
            else if(name.includes('.')) {
                value = this.options[name.split('.')[0]][name.split('.')[1]];
            } else {
                value = this.options[name];
            }

            this.timeline.setOptions(this.options);



            Api.setProjectSetting(this.$route.params.id, name, value)
                .then(response => {
                    console.log(response.data)
                })
                .catch(error => {
                    console.log(error)
                });
        },
        editItem(id) {
            this.$refs.itemmodal.openItem(this.items.find(x => x.id === id));
        },
        formatDateToUserDate(date) {

            return moment(date).format(this.$t('date_format_js'));
        },
        setGroups() {
            console.log('test', this.groups)
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
                this.shared_count = response.data.shared_count;
                this.templates = response.data.item_templates;
                this.setOptions(response.data.options);
                this.initTimeline();
            });
        },
        setOptions(options) {
            if (typeof options.template !== "undefined") {
                options.template = Handlebars.compile(options.template);
                options.xss = {
                    disabled: true
                }
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
                this.displayscale = options.displayscale;
                delete options.displayscale;
            } else {
                this.displayscale = null;
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

            this.options['minHeight'] = this.getMinHeight(this.options['minHeight']);
            this.options.onMove = this.onItemMove;
            this.options.locale = this.$i18n.locale;
        },
        getMinHeight(minHeight) {
            let calculatedMin = window.innerHeight - 85;
            minHeight = parseInt(minHeight.split('px')[0])
            if (minHeight > calculatedMin) {
                return minHeight
            }
            return calculatedMin;
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
                else if (properties.group && properties.what === 'background' && properties.event.target.classList.contains('vis-item-overflow')) {
                    //has class vis-item-overflow
                    console.log(properties.event.target)
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

.stylebox {
    width: 27px;
    height: 27px;
    display: inline-block;
    margin-right: 5px;
    border: 1px solid;
    border-color: #004ba0;
    background-color: rgba(99, 164, 255, 0.4);

}
</style>
