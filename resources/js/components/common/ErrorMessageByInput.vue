<template>
    <small class="my-1 text-xs text-red-400 ml-2">{{message}}</small>
</template>

<script setup>
    const props = defineProps({
        message: {
            type: String,
            default: ''
        }
    })
</script>

<style scoped>

</style>
