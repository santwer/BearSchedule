<template>
    <section>
        <div class="media" v-for="sel in selected">
            <div class="media-left">
                <img width="16" :src="`${sel.fields.issuetype.iconUrl}`">
            </div>
            <div class="media-content">
                <a :href="getIssueUrl(sel.key)" target="_blank">{{ sel.key }}</a>
                <b-icon icon="delete" size="is-small" class="delete-issue" v-on:click.native="deleteIssue(sel)" ></b-icon>
                <span class="jira-status">{{ sel.fields.status.name }}</span>
                <br>
                <small class="smallstyle">
                    {{ sel.fields.summary }}
                </small>
            </div>
        </div>
        <b-field label="Jira Issue">
            <b-autocomplete
                :data="data"
                placeholder="BEAR-12"
                field="title"
                :loading="isFetching"
                @typing="getAsyncData"
                @select="addSelected">

                <template slot-scope="props">
                    <div class="media">
                        <div class="media-left">
                            <img width="16" :src="`${props.option.fields.issuetype.iconUrl}`">
                        </div>
                        <div class="media-content">
                            {{ props.option.key }}
                            <span class="jira-status">{{ props.option.fields.status.name }}</span>
                            <br>
                            <small class="smallstyle">
                                {{ props.option.fields.summary }}
                            </small>
                        </div>
                    </div>
                </template>
            </b-autocomplete>
        </b-field>
    </section>
</template>

<script>
import debounce from 'lodash/debounce'

export default {
    name: "IssueSearch",
    props: ['selected'],
    data() {
        return {
            data: [],
            isFetching: false
        }
    },
    methods: {
        triggerNewIssue(issue) {
            this.$parent.$parent.$parent.newIssueSet(issue);
        },
        getIssues() {
            return this.selected;
        },
        addSelected(option) {
            if(this.selected.filter(x => x.key === option.key).length > 0)
                return;
            this.selected.push(option);
            if(this.selected.length === 1) {
                this.triggerNewIssue(option);
            }
        },
        deleteIssue(issue) {
            this.$parent.$parent.$parent.deleteIssue(issue);

        },
        getProjectId() {
           var item = this.$parent.$parent.$parent.getItem();
           return item.project_id;
        },
        getIssueUrl(issueKey) {
            return '/jira/issue/' + this.getProjectId() + '/' + issueKey;
        },

        getAsyncData: debounce(function (name) {
            if (!name.length) {
                this.data = []
                return
            }
            this.isFetching = true
            var project = this.getProjectId();
            $.get(`/ajax/jira/issue/search?project=${project}&query=${name}`, (data) => {
                this.data = []
                data.issues.forEach((item) => this.data.push(item));
            }, 'json') .fail((error) => {
                this.data = []
                throw error
            }).always(() => {
                this.isFetching = false
            });

        }, 500)
    }
}
</script>

<style scoped>
    .smallstyle {
        width: 280px;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .jira-status {
        float: right;
        padding: 1px 5px;
        background-color: lightgray;
        border-radius: 5px;
        font-size: 80%;
    }
    .delete-issue {
        float:right;
        cursor: pointer;
        padding: 10px;
    }
    .delete-issue:hover {
        color: #1870c7;
    }
</style>
