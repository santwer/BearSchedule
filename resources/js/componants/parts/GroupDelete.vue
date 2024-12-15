<template>
    <BModal
        :title="$t('delete group')"
        v-model="modal"
        :ok-title="$t('general.delete')"
        :cancel-title="$t('general.cancel')"
        :ok-only="false"
        ok-variant="danger"
        :cancel-variant="isDark ? 'secondary' : 'secondary'"
        v-on:ok="deleteItem"
        v-b-modal.modal-center>
        <div class="form-group pb-2">
            <label>{{ $t('project_timelines.messages.confirm_delete_group') }}</label>
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
    name: "GroupDelete",
    mixins: ['themeMixin'],
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
        openModal(id) {
            this.state = null;
            this.loading = false;
            this.modal = true;
            this.id = id;
        },
        closeModal() {
            this.modal = false;
        },
        deleteItem() {
            this.$emit('delete', this.id)
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
