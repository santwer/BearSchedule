<template>
    <form action="">
        <div class="modal-card" style="width: 900px;">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ trans.get('project.item') }}</p>
                <button
                    type="button"
                    class="delete"
                    @click="$emit('close')"/>
            </header>
            <section class="modal-card-body">
                <b-tabs>
                    <b-tab-item :label="trans.get('project.timelines.item.data')">
                <div class="columns">
                    <div class="column">
                        <b-field label="Group">
                            <b-select :placeholder="trans.get('project.timelines.item.select_group')" v-model="item.group" required expanded>
                                <option v-if="item.type === 'background'" :value="null">{{ trans.get('project.timelines.item.no_group') }}</option>
                                <option
                                    v-for="group in groups"
                                    :value="group.id"
                                    :key="group.id">
                                    {{ group.content }}
                                </option>
                            </b-select>
                        </b-field>
                        <b-field :label="trans.get('project.timeline_tables.columns.title')">
                            <b-input
                                type="text"
                                v-model="item.title"
                                :placeholder="trans.get('project.timeline_tables.columns.title')">
                            </b-input>
                        </b-field>
                        <b-field :label="trans.get('project.timeline_tables.columns.subtitle')">
                            <b-input
                                type="text"
                                v-model="item.subtitle"
                                :placeholder="trans.get('project.timeline_tables.columns.subtitle')">
                            </b-input>
                        </b-field>

                        <b-field :label="trans.get('project.timeline_tables.columns.content')">
                            <b-input type="textarea" v-model="item.description"></b-input>
                        </b-field>
                        <div class="buttons">
                            <b-button size="is-small" icon-left="plus-thick" @click="addLink">{{ trans.get('project.timelines.item.add_link')}}</b-button>
                            <div v-for="link in item.links" style="width: 100%;">
                                <link-button editmode="true" :item="link"></link-button>
                            </div>
                        </div>
                    </div>
                    <div class="is-350">
                        <b-field :label="trans.get('project.timeline_tables.columns.start')">
                            <b-datepicker
                                :placeholder="trans.get('project.timelines.item.click_to_select')"
                                v-model="item.start"
                                :show-week-number="true"
                                :first-day-of-week="1"
                                icon="calendar-today"
                                :locale="trans.get('general.bcp47')"

                                trap-focus>
                            </b-datepicker>
                        </b-field>
                        <b-field :label="trans.get('project.timeline_tables.columns.end')" v-if="item.type === 'range' || item.type === 'background'">
                            <b-datepicker
                                :placeholder="trans.get('project.timelines.item.click_to_select')"
                                :locale="trans.get('general.bcp47')"
                                v-model="item.end"
                                :first-day-of-week="1"
                                :show-week-number="true"
                                icon="calendar-today"
                                editable
                                trap-focus>
                            </b-datepicker>
                        </b-field>
                        <b-field :label="trans.get('project.timeline_tables.columns.type')">
                            <b-select :placeholder="trans.get('project.timelines.item.select_type')" v-model="item.type" required expanded>
                                <option
                                    v-for="type in types"
                                    :value="type.id"
                                    :key="type.id">
                                    {{ trans.get('project.timelines.item.types.' + type.id) }}
                                </option>
                            </b-select>
                        </b-field>
                        <b-field :label="trans.get('project.timelines.item.status')">
                            <b-select :placeholder="trans.get('project.timelines.item.select_status')" v-model="item.status" expanded>
                                <option
                                    v-for="status in stati"
                                    :value="status.id"
                                    :key="status.id">
                                    {{ trans.get('project.timelines.item.stati.' + status.id) }}
                                </option>
                            </b-select>
                        </b-field>
                        <issue-search ref="issues" v-if="item.jira" :selected="item.jira"></issue-search>
                        <b-field :label="trans.get('project.timelines.item.color')">
                        <b-dropdown aria-role="list" expanded>
                            <button class="button is-fullwidth" slot="trigger" slot-scope="{ colorPickerActive }" type="button">
                                <span class="previewColor" :style="getCurrentColorStyle()"></span><span>{{ getCurrentColorName() }}</span>
                                <b-icon :icon="colorPickerActive ? 'menu-up' : 'menu-down'"></b-icon>
                            </button>
                            <b-dropdown-item v-for="color in colors" :key="color.id" aria-role="listitem" @click="setColor(color)">
                                <span class="previewColor" :style="color.style"></span> {{ trans.get('project.timelines.item.colors.' + color.id) }}
                            </b-dropdown-item>
                        </b-dropdown>
                        </b-field>

                    </div>

                </div>
                    </b-tab-item>
                    <b-tab-item :label="trans.get('project.timelines.item.tags')" v-if="false">
                        <b-field :label="trans.get('project.timelines.item.enter_tags')">
                            <b-taginput
                                v-model="item.tags"
                                :data="filteredTags"
                                autocomplete
                                allow-new
                                open-on-focus
                                field="user.first_name"
                                icon="label"
                                :placeholder="trans.get('project.timelines.item.add_tags')"
                                @typing="getFilteredTags">
                            </b-taginput>
                        </b-field>
                    </b-tab-item>
                    <b-tab-item :label="trans.get('project.timelines.item.series')"  v-if="false">
                        <timeline-item-series :item="item"></timeline-item-series>
                    </b-tab-item>
                </b-tabs>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$emit('close')">{{ trans.get('general.close') }}</button>
                <button class="button is-danger" type="button" v-if="item.id != null" @click="deleteEntry">
                    {{ trans.get('general.delete') }}
                </button>


                <button class="button is-primary delButton" type="button" @click="saveForm">{{ trans.get('general.save') }}</button>
            </footer>
        </div>
    </form>
</template>

<script>
    import LinkButton from "../tools/LinkButton";
    import TimelineItemSeries from "./TimelineItemSeries";
    import IssueSearch from "../Jira/IssueSearch";

    export default {
        name: "TimelineItemModelForm",
        components: {IssueSearch, TimelineItemSeries, LinkButton},
        props: ['setItem'],
        data() {
            return {
                colorPickerActive: false,
                item: {
                    id: null,
                    title: '',
                    content: '',
                    start: null,
                    end: null,
                    group: null,
                    subtitle: null,
                    links: [],
                    tags: [],
                    color: {},
                },
                filteredTags: [],
                backup: {},
                csrf: null,
                groups: [],
                types: [
                    {id: 'box', text: 'box'},
                    {id: 'point', text: 'point'},
                    {id: 'range', text: 'range'},
                    {id: 'background', text: 'background'},
                ],
                stati: [
                    {id: 'DEFAULT', text: 'Normal'},
                    {id: 'DELAYED', text: 'Delayed'},
                    {id: 'CRITICAL', text: 'Critical'},
                    {id: 'TEST', text: 'Test'},
                    {id: 'DONE', text: 'DONE'},
                ],
                colors: [
                    { id: 'default', name: 'Default', style: { backgroundColor: 'rgba(99, 164, 255, 0.4)' } },
                    { id: '#d32f2f', name: 'Red', style: { backgroundColor:"#d32f2f" } },
                    { id: '#c2185b', name: 'Pink', style: { backgroundColor:"#c2185b" } },
                    { id: '#7b1fa2', name: 'Purple', style: { backgroundColor:"#7b1fa2" } },
                    { id: '#512da8', name: 'Deep Purple', style: { backgroundColor:"#512da8" } },
                    { id: '#303f9f', name: 'Indigo', style: { backgroundColor:"#303f9f" } },
                    { id: '#1976d2', name: 'Blue', style: { backgroundColor:"#1976d2" } },
                    { id: '#0288d1', name: 'Light Blue', style: { backgroundColor:"#0288d1" } },
                    { id: '#0097a7', name: 'Cyan', style: { backgroundColor:"#0097a7" } },
                    { id: '#00796b', name: 'Teal', style: { backgroundColor:"#00796b" } },
                    { id: '#388e3c', name: 'Green', style: { backgroundColor:"#388e3c" } },
                    { id: '#689f38', name: 'Light Green', style: { backgroundColor:"#689f38" } },
                    { id: '#afb42b', name: 'Lime', style: { backgroundColor:"#afb42b" } },
                    { id: '#fbc02d', name: 'Yellow', style: { backgroundColor:"#fbc02d" } },
                    { id: '#ffa000', name: 'Amber', style: { backgroundColor:"#ffa000" } },
                    { id: '#f57c00', name: 'Orange', style: { backgroundColor:"#f57c00" } },
                    { id: '#e64a19', name: 'Deep Orange', style: { backgroundColor:"#e64a19" } },
                    { id: '#9e9e9e', name: 'Grey', style: { backgroundColor:"#9e9e9e" } },
                ]
            }
        },
        methods: {
            getItem() {
                return this.item;
            },
            setColor(colorItem) {
                this.item.color = colorItem;
            },
            getCurrentColorName() {
                if(typeof this.item.color === "undefined") {
                    return Vue.prototype.trans.get('project.timelines.item.colors.' + this.getCurrentColorByStyle().id);
                }
                if(typeof this.item.color.name === "undefined") {
                    return Vue.prototype.trans.get('project.timelines.item.colors.default');
                }
                return  Vue.prototype.trans.get('project.timelines.item.colors.' + this.item.color.id);
            },
            getCurrentColorStyle() {
                if(typeof this.item.color === "undefined") {
                    return this.getCurrentColorByStyle().style;
                }
                if(typeof this.item.color.style === "undefined") {
                    return this.colors[0].style;
                }
                return this.item.color.style;
            },
            getCurrentColorByStyle() {
                if(typeof this.item.style === "undefined") {
                    return this.colors[0];
                }
                var str = this.computeCss(this.item.style);
                if(typeof str['background-color'] != "undefined") {
                    var bgColor = str['background-color'].substring(0, 7);
                    var result = this.colors.filter(obj => {
                        return obj.id === bgColor
                    });
                    if(result.length > 0) {
                        return result[0];
                    }
                }

                return this.colors[0].style;
            },
            computeCss: function (cssStr) {
                var cssEntries = [];
                var cssSplit = cssStr.split(';');
                cssSplit.forEach(function (element) {
                   var parts = element.split(':');
                    if(typeof parts[1] !== "undefined") {
                        cssEntries[parts[0]] = parts[1].trim();
                    }
                });
                return cssEntries;
            },
            getFilteredTags(text) {
                /*this.filteredTags = data.filter((option) => {
                    return option.user.first_name
                        .toString()
                        .toLowerCase()
                        .indexOf(text.toLowerCase()) >= 0
                })*/
              return [];
            },
            newIssueSet(issue) {
                if(!this.item.title && !this.item.subtitle  && !this.item.content ) {
                    this.item.title = issue.fields.summary;
                    this.item.description = issue.fields.description;
                    if(issue.fields.assignee) {
                        this.item.subtitle = issue.fields.assignee.displayName;
                    }
                }
            },
            deleteIssue: function (issue) {
                this.item.jira = this.item.jira.filter(x => x.key !== issue.key)
            },
            saveForm: function () {
                //console.log(this.item.jira)
                //return;
                this.saveAction();

            },
            saveAction: function () {
                if (this.csrf === null) {
                    console.error('CSRF Meta not set. Update not possible');
                    this.$buefy.toast.open({
                        duration: 5000,
                        message: Vue.prototype.trans.get('project.timelines.messages.save_fail'),
                        type: 'is-danger'
                    });
                    this.$emit('close');
                }
                var that = this;
                var sendItem = Object.assign({}, that.item);
                if(sendItem.jira) {
                    sendItem.jira = sendItem.jira.map(function (item) {
                        return item.key;
                    });
                }

                $.ajax({
                    method: "PUT",
                    url: "/ajax/timeline/item",
                    data: sendItem,
                    dataType: 'json'
                }).done(function (data) {
                    var msg = Vue.prototype.trans.get('project.timelines.messages.save_success');
                    that.$buefy.toast.open(msg);
                    if(!window.UseWebSocketKouky) {
                        that.backup.start = that.item.start;
                        that.backup.end = that.item.end;
                        that.backup.content = that.item.title;
                        that.backup.title = that.item.title;
                        that.backup.description = that.item.description;
                        that.backup.group = that.item.group;
                        that.backup.type = that.item.type;
                        that.backup.links = that.item.links;
                        that.backup.subtitle = that.item.subtitle;
                        that.backup.status = that.item.status;

                        that.backup.process = data.data.process;
                        that.backup.processExtra = data.data.processExtra;
                        that.backup.processlabel = data.data.processlabel;
                    }
                    that.$emit('close');
                    if(!window.UseWebSocketKouky) {
                        if (typeof that.item.id === "undefined" || that.item.id === null) {
                            that.backup.id = data.data.id;
                            that.$parent.$parent.$parent.newItemInTimeLine(that.backup);
                        }
                        that.backup.links = data.data.links;
                        that.backup.style = data.data.style;
                    }
                }).fail(function (data) {
                    var msg = (typeof data.message !== "undefined") ? data.message : Vue.prototype.trans.get('project.timelines.messages.save_fail');
                    that.$buefy.toast.open({
                        duration: 5000,
                        message: msg,
                        type: 'is-danger'
                    });
                })

            },
            deleteEntry: function () {
                var that = this;
                this.$buefy.dialog.confirm({
                    message: Vue.prototype.trans.get('project.timelines.messages.confirm_delete_item'),
                    onConfirm: function () {
                        $.ajax({
                            method: "DELETE",
                            url: "/ajax/timeline/item",
                            data: that.item,
                            dataType: 'json'
                        }).done(function (data) {
                            var msg = Vue.prototype.trans.get('project.timelines.messages.delete_success');
                            that.$buefy.toast.open(msg);
                            if(!window.UseWebSocketKouky) {
                                that.$parent.$parent.$parent.deleteItemInTimeLine(that.backup.id);
                            }
                            that.$emit('close');
                        }).fail(function (data) {
                            var msg = (typeof data.responseJSON.message !== "undefined") ? data.responseJSON.message : Vue.prototype.trans.get('project.timelines.messages.delete_fail');
                            that.$buefy.toast.open({
                                duration: 5000,
                                message: msg,
                                type: 'is-danger'
                            });
                        })
                    }
                });

            },
            addLink: function () {
                if (typeof this.item.links === "undefined") {
                    this.item.links = [];
                }
                this.item.links.push({href: '', title: ''});
            }
        },
        mounted() {
            this.backup = this.setItem;
            if (typeof this.setItem !== "undefined") {
                this.backup.start = this.setItem.start === '' ? '' : new Date(Date.parse(this.setItem.start));
            } else {
                this.backup.start = null;
            }
            if (typeof this.setItem !== "undefined") {
                this.backup.end = this.setItem.end === '' ? '' : new Date(Date.parse(this.setItem.end));
            } else {
                this.backup.end = null;
            }
            this.item = Object.assign({}, this.backup);
            if (typeof this.item.type === "undefined") {
                this.item.type = 'box';
            } else if (this.item.type === null || this.item.type === '') {
                this.item.type = 'box';
            }
            if (typeof this.item.links === "undefined") {
                this.item.links = [];
            }
            if (typeof this.item.series  === "undefined") {
                this.item.series  = {
                };
            }
            this.csrf = $('meta[name=csrf-token]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': this.csrf
                }
            });

            this.groups = this.$parent.$parent.$parent.groups;

        }
    }
</script>

<style scoped>
    .delButton {
        display: block;
        position: absolute;
        right: 20px;
    }
    .previewColor {
        height: 15px;
        width: 15px;
        border: 1px solid #000;
        margin-right: 10px;
        display: inline-block;
    }
</style>
