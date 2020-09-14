<template>
    <section v-if="!isDeleted">
        <div v-if="inEdit" class="editer">
            <div class="columns">
                <div class="column">
            <b-field>
                <b-input placeholder="Title"
                         type="text"
                         v-model="item.title"
                         icon="tag-text"
                >
                </b-input>
            </b-field>
            </div>
                <div class="column">
            <b-field>
                <b-input placeholder="URL"
                         type="search"
                         icon="link-variant"
                         icon-clickable
                         v-model="item.href"
                         @icon-click="goToUrl">
                </b-input>
            </b-field>
                </div>
                <div class="is-50">
            <b-button @click="mainClick" icon-left="content-save-outline"></b-button>
            <b-button @click="deleteItem" icon-left="delete"></b-button>
                </div>
            </div>
        </div>
        <b-button v-if="!inEdit" type="is-success is-light" @click="mainClick" expanded>{{item.title}}</b-button>

    </section>
</template>

<script>
    export default {
        name: "LinkButton",
        props: ['editmode', 'item'],
        data() {
            return {
                inEdit: false,
                isDeleted: false,
            }
        },
        methods: {
            mainClick: function () {
                if(this.editmode === 'true') {
                    this.inEdit = !this.inEdit;
                    return;
                } else {
                    this.goToUrl();
                }
            },
            goToUrl: function () {
                window.open(this.item.href);
            },
            deleteItem: function () {
                this.item.title = "";
                this.item.href = "";
                this.isDeleted = true;
            }
        },
        mounted() {
            if(this.item.title === "" && this.item.href === "") {
                this.inEdit = true;
            }
        }
    }
</script>

<style scoped>
    section {
        width: 100%
    }
    .editer {
        border: 1px solid #efefef;
        padding: 5px 0 3px 0;
    }
    .is-50 {
        width: 50px;
        padding-top: 10px;
    }
</style>
