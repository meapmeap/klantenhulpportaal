<script setup lang="ts">
    import { Message, statuses, ticketStore, reactieActions, notitieActions } from '../store';
    import { adminStore } from '../../users/store';
    import { useRoute } from 'vue-router';
    import { formatDate } from '../../../services/utils/formatDate';
    import { authStore } from '../../../services/store/auth';
    import { computed, onMounted, ref } from 'vue';
    import ReactionForm from '../components/ReactionForm.vue';

    const route = useRoute();
    //                                    Maak een error bag om de errors en try/catch eruit te halen. Mogelijk nog notitie en reactie functies verder combineren
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
    const reactieError = ref('');

    //notities
    const notities = ref<Message[]>([]); 
    const showNotitieForm = ref(false); 
    const editingNotitieId = ref<number | null>(null); 
    const notitieError = ref('');

    onMounted(async () => { 
        try { 
            reacties.value = await reactieActions.getAll(ticketId); 
        } catch (error) { 
            console.error('Reacties ophalen mislukt:', error); 
            reactieError.value = 'De reacties konden niet worden opgehaald.'; 
        } 
        
        if (isAdmin.value) { 
            try { 
                notities.value = await notitieActions.getAll(ticketId); 
            } catch (error) { 
                console.error('Notities ophalen mislukt:', error); 
                notitieError.value = 'De notities konden niet worden opgehaald.'; 
            } 
            
            await adminStore.actions.getAll('/api/users/admins'); 
        } 
    });

    //reactie crud functies
    const submitReactie = async (bericht: string) => {
        reactieError.value = '';

        try {
            const reactie = await reactieActions.create(ticketId, { bericht });

            reacties.value.push(reactie);

            showReactieForm.value = false;
        } catch (error) {
            console.error('Reactie toevoegen mislukt:', error);
            reactieError.value = 'De reactie kon niet worden toegevoegd. Probeer het opnieuw.';
        }
    }

    const startEdit = (reactie: Message) => { editingReactieId.value = reactie.id; };
    const cancelEdit = () => { editingReactieId.value = null; };

    const saveEdit = async (reactie: Message, bericht: string) => { 
        reactieError.value = '';

        try {
            const updatedReactie = await reactieActions.update(ticketId, reactie.id, { bericht: bericht.trim() });

            const index = reacties.value.findIndex(item => item.id === reactie.id);

            if (index !== -1) {
                reacties.value[index] = updatedReactie;
            }

            editingReactieId.value = null;
        } catch (error) {
            console.error('Reactie wijzigen mislukt:', error);
            reactieError.value = 'De reactie kon niet worden gewijzigd. Probeer het opnieuw.';
        }
    };

    //notities crud functies
    const submitNotitie = async (bericht: string) => { 
        notitieError.value = ''; 
        
        try { 
            const notitie = await notitieActions.create( ticketId, { bericht: bericht.trim() } ); 
            
            notities.value.push(notitie); 
            
            showNotitieForm.value = false; 
        } catch (error) { 
            console.error('Notitie toevoegen mislukt:', error); 
            notitieError.value = 'De notitie kon niet worden toegevoegd. Probeer het opnieuw.'; 
        } 
    }; 
    
    const startEditNotitie = (notitie: Message) => { editingNotitieId.value = notitie.id; }; 
    const cancelEditNotitie = () => { editingNotitieId.value = null; }; 
    
    const saveEditNotitie = async (notitie: Message, bericht: string) => { 
        notitieError.value = ''; 
        
        try { 
            const updatedNotitie = await notitieActions.update( ticketId, notitie.id, { bericht: bericht.trim() } ); 
            
            const index = notities.value.findIndex( item => item.id === notitie.id ); 
            
            if (index !== -1) { 
                notities.value[index] = updatedNotitie; 
            } 
            
            editingNotitieId.value = null; 
        } catch (error) { 
            console.error('Notitie wijzigen mislukt:', error); 
            notitieError.value = 'De notitie kon niet worden gewijzigd. Probeer het opnieuw.'; 
        } 
    };

    const confirmChanges = async () => {
        if (!ticket.value) return;
        
        await ticketStore.actions.update(ticketId, {...ticket.value, user_id: selectedAdmin.value, status: selectedStatus.value});
    }
</script>

<template>
    <div v-if="ticket"> <!--opties voor bewerken van ticket -->
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
    <div v-if="ticket"> <!--informatie over ticket-->
        <br>
        <h2>Probleem:</h2>
        <p>{{ ticket.titel }}</p>
        <p>Status: {{ ticket.status }}</p>
        <p>Categorie: {{ ticket.categorie }}</p>
        <p>Ticket aangemaakt door {{ ticket.creator }} op: {{ formatDate(ticket.created_at) }}</p>
        <p>Laatst bijgewerkt op: {{ formatDate(ticket.updated_at) }}</p>
        <p v-if="isAdmin">Toegewezen aan admin: {{ ticket.admin }}</p>
        <div v-if="isAdmin"> <!--admin notities-->
            <br>
            <h2>Notities:</h2>
            <button v-if="!showNotitieForm" type="button" @click="showNotitieForm = true" > Notitie toevoegen </button>
            <ReactionForm v-if="showNotitieForm" @submit="submitNotitie" />
            <button v-if="showNotitieForm" type="button" @click="showNotitieForm = false" > Annuleren </button>
            <p v-if="notitieError"> {{ notitieError }} </p>
            <p v-if="notities.length === 0"> Nog geen notities... </p>
            <div v-for="notitie in notities" :key="notitie.id"> 
                <strong>{{ notitie.user }}</strong> 
                <div v-if="editingNotitieId === notitie.id"> 
                    <ReactionForm :initial-message="notitie.bericht" @submit="saveEditNotitie(notitie, $event)" @cancel="cancelEditNotitie" /> 
                </div>
                <div v-else> 
                    <p>{{ notitie.bericht }}</p> 
                    <p>{{ formatDate(notitie.created_at) }}</p> 
                    <button type="button" @click="startEditNotitie(notitie)" > Bewerken </button> 
                </div>
            </div>
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
            <p v-if="reactieError">{{ reactieError }}</p>
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