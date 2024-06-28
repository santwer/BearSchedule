<script>

import VueCharts from 'vue-chartjs'
import { Bar, Line } from 'vue-chartjs'


export default {
    extends: Bar,
    props: ['data', 'label'],
    methods: {
        getData: function () {
            return Object.values(this.data);
        },
        getLabels: function () {
            return Object.keys(this.data);
        },
    },
    mounted () {
        // Overwriting base render method with actual data.
        this.renderChart({
            labels: this.getLabels(),
            datasets: [
                {
                    label: this.label,
                    backgroundColor: '#f87979',
                    data: this.getData()
                }
            ]
        })
    }
}
</script>

<style scoped>

</style>
