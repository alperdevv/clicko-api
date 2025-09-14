import { createRouter, createWebHistory } from 'vue-router';
import Users from '../components/Users.vue';

const routes = [
    { path: '/users', component: Users }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;