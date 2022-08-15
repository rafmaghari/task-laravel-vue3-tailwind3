<template>
    <div class="mx-10 my-10 lg-5 md:mx-24 sm:mx-2">
        <div class="flex justify-end">
            <button class="bg-purple-500 text-white active:bg-purple-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button" v-on:click="toggleModal()">Create Task</button>
        </div>
        <div class="flex items-center justify-between my-2">
            <h5 class="text-2xl font-bold">Lists</h5>
            <h5 class="text-sm underline cursor-pointer" @click="state.showFilters = !state.showFilters">Toggle Sort and Filters</h5>
        </div>
        <div class="my-4" >
            <template v-if="state.showFilters">
                <div class="flex items-center space-x-1 my-2">
                    <label for="">Sort By</label>
                    <select
                        v-model="state.sortField"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                    >
                        <option selected>Choose Field</option>
                        <option value="title">Title</option>
                        <option value="description">Description</option>
                        <option value="due_date">Due Date</option>
                        <option value="created_at">Created Date</option>
                        <option value="completed_at">Completed Date</option>
                        <option value="priority">Priority</option>
                    </select>
                    <select
                        v-model="state.sortDirection"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                    >
                        <option selected>Choose Order</option>
                        <option value="asc">ASC</option>
                        <option value="desc">DESC</option>
                    </select>

                </div>
                <div class="flex items-center space-x-1 my-2">
                    <div class="flex flex-col">
                        <label for="" class="text-xs font-bold">Due Date</label>
                        <div class="flex items-center space-x-1">
                            <Datepicker
                                v-model="state.filterDueDateFrom"
                                :format="format"
                                autoApply
                                :enableTimePicker="false"
                                placeholder="From"
                            ></Datepicker>
                            <Datepicker
                                v-model="state.filterDueDateTo"
                                :format="format"
                                autoApply
                                :enableTimePicker="false"
                                placeholder="To"
                            ></Datepicker>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label for="" class="text-xs font-semibold">Completed Date</label>
                        <div class="flex items-center space-x-1">
                            <Datepicker
                                v-model="state.filterCompletedDateFrom"
                                :format="format"
                                autoApply
                                :enableTimePicker="false"
                                placeholder="From"
                            ></Datepicker>
                            <Datepicker
                                v-model="state.filterCompletedDateTo"
                                :format="format"
                                autoApply
                                :enableTimePicker="false"
                                placeholder="To"
                            ></Datepicker>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-1 my-2">
                    <div class="flex flex-col">
                        <label for="" class="text-xs font-semibold">Archived Date</label>
                        <div class="flex items-center space-x-1">
                            <Datepicker
                                v-model="state.filterArchivedDateFrom"
                                :format="format"
                                autoApply
                                :enableTimePicker="false"
                                placeholder="From"
                            ></Datepicker>
                            <Datepicker
                                v-model="state.filterArchivedDateTo"
                                :format="format"
                                autoApply
                                :enableTimePicker="false"
                                placeholder="To"
                            ></Datepicker>
                        </div>
                    </div>

                </div>
                <div class="flex space-x-1 w-11/12">
                    <select
                        v-model="state.filterPriority"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500  w-full p-2.5"
                    >
                        <option selected>Choose Priority</option>
                        <option value="0">Low</option>
                        <option value="1">Normal</option>
                        <option value="2">High</option>
                        <option value="3">Urgent</option>
                    </select>
                    <input type="text" placeholder="Title and description"
                           class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                           v-model="state.filterTitleAndDescription"
                    />
                </div>
                <div class="flex justify-end mx-10">
                    <button
                        @click="fetchTasks()"
                        class="items-center my-2 py-2 px-3 text-sm  text-center text-white bg-purple-500 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300"
                    >SUBMIT</button>
                </div>
            </template>
            <Tasks :tasks="state.tasks" @reload="reload" v-if="state.tasks.data?.length > 0"/>
            <h1 class="text-3xl font-bold" v-else>No data found</h1>
        </div>
        <div class="flex justify-end">
            <Pagination :data="state.tasks" :limit="5" @pagination-change-page="fetchTasks"/>
        </div>
        <div v-if="state.showModal" class="m-5 overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="relative w-auto my-6 mx-auto max-w-3xl">
                <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                    <div class="flex items-start justify-between p-5 border-b border-solid border-slate-200 rounded-t">
                        <h3 class="text-xl font-semibold">Create Task</h3>
                        <button class="p-1 ml-auto bg-transparent border-0 text-black opacity-5 float-right text-3xl leading-none font-semibold outline-none focus:outline-none" v-on:click="toggleModal()">
                            <span class="bg-transparent text-black opacity-5 h-6 w-6 text-2xl block outline-none focus:outline-none">×</span>
                        </button>
                    </div>
                    <form @submit.prevent="submitHandler">
                        <div class="mx-2">
                            <ErrorMessageGeneral :message="state.errorMessage" v-if="state.errorMessage"/>
                        </div>
                        <div class="relative px-4 py-2 flex-auto space-y-2">
                            <input type="text" placeholder="Title"
                                   v-model="title"
                                   class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                                   :class="state.errors.title ? ' border border-red-200' : ''"
                            />
                            <ErrorMessageByInput :message="state.errors.title[0]" v-if="state.errors.title"/>
                            <input type="text" placeholder="Description"
                                   v-model="description"
                                   class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                                   :class="state.errors.title ? ' border border-red-200' : ''"
                            />
                            <ErrorMessageByInput :message="state.errors.description[0]" v-if="state.errors.description"/>
                            <Datepicker
                                v-model="due_date"
                                :format="format"
                                :minDate="new Date()"
                                autoApply
                                :enableTimePicker="false"
                                placeholder="yyyy-mm-dd"
                                :class="state.errors.due_date ? ' border border-red-200' : ''"
                            ></Datepicker>
                            <ErrorMessageByInput :message="state.errors.due_date[0]" v-if="state.errors.due_date"/>
                            <select
                                v-model="priority"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                :class="state.errors.priority ? ' border border-red-200' : ''"
                            >
                                <option selected>Choose Priority</option>
                                <option value="0">Low</option>
                                <option value="1">Normal</option>
                                <option value="2">High</option>
                                <option value="3">Urgent</option>
                            </select>
                            <ErrorMessageByInput :message="state.errors.priority[0]" v-if="state.errors.priority"/>
                            <input type="text" placeholder="Tags (comma separated)"
                                   v-model="tags"
                                   class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                            />
                            <input  ref="formFiles" type="file" class="block text-sm py-3 px-4 rounded-lg w-full border outline-none" multiple/>
                        </div>
                        <div class="flex items-center justify-end p-6 border-t border-solid border-slate-200 rounded-b">
                            <button class="text-purple-500 bg-transparent border border-solid border-purple-500 hover:bg-purple-500 hover:text-white active:bg-purple-600 font-bold uppercase text-sm px-6 py-3 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button" v-on:click="toggleModal()">Close</button>
                            <button type="submit" class="text-purple-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-if="state.showModal" class="opacity-25 fixed inset-0 z-40 bg-black"></div>

    </div>
</template>

<script setup>
    import ErrorMessageByInput from "@/components/common/ErrorMessageByInput.vue";
    import ErrorMessageGeneral from "@/components/common/ErrorMessageGeneral.vue";
    import Pagination from '@/components/common/LaravelVuePagination.vue';
    import Tasks from "@/components/Tasks.vue";
    import router from "../routes";

    import moment from 'moment';
    import Datepicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

    import {useStore} from "vuex";
    import { useRoute } from 'vue-router'
    import {computed, reactive, ref, onMounted} from "vue";

    const date = ref(new Date());
    const route = useStore();
    const format = (date) => {
        const day = date.getDate();
        let month = date.getMonth() + 1;
        if (month < 10) {
            month = "0"+month;
        }
        const year = date.getFullYear();

        return `${year}/${month}/${day}`;
    }

    const state = reactive({
        showModal: false,
        errors: [],
        errorMessage: null,
        tasks: [],
        sortField: 'created_at',
        sortDirection: 'desc',
        filterDueDateFrom: null,
        filterDueDateTo: null,
        filterCompletedDateFrom: null,
        filterCompletedDateTo: null,
        filterArchivedDateFrom: null,
        filterArchivedDateTo: null,
        filterPriority: null,
        filterTitleAndDescription: null,
        showFilters: false
    })

    const formFiles = ref();
    const title = ref("");
    const description = ref("");
    const due_date = ref("");
    const priority = ref("");
    const tags = ref("");


    const store = useStore();

    const user = computed(() => store.getters["auth/user"])
    const path = computed(() => route.path)

    onMounted(() => {
        fetchTasks();
    })

    const toggleModal = () => {
        state.showModal = !state.showModal
    }

    const fetchTasks = async (page = 1) => {
       let status = ""
       if (window.location.href.includes('completed-task')) {
           status = "completed"
       } else if (window.location.href.includes('archived-task')) {
           status = "archived"
       } else if (window.location.href.includes('todo-task')) {
           status = "todo"
       }
       const sortField = state.sortField;
       const sortDirection = state.sortDirection;
       const params = {
           'status': status,
           'page': page,
           'sort_field': sortField,
           'sort_direction': sortDirection,
           'filter_due_date_from': state.filterDueDateFrom == null ? '' : moment(state.filterDueDateFrom).format('YYYY-MM-DD'),
           'filter_due_date_to': state.filterDueDateTo == null ? '' : moment(state.filterDueDateTo).format('YYYY-MM-DD'),
           'filter_completed_from': state.filterCompletedDateFrom == null ? '' : moment(state.filterCompletedDateFrom).format('YYYY-MM-DD'),
           'filter_completed_to': state.filterCompletedDateTo == null ? '' : moment(state.filterCompletedDateTo).format('YYYY-MM-DD'),
           'filter_archived_from':state.filterArchivedDateFrom == null ? '' : moment(state.filterArchivedDateFrom).format('YYYY-MM-DD'),
           'filter_archived_to': state.filterArchivedDateTo == null ? '' : moment(state.filterArchivedDateTo).format('YYYY-MM-DD'),
           'filter_priority': state.filterPriority,
           'filter_tile_and_description': state.filterTitleAndDescription
       }
       const response = await axios.post(`/tasks/list`, params);
       state.tasks = response.data
    }

    const submitHandler = async () => {
        state.errorMessage = null
        state.errors = []
        try {
            let formData = new FormData();

            for(let i = 0; i < formFiles.value.files.length; i++ ){
                let file = formFiles.value.files[i];
                formData.append('files[' + i + ']', file);
            }
            const formatted_due_date = due_date.value == "" ? '' : moment(due_date.value).format('YYYY-MM-DD')
            formData.append('title', title.value);
            formData.append('description', description.value);
            formData.append('due_date',formatted_due_date)
            formData.append('priority',priority.value)
            formData.append('tags', tags.value)

            await axios.post('/tasks', formData,{headers: {
                    'content-type': 'multipart/form-data'
            }})
            state.showModal = false
            title.value = ''
            description.value = ''
            due_date.value = ''
            priority.value = ''
            tags.value = ''
            alert('Task successfully added.')
            fetchTasks()
        } catch (e) {
            const {errors, message} = e.response.data;
            state.errorMessage = message
            state.errors = errors
        }
    }

    const reload = (data) => {
       fetchTasks(state.tasks.current_page)
       router.push(data)
    }

</script>
