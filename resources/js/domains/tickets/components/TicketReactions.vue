<script setup lang="ts">
    import { ref, computed } from 'vue';
    import { Message, reactieActions } from '../store';
    import { formatDate } from '../../../services/utils/formatDate';
    import ReactionForm from './ReactionForm.vue';
    import { authStore } from '../../../services/store/auth';
    import { useTicketMessages } from '../composables/useTicketMessages';

    const props = defineProps<{ ticketId: number }>();
    const isAdmin = computed(() => authStore.isAdmin.value);

    const showReactieForm = ref(false);

    const {
        messages: reacties,
        editingMessageId: editingReactieId,
        addMessage: submitReactie,
        editMessage: saveEditReactie,
        cancelEdit: cancelEditReactie
    } = useTicketMessages(props.ticketId, reactieActions);

    const startEditReactie = (reactie: Message) => {
        editingReactieId.value = reactie.id;
    };

    const submitReactieForm = async (bericht: string) => {
        const count = reacties.value.length;

        await submitReactie(bericht);

        if (reacties.value.length > count) {
            showReactieForm.value = false;
        }
    };
</script>

<template>
    <section class="section">
        <button v-if="!showReactieForm" type="button" @click="showReactieForm = true" class="column">Reactie toevoegen</button>

        <h2>reacties:</h2>
        <ReactionForm v-if="showReactieForm" @submit="submitReactieForm" @cancel="showReactieForm = false" />
        <p v-if="reacties.length === 0">Nog geen reacties...</p>
        <div v-for="reactie in reacties" :key="reactie.id">
            <strong>{{ reactie.user }}</strong>
            <div v-if="editingReactieId === reactie.id">
                <ReactionForm :initial-message="reactie.bericht" @submit="saveEditReactie(reactie, $event)" @cancel="cancelEditReactie" />
            </div>
            <div v-else>
                <p>{{ reactie.bericht }}</p>
                <p>{{ formatDate(reactie.created_at) }}</p>

                <button v-if="isAdmin" type="button" @click="startEditReactie(reactie)">Bewerken</button>
            </div>
        </div>
    </section>
</template>