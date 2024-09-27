<template>
    <BModal
        :title="$t('rename')"
        v-model="modal"
        :ok-title="$t('general.close')"
        :ok-only="true"
        :ok-variant="isDark ? 'secondary' : 'secondary'"
        v-b-modal.modal-center>
        <div class="form-group pb-2">
            <label>{{ $t('project_project_name') }}</label>
            <BInputGroup class="mt-3">
                <template #append>
                    <BInputGroupText v-if="loading">
                        <BSpinner v-if="loading" small variant="primary"/>
                    </BInputGroupText>
                </template>
                <BFormInput v-model="project_name" :disabled="loading"
                            :state="state"
                            v-on:blur="updateSetting('project_name')"
                            :placeholder="$t('project_project_name')"/>
            </BInputGroup>
        </div>
    </BModal>
    <div  class="toast-container position-fixed p-3 bottom-0 end-0">
        <BToast v-model="success" variant="success">
            <template #title>
                {{ $t('project_timelines.messages.changes_saved_success') }}
            </template>
            {{ $t('project_timelines.messages.save_success') }}
        </BToast>
        <BToast v-model="error" variant="danger">
            <template #title>
                {{ $t('project_timelines.messages.save_failed') }}
            </template>
            {{ error_message }}
        </BToast>
    </div>
</template>


<script>
import {
    BModal,
    BButton,
    BInputGroup,
    BInputGroupText,
    BFormInput,
    BToast,
    BSpinner
} from "bootstrap-vue-next";
import Api from "@/Api";
import error from "./Error.vue";
import {mapActions, mapGetters} from "vuex";
import themeMixin from "@/mixins/ThemeMixin";
export default {
    mixins: ['themeMixin'],
    name: "RenameModal",
    computed: {
        error() {
            return error
        }
    },
    components: {
        BModal,
        BButton,
        BInputGroup,
        BInputGroupText,
        BFormInput,
        BToast,
        BSpinner
    },
    data() {
        return {
            modal: false,
            success: false,
            error: false,
            error_message: null,
            loading: false,
            state: null,
            name: '',
            project_name: null,
        }
    },
    methods: {
        ...mapActions(['getMeta']),
        openModal() {
            this.state = null;
            this.loading = false;
            this.modal = true;
            this.getProjectName();
        },
        closeModal() {
            this.modal = false;
        },
        getProjectName() {
          let project = this.$store.getters.projectById(this.$route.params.id);
            this.project_name = project.name;
        },
        updateSetting(name) {
            this.loading = true;
            this.success = false;
            this.error = false;
            let value = this[name.replace('.', '_')];
            if (value === '') {
                return;
            }
            Api.setProjectSetting(this.$route.params.id, name, value)
                .then(response => {
                    this.state = true;
                    this.loading = false;
                    this.success = true;
                    this.error_message = null;
                    setTimeout(() => {
                        this.state = null;
                    }, 1000);
                    setTimeout(() => {
                        this.success = false;
                    }, 3000);
                    this.getMeta();
                })
                .catch(error => {

                    this.state = false;
                    this.loading = false;
                    this.error_message = error.response.data.message;
                    this.error = true;
                    setTimeout(() => {
                        this.state = null;
                        this.error = false;
                        this.error_message = null;
                        this.error = false;
                    }, 3000);
                });
        }
    },
}
</script>


<style scoped>

</style>
