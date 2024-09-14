<template>
    <BModal

        v-model="modal"
        size="lg"

        title="Item"
        v-on:ok="save"
        v-b-modal.modal-center>
        <loading v-if="loading"></loading>
        <div class="row" v-else>
            <div class="col-8">
                <error :error="error"></error>
                <!-- select for group -->
                <div class="form-group">
                    <label for="group">{{ $t('project_timelines.group.group') }}</label>
                    <select class="form-select" v-model="item.group">
                        <option :value="null" :disabled="true">{{ $t('please select') }}</option>
                        <option v-for="group in groups" :value="group.id">{{ group.content }}</option>
                    </select>
                </div>
                <!-- input for title -->
                <div class="form-group">
                    <label for="content">{{ $t('project_timeline_tables.columns.title') }}</label>
                    <input type="text" class="form-control" v-model="item.title">
                </div>
                <!-- input for subtitle -->
                <div class="form-group">
                    <label for="start">{{ $t('project_timeline_tables.columns.subtitle') }}</label>
                    <input type="text" class="form-control" v-model="item.subtitle">
                </div>
                <!-- textarea for content -->
                <div class="form-group">
                    <label for="start">{{ $t('project_timeline_tables.columns.content') }}</label>
                    <textarea class="form-control" v-model="item.content"></textarea>
                </div>

            </div>
            <div class="col-4">
                <!-- input for start date -->
                <div class="form-group">
                    <label for="start">{{ $t('project_timeline_tables.columns.start') }}</label>
                    <input type="date" class="form-control" v-model="item.start">
                </div>
                <!-- input for end date -->
                <div class="form-group">
                    <label for="end">{{ $t('project_timeline_tables.columns.end') }}</label>
                    <input type="date" class="form-control" v-model="item.end">
                </div>
                <!-- select for type -->
                <div class="form-group">
                    <label for="type">{{ $t('project_timeline_tables.columns.type') }}</label>
                    <select class="form-select" v-model="item.type">
                        <option value="box">{{ $t('project_timelines.item.types.box') }}</option>
                        <option value="point">{{ $t('project_timelines.item.types.point') }}</option>
                        <option value="range">{{ $t('project_timelines.item.types.range') }}</option>
                        <option value="background">{{ $t('project_timelines.item.types.background') }}</option>
                    </select>
                </div>
                <!-- select for Status -->
                <div class="form-group">
                    <label for="status">{{ $t('project_timelines.item.status') }}</label>
                    <select class="form-select" v-model="item.status">
                        <option value="DEFAULT">{{ $t('project_timelines.item.stati.DEFAULT') }}</option>
                        <option value="DELAYED">{{ $t('project_timelines.item.stati.DELAYED') }}</option>
                        <option value="CRITICAL">{{ $t('project_timelines.item.stati.CRITICAL') }}</option>
                        <option value="TEST">{{ $t('project_timelines.item.stati.TEST') }}</option>
                        <option value="DONE">{{ $t('project_timelines.item.stati.DONE') }}</option>

                    </select>
                </div>
                <!-- select for Color -->
                <div class="form-group">
                    <label for="color">{{ $t('project_timelines.item.color') }}</label>
                    <select class="form-select" v-model="item.color">
                        <option v-for="color in colors" :value="color">
                            {{ $t('project_timelines.item.colors.' + color.id) }}
                        </option>
                    </select>


                </div>
                </div>
            </div>


            <template v-slot:footer="{ ok, cancel, hide }">
                    <div class="col">
                        <BButtonGroup>
                        <BButton variant="secondary" class="mr-2" @click="cancel">Close</BButton>
                        <BButton variant="danger" @click="cancel">Delete</BButton>
                        </BButtonGroup>
                    </div>
                    <div class="col ">
                        <BButton variant="primary" class="float-end" @click="save">Save</BButton>
                    </div>

            </template>

    </BModal>
</template>

<script>
import {BModal, BButton,BButtonGroup} from "bootstrap-vue-next";
import Api from "@/Api";
import Error from "@/componants/parts/Error.vue";
import Loading from "@/componants/parts/Loading.vue";
export default {
    components: {
        Loading,
        Error,
        BModal, BButton,BButtonGroup
    },
    props: {
        groups: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            item: {},
            modal: false,
            loading: false,
            error: null,
            colors: [
                { id: 'default', name: 'Default', style: { backgroundColor: 'rgba(99, 164, 255, 0.4)' } },
                { id: '#d32f2f', name: 'Red', style: { backgroundColor:"#d32f2f" } },
                { id: '#c2185b', name: 'Pink', style: { backgroundColor:"#c2185b" } },
                { id: '#7b1fa2', name: 'Purple', style: { backgroundColor:"#7b1fa2" } },
                { id: '#512da8', name: 'Deep Purple', style: { backgroundColor:"#512da8" } },
                { id: '#303f9f', name: 'Indigo', style: { backgroundColor:"#303f9f" } },
                { id: '#1976d2', name: 'Blue', style: { backgroundColor:"#1976d2" } },
                { id: '#0288d1', name: 'Light Blue', style: { backgroundColor:"#0288d1" } },
                { id: '#0097a7', name: 'Cyan', style: { backgroundColor:"#0097a7" } },
                { id: '#00796b', name: 'Teal', style: { backgroundColor:"#00796b" } },
                { id: '#388e3c', name: 'Green', style: { backgroundColor:"#388e3c" } },
                { id: '#689f38', name: 'Light Green', style: { backgroundColor:"#689f38" } },
                { id: '#afb42b', name: 'Lime', style: { backgroundColor:"#afb42b" } },
                { id: '#fbc02d', name: 'Yellow', style: { backgroundColor:"#fbc02d" } },
                { id: '#ffa000', name: 'Amber', style: { backgroundColor:"#ffa000" } },
                { id: '#f57c00', name: 'Orange', style: { backgroundColor:"#f57c00" } },
                { id: '#e64a19', name: 'Deep Orange', style: { backgroundColor:"#e64a19" } },
                { id: '#9e9e9e', name: 'Grey', style: { backgroundColor:"#9e9e9e" } },
            ]
        }
    },
    methods: {
        newItem() {
            return {
                id: null,
                start: null,
                end: null,
                title: null,
                type: 'range',
                content: '',
                subtitle: '',
                group: null,
                project_id: this.$route.params.id,
                links: [],
                tags: [],
                status: 'DEFAULT',
                color: {},
            };
        },
        showModal() {
            this.item = this.newItem();
            this.modal = true;
        },
        openItem(item) {
            this.item = Object.assign({}, item);
            this.item.end = this.dateFromISO(this.item.end);
            this.item.start = this.dateFromISO(this.item.start);
            this.item.color = this.getCurrentColorByStyle();
            this.modal = true;
        },
        getCurrentColorByStyle() {
            if(typeof this.item.style === "undefined") {
                return this.colors[0];
            }
            var str = this.computeCss(this.item.style);
            if(typeof str['background-color'] != "undefined") {
                var bgColor = str['background-color'].substring(0, 7);
                var result = this.colors.filter(obj => {
                    return obj.id === bgColor
                });
                if(result.length > 0) {
                    return result[0];
                }
            }

            return this.colors[0];
        },
        computeCss: function (cssStr) {
            var cssEntries = [];
            var cssSplit = cssStr.split(';');
            cssSplit.forEach(function (element) {
                var parts = element.split(':');
                if(typeof parts[1] !== "undefined") {
                    cssEntries[parts[0]] = parts[1].trim();
                }
            });
            return cssEntries;
        },
        dateFromISO(isoDate) {
            if (null === isoDate) {
                return null;
            }
            let date = new Date(isoDate);
            return date.toISOString().slice(0,10);
        },
        save() {
            this.loading = true;
            Api.setItem(this.item).then(response => {
                this.$emit('save', response.data.data);
                this.modal = false;
            }).catch((response) => {
                this.error = response.response.data;
            }).finally(() => {
                this.loading = false;
            });
        }
    },
    computed: {}
}
</script>

<style scoped>

</style>
