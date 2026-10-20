import Overview from "./pages/UserOverview.vue";
import Create from "./pages/UserCreate.vue";
import Edit from "./pages/UserEdit.vue";

export const userRoutes = [
    { path: '/users', component: Overview, name: 'users.overview' },
    { path: '/users/create', component: Create, name: 'users.create' },
    { path: '/users/:id/edit', component: Edit, name: 'users.edit' }
];