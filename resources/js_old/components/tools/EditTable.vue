<template>
    <b-table :data="data">
        <template slot-scope="props">
            <b-table-column v-for="column in columns" :key="column.field"
                            v-bind:field="column.field"
                            v-bind:label="column.label"
                            v-bind:width="column.width"
                            v-bind:numeric="column.numeric" sortable>
                {{ props.row[column.field] }}
            </b-table-column>
            <b-table-column label="">
                <b-button size="is-small" icon-left="pencil" @click="handleClick(props.row.id)"></b-button>
            </b-table-column>
        </template>
    </b-table>
</template>

<script>
    export default {
        name: "EditTable",
        props: ['data', 'columns'],
        methods: {
            handleClick(id) {
                var input = id;
                this.$emit('click', input);
            }
        }
    }
</script>

<style scoped>

</style>
