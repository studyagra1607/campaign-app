<template>
    <div class="sidemenu">
        <router-link :to="{name : 'campaign'}" :class="{ active: isActiveNav(['campaign']) }" class="link-btn btn-outline w-full">Campaign</router-link>
        <router-link :to="{name : 'category'}" :class="{ active: isActiveNav(['category']) }" class="link-btn btn-outline w-full">Category</router-link>
        <router-link :to="{name : 'emails'}" :class="{ active: isActiveNav(['emails']) }" class="link-btn btn-outline w-full">Emails</router-link>
        <router-link :to="{name : 'template'}" :class="{ active: isActiveNav(['template']) }" class="link-btn btn-outline w-full">Tempate</router-link>
        <router-link :to="{name : 'media'}" :class="{ active: isActiveNav(['media']) }" class="link-btn btn-outline w-full">Media</router-link>
        <OverlayBadge :value="notifications_count" size="small">
            <router-link :to="{name : 'notification'}" :class="{ active: isActiveNav(['notification']) }" class="link-btn btn-outline w-full">Notification</router-link>
        </OverlayBadge>
        <a href="/profile" class="link-btn btn-outline w-full">Settings</a>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { isActiveNav } from '@/services/helpers';
import useNotification from "@/services/NotificationService";

const { notifications_count, notificationsCount } = useNotification();

onMounted(async () => {
    await notificationsCount();
});

const pusher = Echo.connector.pusher;
const channel = pusher.subscribe(`user.${$userId}`);
channel.bind_global(async (event, data) => {
    await notificationsCount();
});
</script>

<style scoped>

</style>