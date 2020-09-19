<template>
    <form action="">
        <div class="modal-card" style="width: 900px;">
            <header class="modal-card-head">
                <p class="modal-card-title">Item</p>
                <button
                    type="button"
                    class="delete"
                    @click="$emit('close')"/>
            </header>
            <section class="modal-card-body">
                <b-tabs>
                    <b-tab-item label="Data">
                <div class="columns">
                    <div class="column">
                        <b-field label="Title">
                            <b-input
                                type="text"
                                v-model="item.title"
                                placeholder="Title">
                            </b-input>
                        </b-field>
                        <b-field label="Subtitle">
                            <b-input
                                type="text"
                                v-model="item.subtitle"
                                placeholder="Subtitle">
                            </b-input>
                        </b-field>

                        <b-field label="Content">
                            <b-input maxlength="200" type="textarea" v-model="item.description"></b-input>
                        </b-field>
                        <div class="buttons">
                            <b-button size="is-small" icon-left="plus-thick" @click="addLink">Add Link</b-button>
                            <div v-for="link in item.links" style="width: 100%;">
                                <link-button editmode="true" :item="link"></link-button>
                            </div>
                        </div>
                    </div>
                    <div class="is-350">
                        <h2 class="subtitle">Options</h2>
                        <b-field label="Type">
                            <b-select placeholder="Select a type" v-model="item.type" required expanded>
                                <option
                                    v-for="type in types"
                                    :value="type.id"
                                    :key="type.id">
                                    {{ type.text }}
                                </option>
                            </b-select>
                        </b-field>
                        <b-field label="Group">
                            <b-select placeholder="Select a group" v-model="item.group" required expanded>
                                <option
                                    v-for="group in groups"
                                    :value="group.id"
                                    :key="group.id">
                                    {{ group.content }}
                                </option>
                            </b-select>
                        </b-field>
                        <b-field label="Color">
                        <b-dropdown aria-role="list" expanded>
                            <button class="button is-fullwidth" slot="trigger" slot-scope="{ colorPickerActive }" type="button">
                                <span class="previewColor" :style="getCurrentColorStyle()"></span><span>{{ getCurrentColorName() }}</span>
                                <b-icon :icon="colorPickerActive ? 'menu-up' : 'menu-down'"></b-icon>
                            </button>
                            <b-dropdown-item v-for="color in colors" aria-role="listitem" @click="setColor(color)">
                                <span class="previewColor" :style="color.style"></span> {{ color.name }}
                            </b-dropdown-item>
                        </b-dropdown>
                        </b-field>
                        <b-field label="Start">
                            <b-datepicker
                                placeholder="Click to select..."
                                v-model="item.start"
                                icon="calendar-today"
                                trap-focus>
                            </b-datepicker>
                        </b-field>
                        <b-field label="End" v-if="item.type === 'range' || item.type === 'background'">
                            <b-datepicker
                                placeholder="Click to select..."
                                v-model="item.end"
                                icon="calendar-today"
                                trap-focus>
                            </b-datepicker>
                        </b-field>

                    </div>

                </div>
                    </b-tab-item>
                    <b-tab-item label="Tags" v-if="false">
                        <b-field label="Enter some tags">
                            <b-taginput
                                v-model="item.tags"
                                :data="filteredTags"
                                autocomplete
                                allow-new
                                open-on-focus
                                field="user.first_name"
                                icon="label"
                                placeholder="Add a tag"
                                @typing="getFilteredTags">
                            </b-taginput>
                        </b-field>
                    </b-tab-item>




                </b-tabs>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$emit('close')">Close</button>
                <button class="button is-primary" type="button" @click="saveForm">Save</button>
                <button class="button is-danger delButton" type="button" v-if="item.id != null" @click="deleteEntry">
                    delete
                </button>
            </footer>
        </div>
    </form>
</template>

<script>
    import LinkButton from "./tools/LinkButton";

    export default {
        name: "TimelineItemModelForm",
        components: {LinkButton},
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
            setColor(colorItem) {
                this.item.color = colorItem;
            },
            getCurrentColorName() {
                if(typeof this.item.color === "undefined") {
                    return 'Default';
                }
                if(typeof this.item.color.name === "undefined") {
                    return 'Default';
                }
                return this.item.color.name;
            },
            getCurrentColorStyle() {
                if(typeof this.item.color === "undefined") {
                    return this.colors[0].style;
                }
                if(typeof this.item.color.style === "undefined") {
                    return this.colors[0].style;
                }
                return this.item.color.style;
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
            saveForm: function () {
                this.saveAction();

            },
            saveAction: function () {
                if (this.csrf === null) {
                    console.error('CSRF Meta not set. Update not possible');
                    this.$buefy.toast.open({
                        duration: 5000,
                        message: `Save didn't work. Come back later.`,
                        type: 'is-danger'
                    });
                    this.$emit('close');
                }
                var that = this;
                $.ajax({
                    method: "PUT",
                    url: "/ajax/timeline/item",
                    data: that.item,
                    dataType: 'json'
                }).done(function (data) {
                    var msg = `Saved successfully`;
                    that.$buefy.toast.open(msg);
                    that.backup.start = that.item.start;
                    that.backup.end = that.item.end;
                    that.backup.content = that.item.title;
                    that.backup.title = that.item.title;
                    that.backup.description = that.item.description;
                    that.backup.group = that.item.group;
                    that.backup.type = that.item.type;
                    that.backup.links = that.item.links;
                    that.backup.subtitle = that.item.subtitle;
                    that.$emit('close');
                    if (typeof that.item.id === "undefined" || that.item.id === null) {
                        that.backup.id = data.data.id;
                        that.$parent.$parent.$parent.newItemInTimeLine(that.backup);
                    }
                    that.backup.links = data.data.links;
                    that.backup.style = data.data.style;
                }).fail(function (data) {
                    var msg = (typeof data.responseJSON.message !== "undefined") ? data.responseJSON.message : `Save didn't work. Come back later.`;
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
                    message: 'You want to Delete this Item?',
                    onConfirm: function () {
                        $.ajax({
                            method: "DELETE",
                            url: "/ajax/timeline/item",
                            data: that.item,
                            dataType: 'json'
                        }).done(function (data) {
                            var msg = `Delete successfully`;
                            that.$buefy.toast.open(msg);
                            that.$parent.$parent.$parent.deleteItemInTimeLine(that.backup.id);
                            that.$emit('close');
                        }).fail(function (data) {
                            var msg = (typeof data.responseJSON.message !== "undefined") ? data.responseJSON.message : `Delete didn't work. Come back later.`;
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
