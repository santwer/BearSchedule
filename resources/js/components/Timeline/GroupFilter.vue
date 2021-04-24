<template>
    <div class="is-float-right">
        <b-dropdown append-to-body aria-role="menu" position="is-bottom-left" trap-focus>

            <button class="button" slot="trigger">
                <b-icon icon="filter" size="is-small" ></b-icon>
                <span>{{ trans.get('project.groups') }}</span>
                <b-icon :icon="activeMenu ? 'menu-up' : 'menu-down'"></b-icon>
            </button>

            <b-dropdown-item
                aria-role="menu-item"
                :focusable="false"
                custom>
                <h5 class="subtitle is-6">{{ trans.get('project.filter_groups') }}</h5>
                <div v-for="option in options" class="field-dp">
                    <b-checkbox v-model="option.visible"
                                true-value="1"
                                :false-value="falseValue">{{ option.content }}</b-checkbox>
                </div>

            </b-dropdown-item>
        </b-dropdown>
    </div>
</template>

<script>
    export default {
        name: "GroupFilter",
        data() {
            return {
                activeMenu: false,
                options: [],
                falseValue: false,
            }
        },
        mounted() {
            this.options = this.$parent.groups;
        }
    }
</script>

<style scoped>
    .field-dp {
        margin-bottom: 5px;
    }
    .is-float-right {
        margin-left: auto;
        margin-right: 12px;
        padding-right: 10px;
        border-right: 1px solid #dbdbdb;
    }
</style>
