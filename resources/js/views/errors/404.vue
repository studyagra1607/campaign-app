<template>
    <main class="max-w-[520px] max-h-[320px] flex flex-col items-center justify-center">
        <!-- <i class="pi pi-info-circle text-6xl"></i> -->
        <h1 class="text-6xl">404</h1>
        <p class="text-gray-900 text-xl font-normal mb-3">
            Page not found!
        </p>
        <router-link :to="'/'" class="link-btn btn-outline px-3">
            Go To Home
            <i class="pi pi-chevron-right"></i>
        </router-link>
    </main>
</template>

<script setup>

</script>

<style scoped>

</style>