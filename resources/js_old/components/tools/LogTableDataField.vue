<template>
    <section style="max-width: 400px;">
        <span   v-if="objectSize(displayData) === 0">
            empty
        </span>
            <p  v-if="objectSize(displayData) > 0">
                Entries {{ objectSize(displayData) }}
            </p>

        <b-collapse :open="false" position="is-bottom" aria-id="contentIdForA11y1"   v-if="objectSize(displayData) > 0">
            <a slot="trigger" slot-scope="props" aria-controls="contentIdForA11y1">
                <b-icon :icon="!props.open ? 'menu-down' : 'menu-up'"></b-icon>
                {{ !props.open ? 'All options' : 'Fewer options' }}
            </a>
            <span>
                <div class="columns" v-for="(value, key) in displayData">
                       <div class="column">
                            {{ key }}
                       </div>
                       <div class="column">
                            {{ value }}
                       </div>
                </div>
            </span>
        </b-collapse>
    </section>
</template>

<script>
    export default {
        name: "LogTableDataField",
        props: ['data'],
        data() {
            return {
                displayData: {}
            }
        },
        methods: {
            objectSize: function(obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) size++;
                }
                return size;
            }
        },
        mounted() {
            if(this.data === '') {
                this.displayData = {};
            } else {
                this.displayData = JSON.parse(this.data);
            }
        }
    }
</script>

<style scoped>

</style>
