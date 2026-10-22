<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import { Message, notitieActions } from '../store';
    import { formatDate } from '../../../services/utils/formatDate';
    import { destroyMessage, destroyErrors, handleApiAction } from '../../../services/error';
    import ReactionForm from './ReactionForm.vue';

    const props = defineProps<{ ticketId: number }>();

    const notities = ref<Message[]>([]);
    const showNotitieForm = ref(false);
    const notitieId = ref<number | null>(null);

    onMounted(async () => {
        const result = await handleApiAction(() => notitieActions.getAll(props.ticketId));

        if (result.success) {
            notities.value = result.data;
        }
    })

    const replaceMessage = (updatedMessage: Message) => {
        const index = notities.value.findIndex(item => item.id === updatedMessage.id);

        if (index !== -1) {
            notities.value[index] = updatedMessage;
        }
    };

    const submitNotitie = async (bericht: string) => {
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() =>
            notitieActions.create(props.ticketId, { bericht: bericht.trim() })
        );

        if (!result.success) return;

        notities.value.push(result.data);
        showNotitieForm.value = false;
    };

    const startEditNotitie = (notitie: Message) => {
        notitieId.value = notitie.id;
    };

    const cancelEditNotitie = () => {
        notitieId.value = null;
    };

    const saveEditNotitie = async (notitie: Message, bericht: string) => {
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() =>
            notitieActions.update(props.ticketId, notitie.id, { bericht: bericht.trim() })
        );

        if (!result.success) return;

        replaceMessage(result.data);
        notitieId.value = null;
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
    <section>
        <br>
        <button v-if="!showNotitieForm" type="button" @click="showNotitieForm = true">Notitie toevoegen</button>

        <h2>notities:</h2>
        <ReactionForm v-if="showNotitieForm" @submit="submitNotitie" />
        <button v-if="showNotitieForm" type="button" @click="showNotitieForm = false">Annuleren</button>
        <p v-if="notities.length === 0">Nog geen notities...</p>
        <div v-for="notitie in notities" :key="notitie.id">
            <strong>{{ notitie.user }}</strong>
            <div v-if="notitieId === notitie.id">
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