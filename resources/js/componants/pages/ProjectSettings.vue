<template>
    <div>
        <div class="container-fluid">
            <h1 class="h3 mb-4 text-gray-800">
                <button class="btn rounded-circle border-0 p-0 shadow-sm rounded  btn-outline-primary"
                @click="goToProject(this.$route.params.id)"
                >
                    <mdicon name="chevron-left" size="32"/>
                </button>

                {{ $t('project_project_settings') }}
            </h1>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3" :class="{'text-gray-200': isDark, 'text-primary': !isDark}">
                            <h6 class="m-0 font-weight-bold ">
                                <mdicon name="cog" size="20"/>
                                {{ $t('project_settings') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group pb-2">
                                    <label>{{ $t('project_project_name') }}</label>
                                    <input type="text" class="form-control"
                                           :placeholder="$t('project_project_name')">
                                </div>
                                <div class="form-group py-2">
                                    <label>{{ $t('project_item_design') }}</label>
                                    <select class="form-select">
                                        <option>{{ $t('project_itemtype.default') }}</option>
                                        <option>{{ $t('project_itemtype.with_subtitle') }}</option>
                                    </select>
                                </div>
                                <div class="form-group py-2">
                                    <label>{{ $t('project_initial_zoom') }}</label>
                                    <select class="form-select">
                                        <option>{{ $t('project_zoom_timeline.auto') }}</option>
                                        <option>{{ $t('project_zoom_timeline.year') }}</option>
                                        <option>{{ $t('project_zoom_timeline.month') }}</option>
                                        <option>{{ $t('project_zoom_timeline.week') }}</option>
                                        <option>{{ $t('project_zoom_timeline.day') }}</option>
                                    </select>
                                </div>
                                <div class="form-group py-2">
                                    <label>{{ $t('project_axis_orientation') }}</label>
                                    <select class="form-select">
                                        <option>{{ $t('project_axis_ori.bottom') }}</option>
                                        <option>{{ $t('project_axis_ori.top') }}</option>
                                        <option>{{ $t('project_axis_ori.both') }}</option>
                                        <option>{{ $t('project_axis_ori.none') }}</option>
                                    </select>
                                </div>
                                <div class="form-group py-2">
                                    <label>{{ $t('project_item_orientation') }}</label>
                                    <select class="form-select">
                                        <option>{{ $t('project_axis_ori.bottom') }}</option>
                                        <option>{{ $t('project_axis_ori.top') }}</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" :class="{'text-gray-200': isDark, 'text-primary': !isDark}">
                                <mdicon name="file-document-multiple-outline" size="20"/>
                                {{ $t('project_logs') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                                <BTable :sort-by="[{key: 'name', order: 'desc'}]"
                                        :items="sortItems"
                                        :fields="sortFields"
                                >
                                    <template #cell(remove)="row">
                                        <BButton variant="outline-danger"
                                                 size="sm" class="pt-0 px-1"
                                        >
                                            <mdicon name="close-thick" size="20"/>
                                        </BButton>
                                    </template>
                                    <template #cell(role)="row">
                                        <BFormSelect v-model="row.value"
                                                     :options="roleOptions"
                                                     size="sm" />
                                    </template>
                                </BTable>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import {BTable,BButton,BFormSelect} from 'bootstrap-vue-next'
import routeMixin from "@/mixins/RouteMixin";
import themeMixin from "@/mixins/ThemeMixin";
export default {
    mixins: [routeMixin, themeMixin],
    components: {BTable,BButton,BFormSelect},
    data() {
        return {
            sortFields: [
                {key: 'name', label: this.$t('project_setting_users.name'), sortable: true},
                {key: 'email', label: this.$t('project_setting_users.email'), sortable: true},
                {key: 'role', label: this.$t('project_setting_users.role'), sortable: true},
                {key: 'remove', label: '', sortable: false}
            ],
            sortItems: [
                {name: 'John Doe', email: '', role: 'ADMIN', remove: 'Remove'},
                {name: 'Jane Doe', email: '', role: 'EDITOR', remove: 'Remove'},
                {name: 'John Smith', email: '', role: 'SUBSCRIBER', remove: 'Remove'},
                {name: 'Jane Smith', email: '', role: 'SUBSCRIBER', remove: 'Remove'},
            ],
            roleOptions: [
                {value: 'ADMIN', text: this.$t('project_setting_users.roles_option.ADMIN')},
                {value: 'EDITOR', text: this.$t('project_setting_users.roles_option.EDITOR')},
                {value: 'SUBSCRIBER', text: this.$t('project_setting_users.roles_option.SUBSCRIBER')},
            ]
        }
    }

}

</script>

<style scoped>

</style>
