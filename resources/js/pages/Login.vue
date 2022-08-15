<template>
    <div class="min-h-screen flex justify-center items-center">
        <div class="py-12 px-20 bg-white rounded-2xl shadow-xl z-20">
            <div>
                <h1 class="text-3xl font-bold text-center mb-4 cursor-pointer">Welcome to Tasker</h1>
            </div>

            <form @submit.prevent="submitHandler">
                <ErrorMessageGeneral :message="state.errorMessage" v-if="state.errorMessage"/>
                <div class="space-y-4">
                    <input type="text"
                           v-model="state.form.email"
                           placeholder="Email Address"
                           class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                           :class="state.errors.email ? ' border border-red-200' : ''"
                    />
                    <ErrorMessageByInput :message="state.errors.email[0]" v-if="state.errors.email"/>
                    <input type="password"
                           v-model="state.form.password"
                           placeholder="Password"
                           class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                           :class="state.errors.email ? ' border border-red-200' : ''"
                    />
                    <ErrorMessageByInput :message="state.errors.password[0]" v-if="state.errors.password" />
                </div>
                <div class="text-center mt-6">
                    <button type="submit" class="py-3 w-64 text-xl text-white bg-purple-400 rounded-2xl">Login</button>
                    <p class="mt-4 text-sm">Don't have an account? <router-link to="/register" class="underline cursor-pointer"> Register</router-link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
    import ErrorMessageByInput from "../components/common/ErrorMessageByInput.vue";
    import ErrorMessageGeneral from "../components/common/ErrorMessageGeneral.vue";

    import {reactive} from "vue";
    import {useStore} from "vuex";
    import router from "../routes";

    const store = useStore();

    const state = reactive({
        errors: [],
        errorMessage: "",
        form: {
            email: 'raf@gmail.com',
            password: 'rafrafraf',
        }
    })


    const submitHandler = async () => {
        state.errors = [];
        state.errorMessage = "";
        try {
            const response = await axios.post('/auth/login', state.form);
            await store.dispatch('auth/signin', response.data)
            router.push('/')
        } catch (e) {
            const {errors, message, error} = e.response.data;
            if (error) {
                state.errorMessage = error
            }
            if (message) {
                state.errorMessage = message
            }
            if (errors) {
                state.errors = errors
            }
        }
    }

</script>

<style scoped>

</style>
