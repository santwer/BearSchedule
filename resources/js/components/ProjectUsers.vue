<template>
    <div>
        <b-table :data="userData">
            <template slot-scope="props">
                <b-table-column field="name" :label="trans.get('project.setting_users.name')" sortable>
                    {{ props.row.name }}
                </b-table-column>
                <b-table-column field="email" :label="trans.get('project.setting_users.email')" sortable>
                    {{ props.row.email }}
                </b-table-column>
                <b-table-column field="pivot.role" :label="trans.get('project.setting_users.role')" sortable>
                    <b-select placeholder="Select a role" v-model="props.row.pivot.role" :disabled="role !== 'ADMIN'"
                              :name="inputName(props.row.id, 'role')">
                        <option
                            v-for="option in roles"
                            :value="option.id"
                            :key="option.id">
                            {{ trans.get('project.setting_users.roles_option.' + option.id) }}
                        </option>
                    </b-select>
                </b-table-column>
                <b-table-column label="">
                    <b-button size="is-small" icon-left="window-close" :data-id="props.row.id" v-if="role === 'ADMIN'"
                              @click="deleteEntry(props.row.id)"></b-button>
                    <input type="hidden" :name="inputName(props.row.id, 'action')"></input>
                </b-table-column>
            </template>
        </b-table>

        <ajax-search-input  v-if="role === 'ADMIN'" src="/ajax/autocomplete/User" :headline="trans.get('project.setting_users.add_user')" v-on:selectitem="addUser"></ajax-search-input>
    </div>
</template>

<script>
    import AjaxSearchInput from "./tools/AjaxSearchInput";
    export default {

        name: "ProjectUsers",
        components: {AjaxSearchInput},
        props: ['users', 'role'],
        data() {
            return {
                userData: [],
                roles: [
                    {id: 'ADMIN', name: 'Admin'},
                    {id: 'EDITOR', name: 'Editor'},
                    {id: 'SUBSCRIBER', name: 'Subscriber'},
                ],
            }

        }, methods: {
            dd: function (varl) {
                console.log('dd', varl);
                return '';
            },
            deleteEntry: function (id) {
                var $input = $('input[name="' + this.inputName(id, 'action') + '"]');
                $input.val('delete');
                $input.parent().parent().addClass('deleted-row');
                $input.parent().find('button').prop('disabled', 'disabled');
                this.dd('input[name="' + this.inputName(id, 'action') + '"]')
            },
            inputName: function (id, field) {
                return 'users[' + id + '][' + field + ']';
            },
            addUser: function (item) {
                if(item !== null) {
                    item.pivot = { role: 'SUBSCRIBER' };
                    this.userData.push(item);
                }
            }
        },
        watch: {

        },


        mounted() {
            this.userData = this.users;
        }
    }
</script>

<style scoped>

</style>
