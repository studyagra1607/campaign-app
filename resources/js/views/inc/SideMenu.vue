<template>
    <div class="sidemenu">
        <router-link :to="{name : 'campaigns'}" :class="{ active: isActiveNav(['campaigns']) }" class="link-btn btn-outline w-full">Home</router-link>
        <router-link :to="{name : 'lists'}" :class="{ active: isActiveNav(['lists']) }" class="link-btn btn-outline w-full">Lists</router-link>
        <router-link :to="{name : 'templates'}" :class="{ active: isActiveNav(['templates']) }" class="link-btn btn-outline w-full">Tempates</router-link>
        <OverlayBadge value="4" size="small">
            <router-link :to="{name : 'notifications'}" :class="{ active: isActiveNav(['notifications']) }" class="link-btn btn-outline w-full" badge="2">Notifications</router-link>
        </OverlayBadge>
        <a href="/profile" class="link-btn btn-outline w-full">Settings</a>
    </div>
</template>

<script setup>
import { isActiveNav } from '@/services/helpers';
</script>

<style scoped>

</style>