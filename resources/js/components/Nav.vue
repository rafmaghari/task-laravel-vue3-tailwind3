<template>
    <div>
        <div class="bg-purple-500">
            <nav class="container px-6 py-4 mx-auto md:flex md:justify-between md:items-center">
                <div class="flex items-center justify-between">
                    <a href="/" class=" text-xl font-bold text-purple-100 md:text-2xl hover:text-purple-300">Tasker</a>
                    <!-- Mobile menu button -->
                    <div @click="state.showMenu = !state.showMenu" class="flex md:hidden sm:hidden">
                        <button type="button" class="text-purple-100 hover:text-purple-400 focus:outline-none focus:text-purple-400">
                            <svg viewBox="0 0 24 24" class="w-6 h-6 fill-current">
                                <path
                                    fill-rule="evenodd"
                                    d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>


                <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
                <ul :class="state.showMenu ? 'flex' : 'hidden'" class=" flex-col mt-8 space-y-4 md:flex md:space-y-0 md:flex-row md:items-center md:space-x-10 md:mt-0">
                    <template v-if="authenticated">
                        <router-link to="/" class="text-sm font-bold text-purple-100 hover:text-purple-400 cursor-pointer ">Home</router-link>
                        <router-link to="/todo-task" class="text-sm font-bold text-purple-100 hover:text-purple-400 cursor-pointer">Todo Task</router-link>
                        <router-link to="/archived-task" class="text-sm font-bold text-purple-100 hover:text-purple-400 cursor-pointer">Archived Tasks</router-link>
                        <router-link to="/completed-task" class="text-sm font-bold text-purple-100 hover:text-purple-400 cursor-pointer">Completed Tasks</router-link>
                        <button type="button" @click="logout" class="text-sm text-left font-bold text-purple-100 hover:text-purple-400 cursor-pointer">Logout</button>
                    </template>
                    <router-link to="/login" class="text-sm font-bold text-purple-100 hover:text-purple-400 cursor-pointer" v-if="!authenticated">Login</router-link>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script setup>
    import {useStore} from "vuex";
    import {reactive, computed} from "vue";
    import router from "../routes";

    const state = reactive({
        showMenu: false
    })

    const store = useStore();

    const authenticated = computed(() => store.getters["auth/authenticated"])

    const logout = async () => {
        if (confirm('Are you sure, you want to logout?')) {
            const result = await axios.post('/auth/logout')
            store.dispatch('auth/logout')
            router.push({name: 'Login'})
        }
    }

    const isActive = (path) => {
       return window.location.pathname === path
    }
</script>
