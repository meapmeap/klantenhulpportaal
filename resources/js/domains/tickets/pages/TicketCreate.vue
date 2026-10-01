<script setup lang="ts">
    import Form from '../components/TicketForm.vue';
    import { useRouter } from 'vue-router';
    import { ref } from 'vue';
    import { ticketStore, NewTicket } from '../store.js';

    const router = useRouter();

    const ticket = ref<NewTicket>({
        titel: '',
        categorie_id: 0,
        user_id: null,
    })

    const handleSubmit = async (data: NewTicket) => {
        await ticketStore.actions.create(data);
        router.push({name: 'tickets.overview'});
    }
</script>

<template>
    <div>
        <h2>Nieuwe ticket</h2>
        <Form :ticket="ticket" @submit="handleSubmit" />
    </div>
</template>