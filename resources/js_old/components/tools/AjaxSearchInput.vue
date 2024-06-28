<template>
    <b-field :label="headline">
        <b-autocomplete
            v-model="selectedItem"
            :data="searchResults"
            @typing="getAsyncData"
            @select="selected"
        ></b-autocomplete>
    </b-field>
</template>

<script>
    export default {
        name: "AjaxSearchInput",
        props: ['src', 'headline'],
        data() {
            return {
                searchResults: [],
                selectedItem: null
            }
        },
        methods:  {
            getAsyncData: function (name) {
                $.get(this.src, {search: name}, (data) => this.searchResults = data.data , 'json');
            },
            selected: function (e) {
                this.$emit('selectitem', e)
            }
        }
    }
</script>

<style scoped>

</style>
