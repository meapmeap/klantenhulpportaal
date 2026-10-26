<script setup lang="ts">
    import { ref } from 'vue';
    import { Message, notitieActions } from '../store';
    import { formatDate } from '../../../services/utils/formatDate';
    import { destroyMessage, destroyErrors, handleApiAction } from '../../../services/error';
    import ReactionForm from './ReactionForm.vue';
    import { useTicketMessages } from '../composables/useTicketMessages';

    const props = defineProps<{ ticketId: number }>();

    const showNotitieForm = ref(false);

    const {
        messages: notities,
        editingMessageId: editingNotitieId,
        addMessage: submitNotitie,
        editMessage: saveEditNotitie,
        cancelEdit: cancelEditNotitie
    } = useTicketMessages(props.ticketId, notitieActions);

    const startEditNotitie = (notitie: Message) => {
        editingNotitieId.value = notitie.id;
    };

    const submitNotitieForm = async (bericht: string) => {
        const count = notities.value.length;

        await submitNotitie(bericht);

        if (notities.value.length > count) {
            showNotitieForm.value = false;
        }
    };

    const deleteNotitie = async (notitie: Message) => {
        if (!window.confirm('Weet je zeker dat je deze notitie wilt verwijderen?')) {
            return;
        }

        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() => notitieActions.delete(props.ticketId, notitie.id));

        if (!result.success) return;

        notities.value = notities.value.filter(item => item.id !== notitie.id);
    };
</script>

<template>
    <section class="section">
        <button v-if="!showNotitieForm" type="button" @click="showNotitieForm = true">Notitie toevoegen</button>
        <h2>notities:</h2>
        <ReactionForm v-if="showNotitieForm" @submit="submitNotitieForm" @cancel="showNotitieForm = false" />
        <p v-if="notities.length === 0">Nog geen notities...</p>
        <div v-for="notitie in notities" :key="notitie.id">
            <strong>{{ notitie.user }}</strong>
            <div v-if="editingNotitieId === notitie.id">
                <ReactionForm :initial-message="notitie.bericht" @submit="saveEditNotitie(notitie, $event)" @cancel="cancelEditNotitie" />
            </div>
            <div v-else>
                <p>{{ notitie.bericht }}</p>
                <p>{{ formatDate(notitie.created_at) }}</p>
                <button type="button" @click="startEditNotitie(notitie)">Bewerken</button> | 
                <button type="button" @click="deleteNotitie(notitie)">Verwijderen</button>
            </div>
        </div>
    </section>
</template>