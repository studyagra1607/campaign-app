
import { createWebHistory, createRouter } from "vue-router";
import { tokenKey } from "@/constants/authConfig";
import { useAuthStore } from '@/stores/authStore';

const routes = [
    {
        path: '/',
        name: 'visitor',
        component: () => import('@/views/layout/visitor.vue'),
        redirect: {name: 'campaign'},
        children: [
            {
                path: '/',
                name: 'campaign',
                component: () => import('@/views/modules/campaign/index.vue'),
                meta: { requiresAuth: true },
            },
            {
                path: '/category',
                name: 'category',
                component: () => import('@/views/modules/category/index.vue'),
                meta: { requiresAuth: true },
            },
            {
                path: '/emails',
                name: 'emails',
                component: () => import('@/views/modules/emails/index.vue'),
                meta: { requiresAuth: true },
            },
            {
                path: '/template',
                name: 'template',
                component: () => import('@/views/modules/template/index.vue'),
                meta: { requiresAuth: true },
            },
            {
                path: '/media',
                name: 'media',
                component: () => import('@/views/modules/media/index.vue'),
                meta: { requiresAuth: true },
            },
            {
                path: '/notification',
                name: 'notification',
                component: () => import('@/views/modules/notification/index.vue'),
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