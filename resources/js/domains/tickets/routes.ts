import Overview from "./pages/TicketOverview.vue";
import Details from "./pages/TicketDetails.vue";
import Create from "./pages/TicketCreate.vue";
import Edit from "./pages/TicketEdit.vue";

export const ticketRoutes = [
    { path: '/tickets', component: Overview, name: 'tickets.overview' },
    { path: '/tickets/create', component: Create, name: 'tickets.create' },
    { path: '/tickets/:id/edit', component: Edit, name: 'tickets.edit' },
    { path: '/tickets/:id', component: Details, name: 'tickets.details' }
];
