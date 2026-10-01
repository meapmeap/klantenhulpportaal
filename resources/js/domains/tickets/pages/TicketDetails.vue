<script setup lang="ts">
    import { ticketStore } from '../store';
    import { useRoute } from 'vue-router';
    import { formatDate } from '../../../services/utils/formatDate';
    import { authStore } from '../../../services/store/auth';
    import { computed } from 'vue';

    const route = useRoute();
    const ticketID = Number(route.params.id);

    const ticket = ticketStore.getters.getById(ticketID);

    const isAdmin = computed(() => authStore.isAdmin.value);
</script>

<template>
    <div>
        <br>
        <router-link :to="{name: 'tickets.overview'}">Terug naar overzicht</router-link> | 
        <router-link :to="{name: 'tickets.edit', params:{id: ticketID}}">Ticket bewerken</router-link>
    </div>
    <div v-if="ticket">
        <br>
        <h1>Probleem:</h1>
        <p>{{ ticket.titel }}</p>
        <p>Status: {{ ticket.status }}</p>
        <p>Categorie: {{ ticket.categorie }}</p>
        <p>Ticket aangemaakt door {{ ticket.created_by }} op: {{ formatDate(ticket.created_at) }}</p>
        <p>Laatst bijgewerkt op: {{ formatDate(ticket.updated_at) }}</p>
        <p v-if="isAdmin">Toegewezen aan admin: {{ ticket.admin }}</p>
        <div v-if="isAdmin">
            Notities:
        </div>
        <div>
            Reacties:
        </div>
    </div>
</template>