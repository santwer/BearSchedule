<template>
    <BModal
        :title="$t('project_excel')"
        v-model="modal"
        :ok-title="$t('general.close')"
        :ok-only="true"
        :ok-variant="isDark ? 'secondary' : 'secondary'"
        v-b-modal.modal-center>
        <div class="form-group pb-2">
            <label>{{ project_name }}</label>


        </div>
        <template v-slot:footer="{ ok, cancel, hide }">
            <div class="col">
                <BButton variant="secondary" class="mr-2" @click="cancel">{{ $t('general.close') }}</BButton>
            </div>
            <div class="col ">
                <BButton variant="primary" class="float-end" @click="exportExcel">{{ $t('export') }}</BButton>
            </div>

        </template>
    </BModal>
</template>

<script>
import error from "@/componants/parts/Error.vue";
import {BButton, BFormInput, BInputGroup, BInputGroupText, BModal, BSpinner, BToast} from "bootstrap-vue-next";
import {mapActions} from "vuex";
import Api from "@/Api";
export default {
    name: "ExcelExportModal",
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
        getProjectName() {
            let project = this.$store.getters.projectById(this.$route.params.id);
            this.project_name = project.name;
        },
        exportExcel() {
           console.log('export excel');
           Api.generateExcel(this.$route.params.id).then(response => {
               console.log(response);
           }).catch(error => {
               console.log(error);
           });
        }
    }
}
</script>



<style scoped>

</style>
