<script setup lang="ts">
    import { ticketStore } from '../store';
    import { onMounted } from 'vue';
    import { authStore } from '../../../services/store/auth'; 

    const tickets = ticketStore.getters.all;

    onMounted(async () => {
        if(authStore.isAdmin){
            await ticketStore.actions.getAll('/api/tickets');
        } else {
            await ticketStore.actions.getAll('/api/my-tickets');
        }
    });
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
                    <th>Toegewezen aan | </th>
                </tr>
                <tr v-for="ticket in tickets" :key="ticket.id">
                    <td>{{ ticket.id }}</td>
                    <td>{{ ticket.titel }}</td>
                    <td>{{ ticket.categorie_id }}</td>
                    <td>{{ ticket.status }}</td>
                    <td>{{ ticket.created_at }}</td>
                    <td>{{ ticket.created_by }}</td>
                    <td>{{ ticket.updated_at }}</td>
                    <td>{{ ticket.user_id }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>