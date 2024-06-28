<template>
    <section>
        <b-button icon-left="plus-thick" @click="openModalNew" class="is-inverted">{{ trans.get('project.timelines.group.add') }}</b-button>
    </section>
</template>

<script>
    import TimelineGroupModelForm from "./TimelineGroupModelForm";
    export default {
        name: "TimelineGroupModel",
        props: ['project'],
        components: {
            TimelineGroupModelForm,
        },
        data() {
            return {
                setItem: {}
            }
        },
        methods: {
            openModalNew: function() {
                this.setItem = {
                    title: null,
                    content: '',
                    parent: null,
                    project_id: this.project,
                    show_share: true,
                    visible: 1,
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
                    component: TimelineGroupModelForm,
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
