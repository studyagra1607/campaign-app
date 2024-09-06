import { createWebHistory, createRouter } from "vue-router";

const routes = [
    {
        path: '/',
        name: 'visitor',
        component: () => import('@/views/layout/visitor.vue'),
        redirect: {name: 'campaigns'},
        children: [
            {
                path: '/',
                name: 'campaigns',
                component: () => import('@/views/pages/campaigns/index.vue'),
            },
            {
                path: '/lists',
                name: 'lists',
                component: () => import('@/views/pages/lists/index.vue'),
            },
            {
                path: '/templates',
                name: 'templates',
                component: () => import('@/views/pages/templates/index.vue'),
            },
            {
                path: '/notifications',
                name: 'notifications',
                component: () => import('@/views/pages/notifications/index.vue'),
            },
        ],
    },
    {
        path: '/:pathMatch(.*)*',
        name: '404',
        component: () => import('@/views/errors/404.vue'),
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes
});


export default router;