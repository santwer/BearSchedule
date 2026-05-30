<template>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $t('menu.settings') }}</h1>
    </div>
    <loading v-if="loading"></loading>
     <div class="row" v-else>

        <!-- Content Column -->
        <div class="col-lg-6 mb-4">
            <error :error="error"></error>
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $t('settings.account') }}</h6>
                </div>
                <div class="card-body">
                    <!-- input fields for name, email -->
                    <div class="form-group">
                        <label for="name">{{ $t('settings.name') }}</label>
                        <input type="text" class="form-control" id="name" v-model="settings.name">
                    </div>
                    <div class="form-group">
                        <label for="email">{{ $t('general.email_address') }}</label>
                        <input type="email" class="form-control" id="email" v-model="settings.email">
                    </div>

                </div>
                <div class="card-body">
                    <!-- change Passwort -->
                    <div class="form-group">
                        <label for="password">{{ $t('general.password') }}</label>
                        <input type="password" class="form-control" id="password" v-model="settings.password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">{{ $t('auth.password_confirm') }}</label>
                        <input type="password" class="form-control" id="password_confirmation"
                               v-model="settings.password_confirmation">
                    </div>
                </div>
                <div class="card-body">
                    <!-- Save Button -->
                    <button class="btn btn-primary" @click="saveSettings">{{ $t('general.save') }}</button>

                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $t('settings.passkeys') }}</h6>
                    <button
                        v-if="!settings.is_ms_account"
                        class="btn btn-sm btn-primary"
                        @click="openAddPasskeyModal"
                        :disabled="passkeyActionLoading"
                    >
                        {{ $t('settings.passkeys_add') }}
                    </button>
                </div>
                <div class="card-body">
                    <div v-if="settings.is_ms_account" class="alert alert-warning mb-0">
                        {{ $t('settings.passkey_ms_blocked') }}
                    </div>
                    <div v-else-if="passkeys.length === 0" class="text-muted mb-0">
                        {{ $t('settings.passkeys_empty') }}
                    </div>
                    <ul v-else class="list-group list-group-flush">
                        <li
                            v-for="passkey in passkeys"
                            :key="passkey.id"
                            class="list-group-item d-flex align-items-center justify-content-between px-0"
                        >
                            <div>
                                <div class="fw-semibold">{{ passkey.name }}</div>
                                <small class="text-muted">{{ formatPasskeyDate(passkey.created_at) }}</small>
                            </div>
                            <button
                                class="btn btn-sm btn-outline-danger"
                                @click="openDeletePasskeyModal(passkey)"
                                :disabled="passkeyActionLoading"
                            >
                                {{ $t('general.delete') }}
                            </button>
                        </li>
                    </ul>
                    <div v-if="passkeyError" class="alert alert-danger mt-3 mb-0">
                        {{ passkeyError }}
                    </div>
                </div>
            </div>


        </div>

        <div class="col-lg-6 mb-4">

            <!-- Illustrations -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $t('settings.info') }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 7rem;"
                             :src="settings.avatar">
                    </div>
                    <div class="form-group">
                        <label for="name">{{ $t('settings.account_create_date') }}</label>
                        <input type="text" class="form-control" :disabled="true" v-model="settings.created_at">
                    </div>


                </div>
                <div class="card-body">
                    <!-- Account delete button -->
                    <button class="btn btn-outline-danger" @click="deleteAccount">{{ $t('settings.delete_account') }}</button>
                </div>
            </div>


        </div>
    </div>
    <BModal
        v-model="passkeyModal"
        :title="passkeyModalMode === 'add' ? $t('settings.passkeys_add') : $t('settings.passkey_delete_title')"
        :ok-title="passkeyModalMode === 'add' ? $t('settings.passkeys_add') : $t('general.delete')"
        :cancel-title="$t('general.cancel')"
        :ok-variant="passkeyModalMode === 'add' ? 'primary' : 'danger'"
        :ok-disabled="passkeyActionLoading"
        @ok.prevent="confirmPasskeyAction"
    >
        <div v-if="passkeyModalMode === 'add'" class="form-group mb-3">
            <label for="passkey-name">{{ $t('settings.passkey_name') }}</label>
            <input
                id="passkey-name"
                type="text"
                class="form-control"
                v-model="passkeyModalName"
                :disabled="passkeyActionLoading"
                @keyup.enter="$refs.passkeyPassword?.focus()"
            >
        </div>
        <div class="form-group mb-0">
            <label for="passkey-password">{{ $t('settings.passkey_confirm_password') }}</label>
            <input
                id="passkey-password"
                ref="passkeyPassword"
                type="password"
                class="form-control"
                v-model="passkeyModalPassword"
                :disabled="passkeyActionLoading"
                @keyup.enter="confirmPasskeyAction"
            >
        </div>
    </BModal>
    <div  class="toast-container position-fixed p-3 bottom-0 end-0">
        <BToast v-model="success" variant="success">
            <template #title>
                Änderungen gespeichert
            </template>
            Erfolgreich gespeichert
        </BToast>
        <BToast v-model="passkeySuccess" variant="success">
            <template #title>
                {{ $t('general.success') }}
            </template>
            {{ passkeySuccessMessage }}
        </BToast>
    </div>

</template>

<script>
import Api from "@/Api";
import { Passkeys } from "@/lib/passkeys";
import {BModal, BToast} from "bootstrap-vue-next";
import Loading from "@/componants/parts/Loading.vue";
import Error from "@/componants/parts/Error.vue";
import ThemeMixin from "@/mixins/ThemeMixin";

export default {
    mixins: [ThemeMixin],
    data() {
        return {
            settings: {},
            passkeys: [],
            loading: true,
            success: false,
            passkeySuccess: false,
            passkeySuccessMessage: '',
            error: null,
            passkeyError: null,
            passkeyModal: false,
            passkeyModalMode: 'add',
            passkeyModalName: '',
            passkeyModalPassword: '',
            passkeyToDelete: null,
            passkeyActionLoading: false,
        };
    },
    components: {
        Error,
        Loading,
        BModal,
        BToast
    },
    methods: {
        deleteAccount() {
            this.error = null;
        },
        saveSettings() {
            this.error = null;
            this.loading = true;
            Api.saveSettings(this.settings)
                .then(response => {
                    this.loading = false;
                    this.success = true;
                    setTimeout(() => {
                        this.success = false;
                    }, 3000);
                })
                .catch(error => {
                    this.error = error.response.data;
                    console.log(error.response.data);
                    this.loading = false;
                });
        },

        getSettings() {
            this.error = null;
            this.loading = true;
            Api.getSettings()
                .then(response => {
                    this.settings = response.data.data;
                    this.settings = {
                        ...this.settings,
                        password: '',
                        password_confirmation: '',
                    };
                    this.loading = false;
                    this.loadPasskeys();
                })
                .catch(error => {
                    this.error = error;
                    this.loading = false;
                });
        },

        loadPasskeys() {
            if (this.settings.is_ms_account) {
                this.passkeys = [];
                return;
            }

            Api.listPasskeys()
                .then(response => {
                    this.passkeys = response.data;
                    this.passkeyError = null;
                })
                .catch(error => {
                    this.passkeyError = error.response?.data?.message || error.message;
                });
        },

        formatPasskeyDate(value) {
            if (!value) {
                return '';
            }

            return new Date(value).toLocaleString(this.$i18n.locale);
        },

        openAddPasskeyModal() {
            this.passkeyModalMode = 'add';
            this.passkeyModalName = '';
            this.passkeyModalPassword = '';
            this.passkeyToDelete = null;
            this.passkeyError = null;
            this.passkeyModal = true;
        },

        openDeletePasskeyModal(passkey) {
            this.passkeyModalMode = 'delete';
            this.passkeyModalName = '';
            this.passkeyModalPassword = '';
            this.passkeyToDelete = passkey;
            this.passkeyError = null;
            this.passkeyModal = true;
        },

        async confirmPasskeyAction(event) {
            event?.preventDefault?.();

            if (!this.passkeyModalPassword) {
                this.passkeyError = this.$t('settings.passkey_confirm_password');
                return;
            }

            if (this.passkeyModalMode === 'add' && !this.passkeyModalName.trim()) {
                this.passkeyError = this.$t('settings.passkey_name');
                return;
            }

            this.passkeyActionLoading = true;
            this.passkeyError = null;

            try {
                await Api.confirmPassword(this.passkeyModalPassword);

                if (this.passkeyModalMode === 'add') {
                    await Passkeys.register({
                        name: this.passkeyModalName.trim(),
                    });
                    this.passkeySuccessMessage = this.$t('settings.passkey_added');
                } else {
                    await Api.deletePasskey(this.passkeyToDelete.id);
                    this.passkeySuccessMessage = this.$t('settings.passkey_deleted');
                }

                this.passkeyModal = false;
                this.passkeySuccess = true;
                setTimeout(() => {
                    this.passkeySuccess = false;
                }, 3000);
                await this.loadPasskeys();
            } catch (error) {
                this.passkeyError = error.response?.data?.message || error.message;
            } finally {
                this.passkeyActionLoading = false;
            }
        },
    },
    mounted() {
        this.getSettings();
    }


}

</script>

<style scoped>

</style>
