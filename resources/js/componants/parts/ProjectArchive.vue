<template>
    <BModal
        :title="$t('general.Archive_project')"
        v-model="modal"
        :ok-title="isArchived ? $t('get to archive') : $t('general.do_archive')"
        :cancel-title="$t('general.cancel')"
        :ok-only="false"
        :ok-variant="isArchived ? 'primary' : 'info'"
        :cancel-variant="isDark ? 'secondary' : 'secondary'"
        v-on:ok="archiveItem"
        v-b-modal.modal-center>
        <div class="form-group pb-2">
            <label v-if="isArchived">{{ $t('project_timelines.messages.confirm_unarchive_project') }}</label>
            <label v-else>{{ $t('project_timelines.messages.confirm_archive_project') }}</label>
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
import error from "@/componants/parts/Error.vue";
import {BButton, BFormInput, BInputGroup, BInputGroupText, BModal, BSpinner, BToast} from "bootstrap-vue-next";

export default {
    name: "ProjectArchive",
    mixins: ['themeMixin'],
    props: {
        isArchived: {
            type: Boolean,
            default: false
        }
    },
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
            id: null,
        }
    },
    methods: {
        openModal() {
            this.state = null;
            this.loading = false;
            this.modal = true;
        },
        closeModal() {
            this.modal = false;
        },
        archiveItem() {
            this.$emit('archive')
        }
    },
    watch: {
        modal() {
            if (!this.modal) {
                this.$nextTick(() =>  {
                    this.id = null;
                    this.$emit('close');
                });

            }
        }
    }
}
</script>



<style scoped>

</style>
