<script setup lang="ts">
    import { Message, statuses, ticketStore, reactieActions, notitieActions } from '../store';
    import { adminStore } from '../../users/store';
    import { useRoute } from 'vue-router';
    import { formatDate } from '../../../services/utils/formatDate';
    import { authStore } from '../../../services/store/auth';
    import { computed, onMounted, ref } from 'vue';
    import ReactionForm from '../components/ReactionForm.vue';
    import { destroyMessage, destroyErrors, handleApiAction, getMessage } from '../../../services/error';

    const route = useRoute();

    const ticketId = Number(route.params.id);
    const ticket = ticketStore.getters.getById(ticketId);

    const selectedAdmin = ref<number | null>(ticket.value?.user_id ?? null);
    const selectedStatus = ref(ticket.value?.status ?? '');

    const isAdmin = computed(() => authStore.isAdmin.value);
    const admins = adminStore.getters.all;
    //reacties
    const reacties = ref<Message[]>([]);
    const showReactieForm = ref(false);
    const editingReactieId = ref<number | null>(null);
    //notities
    const notities = ref<Message[]>([]); 
    const showNotitieForm = ref(false); 
    const editingNotitieId = ref<number | null>(null); 

    onMounted(async () => {
        const reactiesResult = await handleApiAction(() => reactieActions.getAll(ticketId));

        if (reactiesResult.success) {
            reacties.value = reactiesResult.data;
        }

        if (isAdmin.value) {
            const notitiesResult = await handleApiAction(() => notitieActions.getAll(ticketId));

            if (notitiesResult.success) {
                notities.value = notitiesResult.data;
            }

            await handleApiAction(() => adminStore.actions.getAll('/api/users/admins'));
        }
    });
    //vervanger voor notitie of reactie
    const replaceMessage = (items: Message[], updatedMessage: Message) => {
        const index = items.findIndex(item => item.id === updatedMessage.id);

        if (index !== -1) {
            items[index] = updatedMessage;
        }
    };
    //reactie crud functies
    const submitReactie = async (bericht: string) => {
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() =>
            reactieActions.create(ticketId, { bericht })
        );

        if (!result.success) return;

        reacties.value.push(result.data);
        showReactieForm.value = false;
    }

    const startEdit = (reactie: Message) => { editingReactieId.value = reactie.id; };
    const cancelEdit = () => { editingReactieId.value = null; };

    const saveEdit = async (reactie: Message, bericht: string) => { 
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() =>
            reactieActions.update(ticketId, reactie.id, {bericht: bericht.trim()})
        );

        if (!result.success) return;

        replaceMessage(reacties.value, result.data);
        editingReactieId.value = null;
    };
    //notities crud functies
    const submitNotitie = async (bericht: string) => { 
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() =>
            notitieActions.create(ticketId, {bericht: bericht.trim()})
        );

        if (!result.success) return;

        notities.value.push(result.data);
        showNotitieForm.value = false;
    }; 
    
    const startEditNotitie = (notitie: Message) => { editingNotitieId.value = notitie.id; }; 
    const cancelEditNotitie = () => { editingNotitieId.value = null; }; 
    
    const saveEditNotitie = async (notitie: Message, bericht: string) => { 
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() =>
            notitieActions.update(ticketId, notitie.id, {bericht: bericht.trim()})
        );

        if (!result.success) return;

        replaceMessage(notities.value, result.data);
        editingNotitieId.value = null;
    };

    const deleteNotitie = async (notitie: Message) => {
        if (!window.confirm('Weet je zeker dat je deze notitie wilt verwijderen?')) {
            return;
        }

        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() =>
            notitieActions.delete(ticketId, notitie.id)
        );

        if (!result.success) return;

        notities.value = notities.value.filter(item => item.id !== notitie.id);
    };
    //confirm van admin acties
    const confirmChanges = async () => {
        if (!ticket.value) return;

        destroyErrors();
        destroyMessage();

        await handleApiAction(() => ticketStore.actions.update(ticketId, {...ticket.value!, user_id: selectedAdmin.value, status: selectedStatus.value}));
    };
</script>

<template>
    <div v-if="ticket"> 
        <!--opties voor bewerken van ticket -->
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
        <!--informatie over ticket-->
        <br>
        <h2>Probleem:</h2>
        <p>{{ ticket.titel }}</p>
        <p>Status: {{ ticket.status }}</p>
        <p>Categorie: {{ ticket.categorie }}</p>
        <p>Ticket aangemaakt door {{ ticket.creator }} op: {{ formatDate(ticket.created_at) }}</p>
        <p>Laatst bijgewerkt op: {{ formatDate(ticket.updated_at) }}</p>
        <p v-if="isAdmin">Toegewezen aan admin: {{ ticket.admin }}</p>
        <p v-if="getMessage">{{ getMessage }}</p>
        <div v-if="isAdmin"> 
            <!--admin notities-->
            <br>
            <button v-if="!showNotitieForm" type="button" @click="showNotitieForm = true" > Notitie toevoegen </button>
            <h2>Notities:</h2>
            <ReactionForm v-if="showNotitieForm" @submit="submitNotitie" />
            <button v-if="showNotitieForm" type="button" @click="showNotitieForm = false" > Annuleren </button>
            <p v-if="notities.length === 0"> Nog geen notities... </p>
            <div v-for="notitie in notities" :key="notitie.id"> 
                <strong>{{ notitie.user }}</strong> 
                <div v-if="editingNotitieId === notitie.id"> 
                    <ReactionForm :initial-message="notitie.bericht" @submit="saveEditNotitie(notitie, $event)" @cancel="cancelEditNotitie" /> 
                </div>
                <div v-else> 
                    <p>{{ notitie.bericht }}</p> 
                    <p>{{ formatDate(notitie.created_at) }}</p> 
                    <button type="button" @click="startEditNotitie(notitie)" > Bewerken </button> | 
                    <button type="button" @click="deleteNotitie(notitie)">Verwijderen</button>
                </div>
            </div>
        </div>
        <br>
        <div>
            <!--reacties-->
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
                <div v-if="editingReactieId === reactie.id"> 
                    <ReactionForm :initial-message="reactie.bericht" @submit="saveEdit(reactie, $event)" @cancel="cancelEdit" /> 
                </div>
                <div v-else> 
                    <p>{{ reactie.bericht }}</p> 
                    <p>{{ formatDate(reactie.created_at) }}</p> 
                    <button v-if="isAdmin" @click="startEdit(reactie)"> 
                        Bewerken 
                    </button> 
                </div>
            </div>
        </div>
    </div>
</template>