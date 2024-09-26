<template>
    <BModal
        :title="$t('project_share')"
        v-model="modal"
        size="lg"
        :ok-title="$t('general.close')"
        :ok-only="true"
        :ok-variant="isDark ? 'secondary' : 'secondary'"
        v-b-modal.modal-center>
        <loading v-if="loading"></loading>
        <div v-else>
            <div class="card">
                <div class="card-header" :class="{'text-gray-200': isDark, 'text-primary': !isDark}">
                    {{ $t('project_project_members') }}
                </div>
                <BTable :sort-by="[{key: 'name', order: 'desc'}]"
                        :items="sortItems"
                        :fields="sortFields"
                >
                    <template #cell(remove)="row">
                        <BButton variant="outline-danger"
                                 size="sm" class="pt-0 px-1"
                                 @click="openDeleteModal(row.item)"
                        >
                            <mdicon name="close-thick" size="20"/>
                        </BButton>
                    </template>
                    <template #cell(role)="row">
                        <BFormSelect v-model="row.value"
                                     :state="row.item.role_state"
                                     :disabled="row.item.role_state === false"
                                     :options="roleOptions"
                                     v-on:change="changeRole(row)"
                                     size="sm"/>
                        <div class="text-danger" v-if="row.item.save_error">
                            {{ row.item.save_error }}
                        </div>
                    </template>
                </BTable>
                <div class="m-3 mt-0">
                    <div class="form-group">
                        <label>{{ $t('add user to project') }}</label>
                        <BInputGroup>
                            <template #append>
                                <BInputGroupText v-if="searchLoader">
                                    <BSpinner small variant="light"/>
                                </BInputGroupText>
                            </template>
                            <BFormInput list="my-list-id"
                                        v-on:keyup="initLoading"
                                        :placeholder="$t('enter name or email...')"
                                        v-model="searchPerson"
                                        v-debounce:500ms="doSearch"/>
                        </BInputGroup>
                        <datalist id="my-list-id" v-on:select="setParticipant('x')">
                            <option v-for="entry in datalistSearch" :value="JSON.stringify(entry)">{{
                                    entry.value
                                }}
                            </option>
                        </datalist>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header" :class="{'text-gray-200': isDark, 'text-primary': !isDark}">
                    {{ $t('share project to everyone without account') }}
                </div>

                <BInputGroup prepend="Username" class="p-3 pb-0">
                    <template #prepend>
                        <BInputGroupText>
                            <mdicon name="link-variant" size="20"/>
                        </BInputGroupText>
                    </template>
                    <BFormInput v-model="shareUrl" :state="copyState" :readonly="true"/>
                    <BButton :variant="isDark ? 'secondary' : 'outline-secondary'" @click="copyLinkToClipboard">
                        <mdicon name="content-copy" size="20"/>
                        {{ $t('copy') }}
                    </BButton>
                    <BButton :variant="isDark ? 'secondary' : 'outline-secondary'" @click="openLinkInNewWindow">
                        <mdicon name="open-in-new" size="20"/>
                        {{ $t('open') }}
                    </BButton>
                </BInputGroup>
                <div class="pb-3" v-if="copyState === null"></div>
                <div class="text-success px-3" v-if="copyState === true">{{ $t('successfully copied') }}</div>
                <div class="text-danger px-3" v-if="copyState === false">{{ $t('text could not be copied') }}</div>
            </div>
        </div>
    </BModal>
    <BModal
        :title="$t('remove person from project', {name: toDeleteItem ? toDeleteItem.name : ''})"
        v-model="deleteModal"
        :ok-title="$t('general.delete')"
        :cancel-title="$t('general.cancel')"
        v-on:ok="removePerson"
        v-on:cancel="toDeleteItem = null"
        v-on:backdrop="toDeleteItem = null"
        v-on:close="toDeleteItem = null"
        v-b-modal.modal-center>
        <p>{{ $t('Do you want to remove person from project', {name: toDeleteItem ? toDeleteItem.name : ''}) }}</p>
    </BModal>
</template>

<script>
import {
    BModal,
    BButton,
    BFormCheckbox,
    BButtonGroup,
    BFormSelect,
    BTable,
    BInputGroup,
    BInputGroupText,
    BFormInput,
    BSpinner
} from "bootstrap-vue-next";
import Loading from "@/componants/parts/Loading.vue";
import Error from "@/componants/parts/Error.vue";
import ThemeMixin from "@/mixins/ThemeMixin";
import Api from "@/Api";
import App from "@/componants/App.vue";


export default {
    mixins: [ThemeMixin],
    components: {
        Loading,
        Error,
        BModal, BButton, BFormCheckbox, BButtonGroup,
        BFormSelect, BTable, BInputGroup, BFormInput,
        BInputGroupText, BSpinner
    },
    data() {
        return {
            datalistSearch: [],
            searchPerson: null,
            modal: false,
            loading: false,
            searchLoader: false,
            toDeleteItem: null,
            deleteModal: false,
            eventSource: null,
            error: null,
            copyState: null,
            shareUrl: 'https://example.com/share/1234',
            sortFields: [
                {key: 'name', label: this.$t('project_setting_users.name'), sortable: true},
                {key: 'email', label: this.$t('project_setting_users.email'), sortable: true},
                {key: 'role', label: this.$t('project_setting_users.role'), sortable: true},
                {key: 'remove', label: '', sortable: false}
            ],
            sortItems: [],
            roleOptions: [
                {value: 'ADMIN', text: this.$t('project_setting_users.roles_option.ADMIN')},
                {value: 'EDITOR', text: this.$t('project_setting_users.roles_option.EDITOR')},
                {value: 'SUBSCRIBER', text: this.$t('project_setting_users.roles_option.SUBSCRIBER')},
            ]
        }
    },
    methods: {

        initLoading() {
            if (this.searchPerson.length >= 3) {
                this.searchLoader = true;
            }
        },
        doSearch() {
            if (this.searchPerson.length >= 3) {
                this.searchLoader = true;
                Api.searchPerson(this.$route.params.id, this.searchPerson)
                    .then(response => {
                        this.datalistSearch = response.data;
                        this.searchLoader = false;
                    })
                    .catch(error => {
                        this.searchLoader = false;
                        this.error = error;
                    });

            }
        },
        setParticipant(partcipant) {
            partcipant.role_state = null;
            partcipant.role = 'SUBSCRIBER';

            this.sortItems.push(partcipant);
            this.changeRole({item: partcipant, value: 'SUBSCRIBER'});

        },
        getData() {
            this.loading = true;
            this.error = null;
            let project_id = this.$route.params.id;
            Api.getShareData(project_id)
                .then(response => {
                    this.loading = false;
                    this.sortItems = response.data.data.map((item) => {
                        item.role_state = null;
                        return item;
                    });
                    this.shareUrl = response.data.share_url;
                })
                .catch(error => {
                    this.loading = false;
                    this.error = error;
                });
        },
        show() {
            this.modal = true;
            this.getData();
        },
        copyLinkToClipboard() {
            navigator.clipboard.writeText(this.shareUrl).then(() => {
                this.copyState = true;
                setTimeout(() => {
                    this.copyState = null;
                }, 3000);
                console.log('Async: Copying to clipboard was successful!');
            }, err => {
                this.copyState = false;
                setTimeout(() => {
                    this.copyState = null;
                }, 30000);
                console.error('Async: Could not copy text: ', err);
            });
        },
        openLinkInNewWindow() {
            window.open(this.shareUrl, '_blank');
        },
        changeRole(row) {
            let project_id = this.$route.params.id;
            Api.changeRole(project_id, row.item.id, row.value)
                .then(response => {
                    row.item.role_state = true;
                    row.item.role = row.value;
                    setTimeout(() => {
                        row.item.role_state = null;
                    }, 3000);
                })
                .catch(error => {
                    row.item.role_state = false;
                    row.value = row.item.role;
                    row.item.save_error = error.response.data.message;
                    setTimeout(() => {
                        row.item.role_state = null;
                        row.item.save_error = null;
                    }, 30000);
                });
        },
        removePerson() {
            let project_id = this.$route.params.id;
            Api.removePerson(project_id, this.toDeleteItem.id)
                .then(response => {
                    this.getData();
                    this.toDeleteItem = null;
                })
                .catch(error => {
                    this.toDeleteItem = null;
                    this.error = error;
                });
        },
        openDeleteModal(toDeleteItem) {

            this.toDeleteItem = toDeleteItem;
            this.deleteModal = true;

        },
    },
    watch: {
        searchPerson() {
            //string is json
            if (typeof this.searchPerson === 'string') {
                try {
                    let searchPerson = JSON.parse(this.searchPerson);
                    if (searchPerson.id) {
                        this.setParticipant(Object.assign({}, searchPerson));
                        this.searchPerson = null;
                    }
                } catch (e) {

                }
            }
        }
    }
}
</script>


<style scoped>

</style>
