<template>
    <form action="">
        <div class="modal-card" style="width: 400px;">
            <header class="modal-card-head">
                <p class="modal-card-title">Group</p>
                <button
                    type="button"
                    class="delete"
                    @click="$emit('close')"/>
            </header>
            <section class="modal-card-body">
                <b-field label="Name">
                    <b-input
                        type="text"
                        v-model="item.content"
                        placeholder="Title">
                    </b-input>
                </b-field>
                <b-field label="show in share">
                    <b-switch v-model="item.show_share">
                        {{ item.show_share ? 'Yes' : 'No' }}
                    </b-switch>
                </b-field>
                <b-field label="Group">
                    <b-select placeholder="Select a group" v-model="item.parent" expanded>
                        <option
                            v-for="group in groups"
                            :value="group.id"
                            :key="group.id">
                            {{ group.content }}
                        </option>
                    </b-select>
                </b-field>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$emit('close')">Close</button>
                <button class="button is-primary" type="button" @click="saveForm">Save</button>
                <button class="button is-danger delButton" type="button" v-if="item.id != null" @click="deleteEntry">delete</button>

            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        name: "TimelineGroupModelForm",
        props: ['setItem'],
        data() {
            return {
                item: {
                    id: null,
                    title: '',
                    content: '',
                    start: null,
                    end: null,
                    parent: null,
                },
                backup: {},
                csrf: null,
                groups: []
            }
        },
        methods: {
            saveForm: function () {
                this.saveAction();

            },
            getOrderNum: function () {
                var max = Math.max.apply(Math, this.$parent.$parent.$parent.groups.map(function(o) { return o.order; }));
                if(typeof max === "number") {
                    return max + 1;
                }
                return null;
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
                if(typeof that.item.order === "undefined") {
                    that.item.order = that.getOrderNum();
                }
                $.ajax({
                    method: "PUT",
                    url: "/ajax/timeline/group",
                    data: that.item,
                    dataType: 'json'
                }).done(function (data) {
                    var msg = `Saved successfully`;
                    that.$buefy.toast.open(msg);
                    that.backup.content = that.item.content;
                    that.backup.title = that.item.content;
                    that.backup.parent = that.item.parent;
                    that.backup.show_share = that.item.show_share;
                    that.backup.order =that.item.order;
                    //
                    that.$emit('close');
                    if (typeof that.item.id === "undefined" || that.item.id === null) {
                        that.backup.id = data.data.id;
                        that.$parent.$parent.$parent.newGroupInTimeLine(that.backup);
                    }
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
                        message: 'You want to Delete this Group?',
                        onConfirm: function () {

                            $.ajax({
                                method: "DELETE",
                                url: "/ajax/timeline/group",
                                data: that.item,
                                dataType: 'json'
                            }).done(function (data) {
                                var msg = `Delete successfully`;
                                that.$buefy.toast.open(msg);
                                that.$parent.$parent.$parent.deleteGroupInTimeLine(that.backup.id);
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
            }
        },
        mounted() {
            this.backup = this.setItem;
            this.groups = this.$parent.$parent.$parent.groups;
            //this.groups.unshift({id: -1, content: 'no group'});
            this.item = Object.assign({}, this.backup);
            this.csrf = $('meta[name=csrf-token]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': this.csrf
                }
            });

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
