
<template>
    <div style="min-height: 200px;" v-if="loading">
    <BSpinner class="loader" label="Large Spinner" v-if="loading"/>
    </div>
</template>

<script>
import {BSpinner} from "bootstrap-vue-next";
export default {
    components: {
        BSpinner
    },
    props: {
        show: {
            type: Boolean,
            default: true
        }
    },
    setup() {

    },
    data() {
        return {
            loading: this.show,
        };
    },
    methods: {
    }
}
</script>

<style scoped>

.loader {
    width: 8rem;
    height: 8rem;
    position: absolute;
    left: 50%;
    top: 50%;
    margin-top: -4rem;
    margin-left: -4rem;
}

</style>
