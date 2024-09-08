import { createWebHistory, createRouter } from "vue-router";
import { tokenKey } from "@/constants/authConfig";
import { useAuthStore } from '@/stores/authStore';

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
                meta: { requiresAuth: true },
            },
            {
                path: '/lists',
                name: 'lists',
                component: () => import('@/views/pages/lists/index.vue'),
                meta: { requiresAuth: true },
            },
            {
                path: '/templates',
                name: 'templates',
                component: () => import('@/views/pages/templates/index.vue'),
                meta: { requiresAuth: true },
            },
            {
                path: '/notifications',
                name: 'notifications',
                component: () => import('@/views/pages/notifications/index.vue'),
                meta: { requiresAuth: true },
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

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    try {

        if(!authStore.isAuthenticated){
            await authStore.checkAuthentication();
        };
        
        if (to.meta.requiresAuth && !authStore.isAuthenticated) {
            authStore.logout('unauthorized');
        } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
            next({name: 'visitor'});
        } else {
            next();
        };
        
    } catch (error) {
        next({name: '404'});
    }
});

export default router;