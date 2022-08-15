<template>
    <div class="flex flex-col space-y-1 rounded shadow-lg p-8">
        <h1 class="text-5xl uppercase">{{task.title}}</h1>
        <p class="text-xl text-gray-400 italic">{{task.description}}</p>
        <div class="flex space-x-10">
            <p class="text-normal uppercase">Due Date: <span class="font-bold">{{task.due_date || '-'}}</span></p>
            <p class="text-normal uppercase">PRIORITY: <span class="font-bold"> {{task.priority}}</span></p>
        </div>
        <p class="text-normal uppercase">TAGS: <span v-for="(tag,index) in task.tags" :key="index"> #{{tag.name}} </span></p>
    </div>
</template>

<script setup>
    const props = defineProps({
        task: {
            type: Object,
            default: {}
        }
    })
</script>

