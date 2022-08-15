<template>
    <div class="text-white px-4 py-2 border-0 rounded relative mb-4 bg-rose-400 text-xs">
        <span class="inline-block align-middle mr-8">{{message}}</span>
    </div>
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
