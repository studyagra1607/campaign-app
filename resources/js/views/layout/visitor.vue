<template>

    <Toast class="inline-block w-auto min-w-[240px]">
        <template #container="{ message, closeCallback }">
            <div class="flex items-center justify-between px-3 py-3">
                <div>
                    <i class="pi pi-check me-2"></i>
                    <span>{{ message.summary }}</span>
                </div>
                <i class="pi pi-times-circle cursor-pointer ms-5" @click="closeCallback()"></i>
            </div>
        </template>
    </Toast>

    <ConfirmDialog >
        <template #container="{ acceptCallback, rejectCallback }">
            <div class="min-w-[420px] min-h-[265px] text-gray-900 flex flex-col items-center justify-center px-5 py-7">
                <i class="pi pi-exclamation-circle text-7xl mb-2"></i>
                <h4 class="text-3xl font-semibold mb-2">Are you sure?</h4>
                <p class="text-lg">You won't be able to revert this!</p>
                <div class="w-full flex justify-end gap-2 mt-8">
                    <Button label="Yes, delete it!" @click="acceptCallback"></Button>
                    <Button label="Cancel" outlined @click="rejectCallback"></Button>
                </div>
            </div>
        </template>
    </ConfirmDialog>
    
    <div class="logo">
        <router-link :to="{name : 'campaigns'}">
            {{ env.VITE_APP_NAME }}   
        </router-link>
    </div>

    <main>

        <SideMenu />

        <router-view :key="$route.fullPath"></router-view>
        
    </main>

</template>

<script setup>
import SideMenu from '@/views/inc/SideMenu.vue';
const env = import.meta.env;
</script>

<style scoped>

</style>