<template>
    <form action="">
        <div class="modal-card" style="width: 400px;">
            <header class="modal-card-head">
                <p class="modal-card-title">Item</p>
                <button
                    type="button"
                    class="delete"
                    @click="$emit('close')"/>
            </header>
            <section class="modal-card-body">
                <b-field label="Title">
                    <b-input
                        type="text"
                        :value="item.title"
                        placeholder="Title"
                        required>
                    </b-input>
                </b-field>
                <b-field label="Content">
                    <b-input
                        type="text"
                        :value="item.content"
                        placeholder="Title">
                    </b-input>
                </b-field>
                <b-field label="Start">
                    <b-datepicker
                        placeholder="Click to select..."
                        v-model="item.start"
                        icon="calendar-today"
                        trap-focus>
                    </b-datepicker>
                </b-field>
                <b-field label="End">
                    <b-datepicker
                        placeholder="Click to select..."
                        v-model="item.end"
                        icon="calendar-today"
                        trap-focus>
                    </b-datepicker>
                </b-field>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$emit('close')">Close</button>
                <button class="button is-primary">Save</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        name: "TimelineItemModelForm",
        props: ['setItem'],
        data() {
            return {
                item: {
                    title: '',
                    content: '',
                    start: null,
                    end: null,
                    group: '',
                }
            }
        },
        mounted() {
            this.item = this.setItem;
            if(typeof this.setItem !== "undefined") {
                this.item.start = this.setItem.start === '' ? '' : new Date(Date.parse(this.setItem.start));
            } else {
                this.item.start = null;
            }
            if(typeof this.setItem !== "undefined") {
                this.item.end = this.setItem.end === '' ? '' : new Date(Date.parse(this.setItem.end));
            } else {
                this.item.end = null;
            }
        }
    }
</script>

<style scoped>

</style>
