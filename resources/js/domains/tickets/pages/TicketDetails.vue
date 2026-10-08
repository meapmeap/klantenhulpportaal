<script setup lang="ts">
    import { statuses, ticketStore } from '../store';
    import { adminStore } from '../../users/store';
    import { useRoute } from 'vue-router';
    import { formatDate } from '../../../services/utils/formatDate';
    import { authStore } from '../../../services/store/auth';
    import { computed, onMounted, ref } from 'vue';

    const route = useRoute();
    
    const ticketID = Number(route.params.id);
    const ticket = ticketStore.getters.getById(ticketID);

    const selectedAdmin = ref<number | null>(ticket.value?.user_id ?? null);
    const selectedStatus = ref(ticket.value?.status ?? '');

    const isAdmin = computed(() => authStore.isAdmin.value);
    const admins = adminStore.getters.all;

    onMounted(async () => {
        if(isAdmin.value){
            await adminStore.actions.getAll('/api/users/admins');
        }
    });

    const confirmChanges = async () => {
        if (!ticket.value) return;
        
        await ticketStore.actions.update(ticketID, {...ticket.value, user_id: selectedAdmin.value, status: selectedStatus.value});
    }
</script>

<template>
    <div v-if="ticket">
        <br>
        <router-link :to="{name: 'tickets.edit', params:{id: ticketID}}">Ticket bewerken</router-link>
        <div v-if="isAdmin">
            <br>
            <h2>Admin opties:</h2>
            <label>Ticket toewijzen aan: </label>
            <select id="admin" v-model="selectedAdmin">
                <option :value="null">Geen admin</option>
                <option v-for="admin in admins" :key="admin.id" :value="admin.id">{{ admin.voornaam }} {{ admin.achternaam }}</option>
            </select>
            <br>
            <label>Status van ticket wijzigen: </label>
            <select id="status" v-model="selectedStatus">
                <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
            </select>
            <br>
            <button type="button" @click="confirmChanges">Wijzigingen opslaan</button>
        </div>
    </div>
    <div v-if="ticket">
        <br>
        <h2>Probleem:</h2>
        <p>{{ ticket.titel }}</p>
        <p>Status: {{ ticket.status }}</p>
        <p>Categorie: {{ ticket.categorie }}</p>
        <p>Ticket aangemaakt door {{ ticket.creator }} op: {{ formatDate(ticket.created_at) }}</p>
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