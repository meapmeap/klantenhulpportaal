<script setup lang="ts">
    import { Message, statuses, ticketStore, getReacties, addReactie } from '../store';
    import { adminStore } from '../../users/store';
    import { useRoute } from 'vue-router';
    import { formatDate } from '../../../services/utils/formatDate';
    import { authStore } from '../../../services/store/auth';
    import { computed, onMounted, ref } from 'vue';
    import ReactionForm from '../components/ReactionForm.vue';

    const route = useRoute();
    
    const ticketId = Number(route.params.id);
    const ticket = ticketStore.getters.getById(ticketId);

    const selectedAdmin = ref<number | null>(ticket.value?.user_id ?? null);
    const selectedStatus = ref(ticket.value?.status ?? '');

    const isAdmin = computed(() => authStore.isAdmin.value);
    const admins = adminStore.getters.all;

    const reacties = ref<Message[]>([]);
    const showReactieForm = ref(false);

    onMounted(async () => {
        reacties.value = await getReacties(ticketId);
    });

    //const notities
    //const showNotitieForm

    onMounted(async () => {
        if(isAdmin.value){
            await adminStore.actions.getAll('/api/users/admins');
        }
    });

    const submitReactie = async (bericht: string) => {
        const reactie = await addReactie(ticketId, { bericht });

        reacties.value.push(reactie);
        showReactieForm.value = false;
    }

    const confirmChanges = async () => {
        if (!ticket.value) return;
        
        await ticketStore.actions.update(ticketId, {...ticket.value, user_id: selectedAdmin.value, status: selectedStatus.value});
    }
</script>

<template>
    <div v-if="ticket">
        <br>
        <router-link :to="{name: 'tickets.edit', params:{id: ticketId}}">Ticket bewerken</router-link>
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
        <br>
        <div>
            <button v-if="!showReactieForm" @click="showReactieForm = true">
                Voeg reactie toe.
            </button>
            <reactionForm v-if="showReactieForm" @submit="submitReactie" />
            <button v-if="showReactieForm" @click="showReactieForm = false">
                Annuleren
            </button>
            <br>
            <h2>Reacties:</h2>
            <p v-if="reacties.length === 0">Nog geen reacties...</p>
            <div v-for="reactie in reacties" :key="reactie.id">
                <strong>{{ reactie.user }}</strong>
                <p>{{ reactie.bericht }}</p>
                <p>{{ formatDate(reactie.created_at) }}</p>
            </div>
        </div>
    </div>
</template>