import { createWebHistory, createRouter } from 'vue-router'
import List from '@/pages/List.vue'
import Login from '@/pages/Login.vue'
import Register from '@/pages/Registration.vue'
import store from "@/store";
import TaskDetail from "@/pages/TaskDetail.vue";


const routes = [
    {
        path: '/',
        name: 'Home',
        component: List,
        meta: {
            middleware: "auth",
            title: "Home"
        }
    },
    {
        path: '/completed-task',
        name: '',
        component: List,
        meta: {
            middleware: "auth",
            title: "Completed"
        }
    },
    {
        path: '/archived-task',
        name: '',
        component: List,
        meta: {
            middleware: "auth",
            title: "Archived"
        }
    },
    {
        path: '/todo-task',
        name: '',
        component: List,
        meta: {
            middleware: "auth",
            title: "Todo"
        }
    },
    {
        path: '/tasks/:id',
        name: 'TaskDetail',
        component: TaskDetail,
        meta: {
            middleware: "auth",
            title: "Task"
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: {
            middleware: "guest",
            title: "Login"
        }
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: {
            middleware: "guest",
            title: "Register"
        }
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'bg-purple-400 p-2 rounded'
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "Home" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "Login" })
        }
    }
})

export default router
