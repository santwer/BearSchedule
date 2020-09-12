<template>
    <form action="">
        <div class="modal-card" style="width: 1200px;">
            <header class="modal-card-head">
                <p class="modal-card-title">Item</p>
                <button
                    type="button"
                    class="delete"
                    @click="$emit('close')"/>
            </header>
            <section class="modal-card-body">
                <div class="columns">
                    <div class="column">
                        <b-field label="Title">
                            <b-input
                                type="text"
                                v-model="item.content"
                                placeholder="Title">
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
                        <b-field label="Title (on Mouse over)">
                            <b-input
                                type="text"
                                v-model="item.title"
                                placeholder="Title"
                                required>
                            </b-input>
                        </b-field>
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
                        <b-field label="Start">
                            <b-datepicker
                                placeholder="Click to select..."
                                v-model="item.start"
                                icon="calendar-today"
                                trap-focus>
                            </b-datepicker>
                        </b-field>
                        <b-field label="End">
                            <b-datepicker
                                placeholder="Click to select..."
                                v-model="item.end"
                                icon="calendar-today"
                                trap-focus>
                            </b-datepicker>
                        </b-field>
                    </div>
                </div>
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
                item: {
                    id: null,
                    title: '',
                    content: '',
                    start: null,
                    end: null,
                    group: null,
                    links: []
                },
                backup: {},
                csrf: null,
                groups: [],
                types: [
                    {id: 'box', text: 'default'},
                    {id: 'point', text: 'point'},
                    {id: 'range', text: 'range'},
                    {id: 'background', text: 'background'},
                ]
            }
        },
        methods: {
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
                console.log(that.item)
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
                    that.backup.content = that.item.content;
                    that.backup.title = that.item.title;
                    that.backup.description = that.item.description;
                    that.backup.group = that.item.group;
                    that.backup.type = that.item.type;
                    that.backup.links = that.item.links;
                    that.$emit('close');
                    if (typeof that.item.id === "undefined" || that.item.id === null) {
                        that.backup.id = data.data.id;
                        that.$parent.$parent.$parent.newItemInTimeLine(that.backup);
                    }
                    that.backup.links = data.data.links;
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
                console.log(this.item.links)
                if (typeof this.item.links === "undefined") {
                    this.item.links = [];
                }
                this.item.links.push({href: '', title: ''});
                console.log(this.item.links)
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
</style>
