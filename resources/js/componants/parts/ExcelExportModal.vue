<template>
    <BModal
        :title="$t('project_excel') + ' ' + project_name"
        v-model="modal"
        :ok-title="$t('general.close')"
        :ok-only="true"
        :ok-variant="isDark ? 'secondary' : 'secondary'"
        v-b-modal.modal-center>
        <div class="form-group pb-2">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="start">{{ $t('project_timeline_tables.columns.start') }}</label>
                        <input type="date" class="form-control" :max="export_end" v-model="export_start">
                    </div>
                </div>
                <div class="col">
                    <!-- input for end date -->
                    <div class="form-group">
                        <label for="end">{{ $t('project_timeline_tables.columns.end') }}</label>
                        <input type="date" class="form-control"  :min="export_start" v-model="export_end">
                    </div>
                </div>
            </div>



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
import moment from "moment";
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
            export_start: null,
            export_end: null,
        }
    },
    methods: {
        ...mapActions(['getMeta']),
        openModal() {
            this.state = null;
            this.loading = false;
            this.modal = true;
            this.getProjectName();
            this.setExportDates();
        },
        setExportDates() {
            let itemRange = this.$parent.getItemRange();
            let start = moment(itemRange.start).format('YYYY-MM-DD');
            let end = moment(itemRange.end).format('YYYY-MM-DD');


            this.export_start = start;
            this.export_end = end;

        },
        getProjectName() {
            let project = this.$store.getters.projectById(this.$route.params.id);
            this.project_name = project.name;
        },
        exportExcel() {
            let url = '/api/timeline/' + this.$route.params.id + '/excel';
            url += '?start=' + this.export_start + '&end=' + this.export_end;

           window.open(url);
           this.modal = false;

        }
    }
}
</script>



<style scoped>

</style>
