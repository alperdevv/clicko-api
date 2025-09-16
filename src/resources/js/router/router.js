import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import UsersList from '../components/Users/Userslist.vue';
import UserEdit from '../components/Users/UserEdit.vue';
import UserCreate from '../components/Users/UserCreate.vue';
import UserShow from '../components/Users/UserShow.vue';
import Home from '../components/Home.vue';

const routes = [
    { path: '/login', component: Login },
    { path: '/userslist', component: UsersList },
    { path: '/', redirect: '/login' },
    { path: '/users/:id', component: UserShow },
    { path: '/users/:id/edit', component: UserEdit },
    { path: '/users/create', component: UserCreate },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;