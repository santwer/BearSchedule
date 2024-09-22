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
    <div  class="toast-container position-fixed p-3 bottom-0 end-0">
        <BToast v-model="success" variant="success">
            <template #title>
                Änderungen gespeichert
            </template>
            Erfolgreich gespeichert
        </BToast>
    </div>

</template>

<script>
import Api from "@/Api";
import {BToast,BSpinner} from "bootstrap-vue-next";
import Loading from "@/componants/parts/Loading.vue";
import Error from "@/componants/parts/Error.vue";
import ThemeMixin from "@/mixins/ThemeMixin";

export default {
    mixins: [ThemeMixin],
    data() {
        return {
            settings: {},
            loading: true,
            success: false,
            error: null,
        };
    },
    components: {
        Error,
        Loading,
        BToast
    },
    computed: {},
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
                })
                .catch(error => {
                    this.error = error;
                    this.loading = false;
                });
        },
    },
    mounted() {
        this.getSettings();
    }


}

</script>

<style scoped>

</style>
