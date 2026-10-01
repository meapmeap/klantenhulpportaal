<script setup lang="ts">
    import { useRoute, useRouter } from 'vue-router';
    import { Ticket, ticketStore } from '../store';
    import Form from '../components/TicketForm.vue';

    const route = useRoute();
    const router = useRouter();

    const ticketId = Number(route.params.id);
    const ticket = ticketStore.getters.getById(ticketId);

    const handleSubmit = async (data: Ticket) => {
        await ticketStore.actions.update(ticketId, data);
        router.push({ name: 'tickets.details', params: { id: ticketId } });
    }
</script>

<template>
    <div>
        <h2>Ticket aanpassen.</h2>
        <Form v-if="ticket" :ticket="ticket" @submit="handleSubmit" />
    </div>
</template>