<template>
    <div class="grid grid-cols-1 gap-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
        <div class="cursor-pointer p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md w-72" v-for="(task, index) in tasks.data" :key="index">
            <div class="flex justify-end items-center">
                <p
                    class="text-gray-400 mt-2 text-sm  rounded py-1 w-fit font-bold uppercase"
                    :class="task.completed_at == null ?'text-gray-500' : 'text-green-500'"
                >{{task.completed_at != null ? "Completed" : "Todo"}}</p>
            </div>
            <div class="flex justify-between items-center">
                <a href="#">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{task.title}}</h5>
                </a>
                <p class="mb-2 font-bold tracking-tight text-gray-500 text-xs">{{task.priority}}</p>
            </div>

            <p class="mb-3 font-normal text-gray-400 ">{{task.description}}</p>

            <p class="text-gray-400 my-1 text-xs bg-gray-400 text-gray-50 rounded px-2 py-1 w-fit">{{task.due_date}}</p>
            <template v-if="task.tags.length > 0">
                <div class="flex space-x-1">
                    <p class="text-gray-400 my-1 text-xs bg-gray-400 text-gray-50 rounded px-2 py-1 w-fit" v-for="(tag,index) in task.tags" :key="index">#{{tag.name}}</p>
                </div>
            </template>
            <template v-if="task.attachments.length > 0">
                <div class="flex space-x-1 my-1">
                    <template   v-for="(attachment,index) in task.attachments" :key="index">
                        <img
                            class="w-10 h-10 rounded-full"
                            :src="`/files/${attachment.name}`"

                            v-if="['svg','png','jpg'].includes(attachment.name.split('.').pop())"
                        />
                        <p v-else class="text-xs text-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>{{attachment.name.substring(0,5) + '.' +attachment.name.split('.').pop()}}</p>
                    </template>
                </div>
            </template>



            <router-link :to="{name: 'TaskDetail', params: {id: task.id}}" class="underline font-semibold text-sm mb-3">See more</router-link>
            <hr class="my-2">
            <p class="text-xs font-bold">Mark as:</p>
            <div class="flex space-x-1">
                <template v-if="task.completed_at == null && task.archived_at == null">
                        <button
                            @click="completeTask(task.id, 2)"
                            class="items-center my-1 py-1 px-3 text-xs text-center text-white bg-green-500 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">Complete</button>
                        <button
                            @click="completeTask(task.id, 1)"
                            v-if="task.status !== 1"
                            class="items-center my-1 py-1 px-3 text-xs text-center text-white bg-green-500 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">Todo</button>
                        <button
                            @click="archivedTask(task.id)"
                            class="items-center my-1 py-1 px-3 text-xs text-center text-white bg-gray-500 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300"
                        >
                            Archive
                        </button>
                </template>
                <template v-if="task.archived_at != null">
                    <button
                        @click="archivedTask(task.id, 'restore')"
                        class="items-center my-1 py-1 px-3 text-xs text-center text-white bg-green-500 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300"
                    >
                        Restore
                    </button>
                </template>
            </div>

        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        tasks: {
            type: Object,
            default: []
        }
    })

    const completeTask = async (id, status) => {
        await axios.put(`/tasks/update-status/${id}`, {status: status})
        const path = status == 2 ? '/completed-task' : '/todo-task'
        emit('reload', path)
    }

    const archivedTask = async (id, status = 'archived') => {
        if (confirm(`Are you sure, you want to ${status} this tasks?`)) {
            await axios.put(`/tasks/toggle-archived/${id}`, {status: status})
            const path = status === 'archived' ? '/archived-task' : '/';
            emit('reload', path)
        }
    }
    const emit = defineEmits(['reload']);

</script>

<style scoped>

</style>
