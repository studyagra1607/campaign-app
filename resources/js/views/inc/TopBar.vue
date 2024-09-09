<template>
    <div class="h-14 flex items-center justify-between rounded-md bg-gray-50 p-2 mb-5">
        <Breadcrumb :home="home" :model="items" class="bg-transparent p-0 px-1">
            <template #item="{ item, props }">
                <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
                    <a :href="href" v-bind="props.action" @click="navigate">
                        <span :class="[item.icon, 'text-color']" />
                        <span class="text-primary font-semibold">{{ item.label }}</span>
                    </a>
                </router-link>
                <a v-else :href="item.url" :target="item.target" v-bind="props.action">
                    <span class="text-surface-700 dark:text-surface-0">{{ item.label }}</span>
                </a>
            </template>
        </Breadcrumb>
        <slot />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const home = ref({
    icon: 'pi pi-home',
    label: 'Home',
    route: '/'
});

const items = ref([
    {
        label: route.name.charAt(0).toUpperCase() + route.name.slice(1),
        route: route.path,
    },
]);

</script>

<style scoped>

</style>