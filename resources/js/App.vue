<template>
    <Nav/>
    <router-view :key="$route.path" />
    <div id="modals" class="relative z-20"></div>
</template>

<script setup>
import Nav from "./components/Nav.vue";
</script>
