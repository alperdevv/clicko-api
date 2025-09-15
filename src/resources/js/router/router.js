import { createRouter, createWebHistory } from 'vue-router';
import Users from '../components/Users/Users.vue';
import Login from '../components/Login.vue';
import UsersList from '../components/Users/Userslist.vue';
import UserEdit from '../components/Users/UserEdit.vue';
import UserCreate from '../components/Users/UserCreate.vue';
import UserShow from '../components/Users/UserShow.vue';

const routes = [
    { path: '/users', component: Users },
    { path: '/login', component: Login },
    { path: '/userslist', component: UsersList },
    { path: '/users/:id', component: UserShow },
    { path: '/users/:id/edit', component: UserEdit },
    { path: '/users/create', component: UserCreate },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;