<template>
    <section>
        <b-button size="is-small" icon-left="plus-thick" @click="openModalNew">Add Item</b-button>
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
                    start: new Date(),
                    end: new Date(),
                    title: null,
                    type: 'range',
                    content: '',
                    subtitle: '',
                    group: null,
                    project_id: this.project,
                    links: [],
                    tags: [],
                    color: {},
                };
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
            }
        }
    }
</script>

<style scoped>

</style>
