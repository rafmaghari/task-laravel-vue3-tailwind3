<template>
    <div class="mx-20 my-10 md:mx-64" v-if="state.task">
        <div class="flex justify-between my-2">
            <router-link to="/" class="underline">Back to List</router-link>
            <button @click="toggleEdit" class="underline" v-if="state.task.completed_at == null">{{ state.is_editing ? 'VIEW ONLY' : 'EDIT' }}</button>
        </div>
        <template v-if="!state.is_editing">
            <div class="flex justify-end" v-if="state.task.completed_at == null">
                <button
                    @click="state.deleteConfirmation = true"
                    class="items-center my-2 py-1 px-3 text-sm  text-center text-white bg-red-500 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300"
                >
                    DELETE
                </button>
            </div>
            <TaskDetailDisplay :task="state.task" />
        </template>
       <template v-else>
           <h2 class="my-1 text-xl font-bold">EDITING</h2>

           <ErrorMessageGeneral :message="state.errorMessage" v-if="state.errorMessage"/>
           <form @submit.prevent="submitHandler" method="POST">
               <div class="flex flex-col space-y-1">
                   <input type="text" placeholder="Title"
                          v-model="state.task.title"
                          class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                          :class="state.errors.title ? ' border border-red-200' : ''"
                   />
                   <ErrorMessageByInput :message="state.errors.title[0]" v-if="state.errors.title"/>
                   <input type="text" placeholder="Description"
                          v-model="state.task.description"
                          class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                          :class="state.errors.description ? ' border border-red-200' : ''"
                   />
                   <ErrorMessageByInput :message="state.errors.description[0]" v-if="state.errors.description"/>
                   <div class="grid grid-cols-2 gap-1">
                       <Datepicker
                           v-model="state.task.due_date"
                           :format="format"
                           :minDate="new Date()"
                           autoApply
                           :enableTimePicker="false"
                           placeholder="yyyy-mm-dd"
                           inputClassName="text-2xl"
                       ></Datepicker>
                       <select
                           v-model="state.priorityValue"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                       >
                           <option selected>Choose Priority</option>
                           <option value="0">Low</option>
                           <option value="1">Normal</option>
                           <option value="2">High</option>
                           <option value="3">Urgent</option>
                       </select>
                   </div>
                   <div class="flex justify-end mx-5">
                       <button class="items-center my-2 py-1 px-3 text-sm  text-center text-white bg-purple-500 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300">SAVE</button>
                   </div>
               </div>
           </form>
           <label for="tags">Tags: </label>
           <small class="font-bold">click to remove tag</small>

           <div class="flex space-x-1 my-1">
               <p
                   @click="removeTag(tag)"
                   class="text-green-50 text-xs bg-green-500 p-2  rounded py-1 w-fit font-bold uppercase cursor-pointer"
                   v-for="(tag,index) in state.task.tags" :key="index"
               >
                   #{{tag.name}}
               </p>
           </div>
           <form @submit.prevent="addTagHandler">
               <input type="text" placeholder="Tags"
                      v-model="state.tag"
                      class="row block text-xs py-3 px-4 rounded-lg w-full border outline-none"
                      required
               />
               <div class="flex justify-end mx-5">
                   <button
                       type="submit"
                       class="items-center w-20 my-2 py-1 px-3 text-sm  text-center text-white bg-purple-500 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300"
                   >ADD</button>
               </div>
           </form>
           <p>IMAGES:</p>

           <form @submit.prevent="addImageHandler">
               <input  ref="formFiles" type="file" class="block text-sm py-3 px-4 rounded-lg w-full border outline-none" multiple required/>
               <div class="flex justify-end mx-5">
                   <button type="submit" class="items-center my-2 py-1 px-3 text-sm  text-center text-white bg-purple-500 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300">ATTACH FILE</button>
               </div>
           </form>
       </template>
        <div class="my-2 shadow-lg p-10">
            <template v-if="state.task.attachments.length > 0">
                <small class="font-semibold italic text-blue-400">Click the Image to download and click the file link for other files</small>
                <div class="flex flex-col space-x-1 my-1">
                    <template  v-for="(attachment,index) in state.task.attachments" :key="index">
                        <img
                            @click="downloadFile(attachment.name)"
                            class="cursor-pointer object-contain h-48 w-96"
                            :src="`/files/${attachment.name}`"
                            v-if="['svg','png','jpg'].includes(attachment.name.split('.').pop())"
                        >
                        <button class="underline" v-else>{{attachment.name}}</button>
                    </template>
                </div>
            </template>
            <template v-else><p class="text-2xl">No Image.</p></template>
        </div>
        <Modal :isOpen="state.deleteConfirmation" @cancel="state.deleteConfirmation=false" @proceed="confirmDelete" body="Are you sure, you want to delete this task?"/>
    </div>
</template>

<script setup>
import ErrorMessageByInput from "@/components/common/ErrorMessageByInput.vue";
import ErrorMessageGeneral from "@/components/common/ErrorMessageGeneral.vue";
import TaskDetailDisplay from "@/components/TaskDetailDisplay.vue";
import Modal from "@/components/common/Modal.vue";
import router from "../routes";

import { useRoute } from 'vue-router'
import {ref, reactive, onMounted } from 'vue';

import moment from 'moment';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const state = reactive({
    errors: [],
    errorMessage: "",
    task: null,
    tagsDisplay: null,
    tag: null,
    priorityValue: null,
    is_editing: false,
    priorityMapping: {
        'LOW': 0,
        'NORMAL': 1,
        'HIGH': 2,
        'URGENT': 3,
    },
    deleteConfirmation: false
})
const formFiles = ref();

const format = (date) => {
    const day = date.getDate();
    let month = date.getMonth() + 1;
    if (month < 10) {
        month = "0"+month;
    }
    const year = date.getFullYear();

    return `${year}/${month}/${day}`;
}

const route = useRoute()

onMounted(() => {
    fetchTaskDetail();
})

const fetchTaskDetail = async () => {
    const response = await axios.get(`/tasks/${route.params.id}`)
    state.task = response.data
    state.tagsDisplay = state.task.tags.map(item => item.name).join(',')
    state.priorityValue = state.priorityMapping[state.task.priority]
}

const downloadFile = async (name) => {
    const response = await axios.get("/file-download/" + name, {responseType: 'blob'})
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', name);
    document.body.appendChild(link);
    link.click();
}

const toggleEdit = () => {
    state.is_editing = !state.is_editing
}

const submitHandler = async () => {
    state.errors = [];
    state.errorMessage = "";
    try {
        state.task.priority = state.priorityValue
        state.task.due_date = state.task.due_date == null ? "" : moment(state.task.due_date).format('YYYY-MM-DD')
        const response =  await axios.put(`/tasks/${route.params.id}`, state.task)
        alert('Task was saved successfully.')

    } catch (e) {
        const {errors, message} = e.response.data;
        state.errorMessage = message
        state.errors = errors
    }}

const removeTag = async (tag) => {
    if (confirm('Are you sure, you want to delete this tag?')) {
        await axios.delete(`/tasks/tags/${tag.id}`)
        state.task.tags = state.task.tags.filter(item => item.id !== tag.id);
        alert('Tag successfully deleted')
    }
}

const addTagHandler = async () => {
    const response = await axios.post(`/tasks/tags`, {tag: state.tag, task_id: state.task.id})
    state.task.tags.push(response.data)
    state.tag = null
    alert('Tag was successfully added.')

}

const addImageHandler = async () => {
    let formData = new FormData();

    for(let i = 0; i < formFiles.value.files.length; i++ ){
        let file = formFiles.value.files[i];
        formData.append('files[' + i + ']', file);
    }

    const response = await axios.post(`/tasks/upload-file/${state.task.id}`, formData,{headers: {
            'content-type': 'multipart/form-data'
    }})
    const images = response.data;
    images.map(item => {
        state.task.attachments.push(item)
    })
    formFiles.value.target.value
    alert('Files was uploaded successfully')
}

const confirmDelete = async () => {
    await axios.delete(`/tasks/${route.params.id}`)
    router.push('/')
}
</script>

<style scoped>

</style>
