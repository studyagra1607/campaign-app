<template>

    <Toast class="whitespace-normal inline-block w-auto min-w-[240px] max-w-[320px] z-[9999]" id="toastContainer">
        <template #container="{ message, closeCallback }">
            <div class="flex items-center justify-between px-3 py-3">
                <div class="flex gap-2">
                    <i class="pi pi-check mt-1"></i>
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
    
    <form method="POST" action="/logout" v-if="authStore.isAuthenticated">
        <input type="hidden" name="_token" :value="authStore.csrf_token" autocomplete="off">
        <Button type="submit" icon="pi pi-exclamation-circle" class="fixed z-10 right-10 bottom-9" />
    </form>
    
    <div class="logo">
        <router-link :to="{name : 'campaign'}">
            {{ $env?.VITE_APP_NAME }}
        </router-link>
    </div>

    <main>

        <SideMenu />

        <router-view></router-view>
        
    </main>

</template>

<script setup>
import { useAuthStore } from '@/stores/authStore';
import SideMenu from '@/views/inc/SideMenu.vue';
const authStore = useAuthStore();

const pusher = Echo.connector.pusher;
const channel = pusher.subscribe(`user.${$userId}`);
channel.bind_global(async (event, data) => {
    if(data?.data?.msg && data?.data?.type){
        staticToast({msg: data.data.msg, severity: data.data.type});
    };
});
</script>

<style scoped>

</style>