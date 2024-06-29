import {createRouter, createWebHistory} from 'vue-router';

export default createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/:locale',
            name: 'home',
            component: () => import('./componants/pages/Home.vue')
        },
        {
            path: '/:locale/settings',
            name: 'settings',
            component: () => import('./componants/pages/Settings.vue')
        },
        {
            path: '/:locale/activity-log',
            name: 'activity-log',
            component: () => import('./componants/pages/ActivityLog.vue')
        },

        {
            path: '/:locale/project/:id',
            name: 'project',
            component: () => import('./componants/pages/ProjectHome.vue')
        },
    ],
})
