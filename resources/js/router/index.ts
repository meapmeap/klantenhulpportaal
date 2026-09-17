import { createRouter, createWebHistory } from 'vue-router';
import { profileRoutes } from '../domains/profile/routes'
import { ticketRoutes } from '../domains/tickets/routes'
//import { categoryRoutes } from '../domains/categories/routes'
//import { userRoutes } from '../domains/users/routes'

export const router = createRouter({
    history: createWebHistory(),
    routes: [
        ...profileRoutes,
        ...ticketRoutes,
        //...categoryRoutes,
        //...userRoutes,
    ],
})