import Overview from "./pages/CategoryOverview.vue";
import Create from "./pages/CategoryCreate.vue";
import Edit from "./pages/CategoryCreate.vue";

export const categoryRoutes = [
    { path: '/categories', component: Overview, name: 'categories.overview' },
    { path: '/categories/create', component: Create, name: 'categories.create' },
    { path: '/categories/:id/edit', component: Edit, name: 'categories.edit' },
];