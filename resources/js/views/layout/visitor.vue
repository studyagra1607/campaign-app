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
        
        <!-- <div class="fixed left-10 bottom-10 rounded-md bg-gray-900 text-gray-50 w-[12rem]">
            <div class="px-4 py-3">
                <div class="flex flex-wrap items-center gap-3 mb-1">
                    <i class="pi pi-spin pi-spinner text-2xl"></i>
                    <span>1000...r.csv</span>
                </div>
                <div>
                    <div class="flex items-center justify-between text-[.6rem] mb-1">
                        <span>
                            Verifying.....
                        </span>
                        <span>
                            2000000/2000000
                        </span>
                    </div>
                    <ProgressBar :value="40" :showValue="false" class="bg-slate-600 h-[.1rem]"
                    :pt="{
                        value: 'bg-gray-100'
                    }"
                    ></ProgressBar>
                </div>
            </div>
        </div> -->
        
        <!-- <i class="pi pi-chevron-down cursor-pointer p-2 pe-0 ms-3" v-styleclass="{ selector: '.box-1', leaveActiveClass: 'animate-slideup', toggleClass: 'animate-slidedown' }"></i>
        <div class="flex flex-col items-center">
            <div>
                <Button v-styleclass="{ selector: '.box2', enterFromClass: 'hidden', enterActiveClass: 'animate-slidedown' }" label="SlideDown" class="mr-2" />
                <Button v-styleclass="{ selector: '.box2', leaveActiveClass: 'animate-slideup', leaveToClass: 'hidden' }" label="SlideUp" severity="secondary" />
            </div>
            <div class="h-32">
                <div class="hidden animate-duration-500 box2 overflow-hidden">
                    <div class="flex bg-primary text-primary-contrast items-center justify-center py-4 rounded-md mt-4 font-bold w-32 h-32">Content</div>
                </div>
            </div>
        </div> -->
        
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