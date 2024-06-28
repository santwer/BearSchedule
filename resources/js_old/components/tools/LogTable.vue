<template>
    <b-table :data="data">
        <template slot-scope="props">
            <b-table-column field="column.updated_at" label="Date Time"
                            sortable>
                {{ new Date(props.row.updated_at).toLocaleString() }}
            </b-table-column>
            <b-table-column field="column.action" label="Action"
                            sortable>
                {{ props.row.action }}
            </b-table-column>
            <b-table-column field="column.type" label="Object"
                            sortable>
                <span v-if="props.row.type === 'SETTINGS'">Project / Settings</span>
                <span v-if="props.row.type === 'USERS'">Project Users</span>
                <span v-if="props.row.type === 'SHARE'">Share Settings</span>
                <span v-if="props.row.type === 'GROUP'">Group</span>
                <span v-if="props.row.type === 'ITEM'">Item</span>
            </b-table-column>
            <b-table-column field="column.old_value" label="Old"
                            sortable>
                <LogTableDataField :data="props.row.old_value"></LogTableDataField>
            </b-table-column>
            <b-table-column field="column.new_value" label="New"
                            sortable>
                <LogTableDataField :data="props.row.new_value"></LogTableDataField>

            </b-table-column>
        </template>
    </b-table>
</template>

<script>
    import LogTableDataField from "./LogTableDataField";
    export default {
        name: "LogTable",
        components: {LogTableDataField},
        props: ['data'],
        data() {
            return {
                columns: [
                    {
                        field: 'id',
                        label: 'ID',
                        width: '40',
                        numeric: true
                    },
                    {
                        field: 'action',
                        label: 'Action',
                    },
                    {
                        field: 'type',
                        label: 'Area',
                    },
                    {
                        field: 'old_value',
                        label: 'Old',
                    },
                    {
                        field: 'new_value',
                        label: 'new',
                    },]
            }
        },
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
