<template>
    <div class="modal-card" style="width: 400px;">
        <header class="modal-card-head">
            <p class="modal-card-title">{{ item.title }}</p>
            <button
                type="button"
                class="delete"
                @click="$emit('close')"/>
        </header>
        <section class="modal-card-body">
            <article class="media">
                <div class="media-content">
                    <div class="content">
                        <p>
                            <strong>{{ item.subtitle }}</strong>  <small>{{  displayDate(item.start) }}</small> <small v-if="item.type === 'range'">- {{ displayDate(item.end) }}</small>

                            <br><span v-if="item.status !== 'DEFAULT'">Status: {{(item.status).toLowerCase() }}</span><br>
                            <span v-html="getHtmlDesc(item.description)">{{ item.description }}</span>
                            </p>
                        <div v-for="link in item.links" style="width: 100%;">
                            <link-button editmode="false" :item="link"></link-button>
                        </div>
                    </div>
                    <nav class="level is-mobile">
                        <div class="level-left">
                            <a class="level-item">
                                <span class="icon is-small"><i class="fas fa-reply"></i></span>
                            </a>
                            <a class="level-item">
                                <span class="icon is-small"><i class="fas fa-retweet"></i></span>
                            </a>
                            <a class="level-item">
                                <span class="icon is-small"><i class="fas fa-heart"></i></span>
                            </a>
                        </div>
                    </nav>
                </div>
            </article>
        </section>
        <footer class="modal-card-foot">
            <button class="button" type="button" @click="$emit('close')">{{ trans.get('general.close') }}</button>
        </footer>
    </div>

</template>

<script>
    import LinkButton from "../tools/LinkButton";
    export default {
        components: {LinkButton},
        props: ['item'],
        name: "TimelineItemShowModel",
        methods: {
           displayDate: function (input) {
               const options = { year: 'numeric', month: 'short', day: 'numeric' };
               var date = new Date(input);
               return date.toLocaleDateString(Vue.prototype.trans.getLocale(), options);
           },
           getHtmlDesc: function (desc) {
               if(typeof desc === "undefined") return desc;
               return desc.replace(/(?:\r\n|\r|\n)/g, '<br>');
           }
        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>
