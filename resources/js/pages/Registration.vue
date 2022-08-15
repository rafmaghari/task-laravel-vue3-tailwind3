<template>
    <div class="min-h-screen flex justify-center items-center">
        <div class="py-12 px-12 bg-white rounded-2xl shadow-xl z-20">
            <div>
                <h1 class="text-3xl font-bold text-center mb-4 cursor-pointer">Create An Account</h1>
            </div>
            <form @submit.prevent="submitHandler()">
                <ErrorMessageGeneral :message="state.errorMessage" v-if="state.errorMessage"/>
                <div class="space-y-4">
                    <input type="text" placeholder="Name"
                           class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                           v-model="state.form.name"
                           :class="state.errors.name ? ' border border-red-200' : ''"
                    />
                    <ErrorMessageByInput :message="state.errors.name[0]" v-if="state.errors.name" />

                    <input type="email" placeholder="Email Address"
                           class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                           v-model="state.form.email"
                           :class="state.errors.email ? ' border border-red-200' : ''"
                    />
                    <ErrorMessageByInput :message="state.errors.email[0]" v-if="state.errors.email"/>

                    <input type="password" placeholder="Password"
                           class="block text-sm py-3 px-4 rounded-lg w-full border outline-none"
                           v-model="state.form.password"
                           :class="state.errors.password ? ' border border-red-200' : ''"
                    />
                    <ErrorMessageByInput :message="state.errors.password[0]" v-if="state.errors.password"/>
                </div>
                <div class="text-center mt-6">
                    <button type="submit" class="py-3 w-64 text-xl text-white bg-purple-400 rounded-2xl">Create Account</button>
                    <p class="mt-4 text-sm">Already Have An Account? <router-link to="/login" class="underline cursor-pointer"> Sign In</router-link>
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
            name: null,
            email: null,
            password: null
        },
        showModal: false
    })

    const submitHandler = async () => {
        try {
            const response = await axios.post('/auth/register', state.form);
            await store.dispatch('auth/signin', response.data)
            router.push('/')
        } catch (e) {
            const {errors, message} = e.response.data;
            state.errorMessage = message
            state.errors = errors
        }
    }
</script>

<style scoped>

</style>
