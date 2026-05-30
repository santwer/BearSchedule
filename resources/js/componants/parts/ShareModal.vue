<template>
    <BModal
        :title="$t('project_share')"
        v-model="modal"
        size="lg"
        :ok-title="$t('general.close')"
        :ok-only="true"
        :ok-variant="isDark ? 'secondary' : 'secondary'"
        @ok="modal = false"
        @hide="modal = false">
        <loading v-if="loading"></loading>
        <div v-else>
            <BTabs card v-model="activeTab" @activate-tab="onTabActivated">
                <BTab active>
                    <template #title>
                        <mdicon name="account-multiple" size="20"/> {{ $t('project_project_members') }}
                    </template>
                    <div class="card">
                        <BTable :sort-by="[{key: 'name', order: 'desc'}]"
                                :items="sortItems"
                                :fields="sortFields"
                        >
                            <template #cell(remove)="row">
                                <BButton variant="outline-danger" v-if="editable"
                                         size="sm" class="pt-0 px-1"
                                         @click="openDeleteModal(row.item)"
                                >
                                    <mdicon name="close-thick" size="20"/>
                                </BButton>
                            </template>
                            <template #cell(role)="row">
                                <BFormSelect v-model="row.value"
                                             :state="row.item.role_state"
                                             :disabled="row.item.role_state === false || !editable"
                                             :options="roleOptions"
                                             v-on:change="changeRole(row)"
                                             size="sm"/>
                                <div class="text-danger" v-if="row.item.save_error">
                                    {{ row.item.save_error }}
                                </div>
                            </template>
                        </BTable>
                        <div class="m-3 mt-0" v-if="editable">
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
                </BTab>
                <BTab>
                    <template #title>
                        <mdicon name="link-variant" size="20"/> {{ $t('share project to everyone without account') }}
                    </template>
                    <div class="card">
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
                </BTab>
                <BTab lazy>
                    <template #title>
                        <mdicon name="robot" size="20"/> {{ $t('project_mcp_tab') }}
                    </template>
                    <div class="card p-3">
                        <p class="text-muted mb-3">{{ $t('project_mcp_description') }}</p>

                        <loading v-if="mcpLoading"></loading>
                        <div v-else>
                            <div v-if="mcpNewUrl" class="alert alert-success mb-3" role="alert">
                                <p class="mb-2">{{ $t('project_mcp_created_warning') }}</p>
                                <BInputGroup>
                                    <BFormInput v-model="mcpNewUrl" :readonly="true"/>
                                    <BButton :variant="isDark ? 'secondary' : 'outline-secondary'" @click="copyMcpLinkToClipboard">
                                        <mdicon name="content-copy" size="20"/>
                                        {{ $t('copy') }}
                                    </BButton>
                                </BInputGroup>
                                <div class="text-success mt-2" v-if="mcpCopyState === true">{{ $t('successfully copied') }}</div>
                                <div class="text-danger mt-2" v-if="mcpCopyState === false">{{ $t('text could not be copied') }}</div>
                            </div>

                            <div v-if="mcpError" class="text-danger mb-3">{{ mcpError }}</div>

                            <BTable
                                v-if="mcpTokens.length"
                                :items="mcpTokens"
                                :fields="mcpTokenFields"
                                small
                                responsive
                            >
                                <template #cell(label)="row">
                                    {{ row.item.name || row.item.token_prefix }}
                                </template>
                                <template #cell(created_at)="row">
                                    {{ formatMcpDate(row.item.created_at) }}
                                </template>
                                <template #cell(last_used_at)="row">
                                    {{ row.item.last_used_at ? formatMcpDate(row.item.last_used_at) : '—' }}
                                </template>
                                <template #cell(actions)="row">
                                    <BButton
                                        variant="outline-danger"
                                        size="sm"
                                        @click="revokeMcpToken(row.item)"
                                    >
                                        {{ $t('project_mcp_revoke') }}
                                    </BButton>
                                </template>
                            </BTable>
                            <p v-else class="text-muted">{{ $t('project_mcp_no_tokens') }}</p>

                            <div class="mt-3">
                                <label class="form-label">{{ $t('project_mcp_name') }}</label>
                                <BInputGroup>
                                    <BFormInput v-model="mcpNewName" :disabled="mcpCreating"/>
                                    <BButton
                                        :variant="isDark ? 'primary' : 'primary'"
                                        :disabled="mcpCreating"
                                        @click="createMcpToken"
                                    >
                                        <BSpinner v-if="mcpCreating" small class="me-1"/>
                                        {{ $t('project_mcp_create') }}
                                    </BButton>
                                </BInputGroup>
                            </div>
                        </div>
                    </div>
                </BTab>

            </BTabs>


        </div>
    </BModal>
    <BModal
        :title="$t('remove person from project', {name: toDeleteItem ? toDeleteItem.name : ''})"
        v-model="deleteModal"
        :ok-title="$t('general.delete')"
        :cancel-title="$t('general.cancel')"
        v-on:ok="removePerson"
        v-on:cancel="toDeleteItem = null"
        v-on:close="toDeleteItem = null">
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
    BSpinner,
    BTabs,
    BTab
} from "bootstrap-vue-next";
import Loading from "@/componants/parts/Loading.vue";
import Error from "@/componants/parts/Error.vue";
import ThemeMixin from "@/mixins/ThemeMixin";
import Api from "@/Api";


export default {
    mixins: [ThemeMixin],
    components: {
        Loading,
        Error,
        BModal, BButton, BFormCheckbox, BButtonGroup,
        BFormSelect, BTable, BInputGroup, BFormInput,
        BInputGroupText, BSpinner, BTabs, BTab
    },
    props: {
        editable: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            activeTab: 0,
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
            mcpTokens: [],
            mcpLoading: false,
            mcpCreating: false,
            mcpLoaded: false,
            mcpError: null,
            mcpNewName: '',
            mcpNewUrl: null,
            mcpCopyState: null,
            sortFields: [
                {key: 'name', label: this.$t('project_setting_users.name'), sortable: true},
                {key: 'email', label: this.$t('project_setting_users.email'), sortable: true},
                {key: 'role', label: this.$t('project_setting_users.role'), sortable: true},
                {key: 'remove', label: '', sortable: false}
            ],
            mcpTokenFields: [
                {key: 'label', label: this.$t('project_mcp_name'), sortable: false},
                {key: 'created_at', label: this.$t('project_mcp_created_at'), sortable: true},
                {key: 'last_used_at', label: this.$t('project_mcp_last_used'), sortable: true},
                {key: 'actions', label: '', sortable: false}
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
            this.mcpLoaded = false;
            this.mcpNewUrl = null;
            this.mcpCopyState = null;
            this.mcpNewName = '';
            this.mcpError = null;
            this.getData();
        },
        onTabActivated(tabIndex) {
            if (tabIndex === 2) {
                this.loadMcpTokens();
            }
        },
        loadMcpTokens(force = false) {
            if (this.mcpLoaded && !force) {
                return;
            }

            if (!this.mcpLoaded) {
                this.mcpLoading = true;
            }

            this.mcpError = null;

            Api.getMcpTokens(this.$route.params.id)
                .then(response => {
                    this.mcpTokens = response.data.data;
                    this.mcpLoaded = true;
                    this.mcpLoading = false;
                })
                .catch(error => {
                    this.mcpLoading = false;
                    this.mcpError = error.response?.data?.message || error.message;
                });
        },
        createMcpToken() {
            this.mcpCreating = true;
            this.mcpError = null;

            const payload = {};
            if (this.mcpNewName.trim() !== '') {
                payload.name = this.mcpNewName.trim();
            }

            Api.createMcpToken(this.$route.params.id, payload)
                .then(response => {
                    this.mcpNewUrl = response.data.data.url;
                    this.mcpNewName = '';
                    this.mcpCreating = false;
                    this.loadMcpTokens(true);
                })
                .catch(error => {
                    this.mcpCreating = false;
                    this.mcpError = error.response?.data?.message || error.message;
                });
        },
        revokeMcpToken(token) {
            if (!window.confirm(this.$t('project_mcp_revoke_confirm'))) {
                return;
            }

            Api.revokeMcpToken(this.$route.params.id, token.id)
                .then(() => {
                    if (this.mcpNewUrl) {
                        this.mcpNewUrl = null;
                    }
                    this.loadMcpTokens(true);
                })
                .catch(error => {
                    this.mcpError = error.response?.data?.message || error.message;
                });
        },
        copyLinkToClipboard() {
            navigator.clipboard.writeText(this.shareUrl).then(() => {
                this.copyState = true;
                setTimeout(() => {
                    this.copyState = null;
                }, 3000);
            }, () => {
                this.copyState = false;
                setTimeout(() => {
                    this.copyState = null;
                }, 30000);
            });
        },
        copyMcpLinkToClipboard() {
            navigator.clipboard.writeText(this.mcpNewUrl).then(() => {
                this.mcpCopyState = true;
                setTimeout(() => {
                    this.mcpCopyState = null;
                }, 3000);
            }, () => {
                this.mcpCopyState = false;
                setTimeout(() => {
                    this.mcpCopyState = null;
                }, 30000);
            });
        },
        formatMcpDate(value) {
            if (!value) {
                return '';
            }

            return new Date(value).toLocaleString();
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
