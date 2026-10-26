<script setup lang="ts">
    import { ticketStore } from '../store';
    import { useRoute } from 'vue-router';
    import { formatDate } from '../../../services/utils/formatDate';
    import { authStore } from '../../../services/store/auth';
    import { computed } from 'vue';
    import { getMessage } from '../../../services/error';
    import TicketNotes from '../components/TicketNotes.vue';
    import TicketReactions from '../components/TicketReactions.vue';
    import TicketAdminOptions from '../components/TicketAdminOptions.vue';

    const route = useRoute();

    const ticketId = Number(route.params.id);
    const ticket = ticketStore.getters.getById(ticketId);

    const isAdmin = computed(() => authStore.isAdmin.value);
</script>

<template>
    <div v-if="ticket" class="section"> 
        <!--opties voor bewerken van ticket -->
        <router-link :to="{name: 'tickets.edit', params:{id: ticketId}}">Ticket bewerken</router-link>
        <TicketAdminOptions v-if="isAdmin" :ticket-id="ticketId" />
    </div>
    <div v-if="ticket" class="section"> 
        <!--informatie over ticket-->
        <h2>Probleem:</h2>
        <p>{{ ticket.titel }}</p>
        <p>Status: {{ ticket.status }}</p>
        <p>Categorie: {{ ticket.categorie }}</p>
        <p>Ticket aangemaakt door {{ ticket.creator }} op: {{ formatDate(ticket.created_at) }}</p>
        <p>Laatst bijgewerkt op: {{ formatDate(ticket.updated_at) }}</p>
        <p v-if="isAdmin">Toegewezen aan admin: {{ ticket.admin }}</p>
        <p v-if="getMessage" class="section">Error: {{ getMessage }}</p>
        <div v-if="isAdmin" class="column"> 
            <!--admin notities-->
            <TicketNotes v-if="isAdmin" :ticket-id="ticketId" />
        </div>
        <div class="column">
            <!--reacties-->
            <TicketReactions :ticket-id="ticketId" />
        </div>
    </div>
</template>