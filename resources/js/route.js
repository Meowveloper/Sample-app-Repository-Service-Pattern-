import Dashboard from './pages/Dashboard.vue'
import Category from './pages/category/Browse.vue'
import Problem from './pages/problem/Browse.vue'
// import ProblemOptions from './pages/problem-options/Browse.vue'
import User from "./pages/user/Browse.vue"
import Admin from "./pages/admin/Browse.vue"

export default [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/categories',
        name: 'categories',
        component: Category,
    },
    {
        path: '/problems',
        name: 'problems',
        component: Problem,
    },
    {
        path: '/users',
        name: 'users',
        component: User,
    },
    {
        path : '/admins',
        name : 'admins',
        component : Admin,
    }
]

