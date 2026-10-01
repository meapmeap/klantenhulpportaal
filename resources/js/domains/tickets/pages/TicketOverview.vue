<script setup lang="ts">
    import { ticketStore } from '../store';
    import { computed, onMounted } from 'vue';
    import { formatDate } from '../../../services/utils/formatDate';
    import { authStore } from '../../../services/store/auth';

    const tickets = ticketStore.getters.all;

    onMounted(async () => {
        await ticketStore.actions.getAll('/api/tickets');
    });

    const isAdmin = computed(() => authStore.isAdmin.value);
</script>

<template>
    <div v-if="tickets">
        <table>
            <tbody>
                <tr>
                    <th>ID | </th>
                    <th>Titel | </th>
                    <th>Categorie | </th>
                    <th>Status | </th>
                    <th>Aangemaakt op | </th>
                    <th>Aangemaakt door | </th>
                    <th>Laatste update op | </th>
                    <th v-if="isAdmin">Toegewezen aan | </th>
                </tr>
                <tr v-for="ticket in tickets" :key="ticket.id">
                    <td>{{ ticket.id }}</td>
                    <td><router-link :to="{ name: 'tickets.details', params: { id: ticket.id } }">{{ ticket.titel }}</router-link></td>
                    <td>{{ ticket.categorie }}</td>
                    <td>{{ ticket.status }}</td>
                    <td>{{ formatDate(ticket.created_at) }}</td>
                    <td>{{ ticket.creator }}</td>
                    <td>{{ formatDate(ticket.updated_at) }}</td>
                    <td>{{ ticket.admin }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>