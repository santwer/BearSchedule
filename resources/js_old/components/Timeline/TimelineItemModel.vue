<template>
    <section>
        <b-button icon-left="plus-thick" @click="openModalNew" class="is-inverted">{{ trans.get('project.timelines.item.new') }}</b-button>
    </section>
</template>

<script>
    import TimelineItemModelForm from "./TimelineItemModelForm";
    export default {
        name: "TimelineItemModel",
        props: ['project'],
        components: {
            TimelineItemModelForm,
        },
        data() {
            return {
                setItem: {}
            }
        },
        methods: {
            openModalNew: function() {
                this.setItem = {
                    start: this.getNewDate(),
                    end: this.getNewDate(1),
                    title: null,
                    type: 'range',
                    content: '',
                    subtitle: '',
                    group: null,
                    project_id: this.project,
                    links: [],
                    tags: [],
                    status: null,
                    color: {},
                };
                if(typeof enableJira !== "undefined" && enableJira)
                    this.setItem.jira = [];

                this.$emit('addItem', this.setItem)
                this.openModal();
            },
            openModelItem: function(item) {
                this.setItem = item;
                this.openModal();
            },
            openModal: function () {
                var that = this;
                this.$buefy.modal.open({
                    parent: this,
                    component: TimelineItemModelForm,
                    props: {
                        setItem: that.setItem
                    },
                    distroyOnHide: false,
                    hasModalCard: true,
                    trapFocus: true
                })
            },
            getNewDate(addDays) {
                addDays = addDays || 0;
                var date = new Date();
                date.setDate(date.getDate() + addDays);
                return new Date(date.toDateString())
            }
        }
    }
</script>

<style scoped>

</style>
