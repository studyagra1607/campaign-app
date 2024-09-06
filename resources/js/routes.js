import { createWebHistory, createRouter } from "vue-router";

const routes = [
    {
        path: '/',
        name: 'visitor',
        component: () => import('@/views/layout/visitor.vue'),
        redirect: {name: 'index'},
        children: [
            {
                path: '/',
                name: 'index',
                component: () => import('@/views/pages/index.vue'),
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