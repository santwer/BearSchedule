<template>
    <div>
        <div class="columns">
            <div class="column">
                <b-field label="Enable Series">
                    <b-switch v-model="item.is_series">
                        Startdate will be {{ item.start }}
                    </b-switch>
                </b-field>
            </div>
        </div>
        <div class="columns" v-if="item.is_series">
            <div class="column is-one-fifth">
                <div>
                    <b-radio v-model="item.series.type"
                             name="seriestype"
                             native-value="daily"> Daily
                    </b-radio>
                </div>
                <div>
                    <b-radio v-model="item.series.type"
                             name="seriestype"
                             native-value="weekly"> Weekly
                    </b-radio>
                </div>
                {{ item.series.type }}
            </div>
            <div class="column">
                <div v-if="item.series.type === 'daily'">
                    <b-field :label="getPeriodDesc('Days')">
                        <b-numberinput v-model="item.series.period"></b-numberinput>
                    </b-field>
                </div>
                <div v-if="item.series.type === 'weekly'">
                    <b-field :label="getPeriodDesc('Weeks')">
                        <b-numberinput v-model="item.series.period"></b-numberinput>
                    </b-field>
                    <div class="field">
                        <b-checkbox v-model="item.series.days[0]">Monday</b-checkbox>
                        <b-checkbox v-model="item.series.days[1]">Tuesday</b-checkbox>
                        <b-checkbox v-model="item.series.days[2]">Wednesday</b-checkbox>
                        <b-checkbox v-model="item.series.days[3]">Thursday</b-checkbox>
                        <b-checkbox v-model="item.series.days[4]">Friday</b-checkbox>
                        <b-checkbox v-model="item.series.days[5]">Saturday</b-checkbox>
                        <b-checkbox v-model="item.series.days[6]">Sunday</b-checkbox>
                    </div>

                </div>
                <div v-if="item.series.type === 'monthly'">
                    <b-field :label="getPeriodDesc('Months')">
                        <b-numberinput v-model="item.series.period"></b-numberinput>
                    </b-field>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "TimelineItemSeries",
    props: ['item'],
    data() {
        return {
            enableSeries: false,
            series: {
                type: null,
                period: null,
                days: {},
            }
        };
    },
    methods: {
        getPeriodDesc(str) {
            if(this.series.period === null) {
                return 'Every ... ' + str;
            }
            return 'Every ' + this.series.period + ' ' + str;
        }
    },
    mounted() {
        console.log(this.item.series)
        if(typeof this.item.series === "undefined") {
            this.item.series = this.series;
        }
        else if(typeof this.item.series.days === "undefined") {
            this.item.series.days = this.series.days;
        }
        console.log(this.item.series)
    }
}
</script>

<style scoped>

</style>
