<template>
    <div>
        <timeline ref="timeline"
                  :items="items"
                  :groups="groups"
                  :options="options"></timeline>
    </div>
</template>

<script>
    import { DataSet, Timeline } from 'vue2vis';
    export default {
        name: "StudentsTimeline",
        components: {
            Timeline,
            DataSet
        },
        data() {
            return {
                groups: [{
                    id: 0,
                    content: 'Group 1'
                }],
                items: [{
                    id: 0,
                    group: 0,
                    start: new Date(),
                    end: '2020-12-01',
                    style: 'background: blue; color: #fff;',
                    className: 'olalla',
                    content: 'Item 1'
                }],
                options: {
                    editable: true,
                }
            }
        },
    }
</script>

<style scoped>

</style>
