import { createRouter, createWebHistory } from 'vue-router';
import Users from '../components/Users.vue';
import Login from '../components/Login.vue';
import UsersList from '../components/Userslist.vue';

const routes = [
    { path: '/users', component: Users },
    { path: '/login', component: Login },
    { path: '/userslist', component: UsersList }

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;