import {createRouter, createWebHistory} from 'vue-router';

export default createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/:locale',
            name: 'home',
            component: () => import('./componants/pages/Home.vue')
        },

    ],
})
