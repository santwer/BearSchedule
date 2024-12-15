<template>
    <BModal

        title="Group"
        v-model="modal"
        v-b-modal.modal-center>
        <loading v-if="loading"></loading>
        <div v-else>
            <error :error="error"></error>
            <div class="form-group">
                <label for="content">{{ $t('project_timeline_tables.columns.title') }}</label>
                <input type="text" class="form-control" v-model="item.content" :disabled="!editable">
            </div>
            <div class="form-group">
                <BFormCheckbox
                    id="checkbox-1"
                    v-model="item.show_share"
                    name="checkbox-1"
                    :value="true"
                    :unchecked-value="false"
                    :disabled="!editable"
                >
                    {{ $t('project_timelines.group.show_in_share') }}
                </BFormCheckbox>
            </div>
            <div class="form-group">
                <label for="color">{{ $t('project_timelines.group.group') }}</label>
                <select class="form-select" v-model="item.parent" :disabled="!editable">
                    <option :value="null">{{ $t('project_timelines.item.no_group') }}</option>
                    <option v-for="group in groups" :value="group.id">
                        {{ group.content }}
                    </option>
                </select>


            </div>
        </div>
        <template v-slot:footer="{ ok, cancel, hide }">
            <div class="col">
                <BButtonGroup>
                    <BButton variant="secondary" class="mr-2" @click="cancel">{{ $t('general.close') }}</BButton>
                    <BButton variant="danger" @click="openDelete" v-if="editable">{{ $t('general.delete') }}</BButton>
                </BButtonGroup>
            </div>
            <div class="col ">
                <BButton variant="primary" class="float-end" @click="save" v-if="editable">{{ $t('general.save') }}</BButton>
            </div>

        </template>
    </BModal>
</template>

<script>
import {BModal, BButton, BFormCheckbox, BButtonGroup} from "bootstrap-vue-next";
import Api from "@/Api";
import Error from "@/componants/parts/Error.vue";
import Loading from "@/componants/parts/Loading.vue";

export default {
    components: {
        Loading,
        Error,
        BModal, BButton, BFormCheckbox, BButtonGroup
    },
    props: {
        groups: {
            type: Array,
            default: () => []
        },
        editable: {
            type: Boolean,
            default: false,
        }

    },
    data() {
        return {
            item: {},
            modal: false,
            loading: false,
            error: null,
            colors: []
        }
    },
    methods: {
        newItem() {
            return {
                title: null,
                content: '',
                parent: null,
                project_id: this.$route.params.id,
                show_share: true,
                visible: true,
                id: null,
                start: null,
                end: null,
            };
        },
        showModal() {
            this.item = this.newItem();
            this.modal = true;
        },
        openGroup(item) {
            this.item = Object.assign({}, item);
            console.log('here', this.item)
            this.modal = true;
        },
        openDelete() {
            this.modal =false;
            this.$emit('delete', this.item.id);
        },
        save() {
            this.loading = true;

            Api.setGroup(this.item).then(response => {
                this.modal = false;
                this.$emit('save', response.data.data);
            }).catch(response => {
                this.loading = false;
                this.error = response.response.data;
            }).finally(() => {
                this.loading = false;
            });

        },
    }

}

</script>

<style scoped>

</style>
