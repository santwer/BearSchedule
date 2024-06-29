<template>
<BAlert :model-value="error !== null" variant="danger" dismissible>

    <h6 class="alert-heading"> {{ error.message }}</h6>

    <ul v-for="errortyp in error.errors">
        <li v-for="err in errortyp" :key="err">
            {{ err }}
        </li>
    </ul>
</BAlert>
</template>

<script>
import {BAlert} from "bootstrap-vue-next";
export default {
components: {BAlert},
    props: {
        error: {
            type: Object,
            default: null
        }
    },

}
</script>

<style scoped>

</style>
