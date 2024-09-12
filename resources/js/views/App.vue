<template>

    <div class="main-container">

        <router-view></router-view>

    </div>

</template>

<script setup>
import { useAuthStore } from '@/stores/authStore';
const authStore = useAuthStore();
window.onbeforeunload = authStore.checkAuthentication();
</script>

<style scoped>

</style>