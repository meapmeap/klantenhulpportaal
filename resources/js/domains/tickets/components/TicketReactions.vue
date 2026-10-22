<script setup lang="ts">
    import { ref, onMounted, computed } from 'vue';
    import { Message, reactieActions } from '../store';
    import { formatDate } from '../../../services/utils/formatDate';
    import { destroyMessage, destroyErrors, handleApiAction } from '../../../services/error';
    import ReactionForm from './ReactionForm.vue';
    import { authStore } from '../../../services/store/auth';

    const props = defineProps<{ ticketId: number }>();
    const isAdmin = computed(() => authStore.isAdmin.value);

    const reacties = ref<Message[]>([]);
    const showReactieForm = ref(false);
    const reactieId = ref<number | null>(null);

    onMounted(async () => {
        const result = await handleApiAction(() => reactieActions.getAll(props.ticketId));

        if (result.success) {
            reacties.value = result.data;
        }
    })

    const replaceMessage = (updatedMessage: Message) => {
        const index = reacties.value.findIndex(item => item.id === updatedMessage.id);

        if (index !== -1) {
            reacties.value[index] = updatedMessage;
        }
    };

    const submitReactie = async (bericht: string) => {
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() =>
            reactieActions.create(props.ticketId, { bericht: bericht.trim() })
        );

        if (!result.success) return;

        reacties.value.push(result.data);
        showReactieForm.value = false;
    };

    const startEditReactie = (reactie: Message) => {
        reactieId.value = reactie.id;
    };

    const cancelEditReactie = () => {
        reactieId.value = null;
    };

    const saveEditReactie = async (reactie: Message, bericht: string) => {
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() =>
            reactieActions.update(props.ticketId, reactie.id, { bericht: bericht.trim() })
        );

        if (!result.success) return;

        replaceMessage(result.data);
        reactieId.value = null;
    };
</script>

<template>
    <section>
        <br>
        <button v-if="!showReactieForm" type="button" @click="showReactieForm = true">Reactie toevoegen</button>

        <h2>reacties:</h2>
        <ReactionForm v-if="showReactieForm" @submit="submitReactie" />
        <button v-if="showReactieForm" type="button" @click="showReactieForm = false">Annuleren</button>
        <p v-if="reacties.length === 0">Nog geen reacties...</p>
        <div v-for="reactie in reacties" :key="reactie.id">
            <strong>{{ reactie.user }}</strong>
            <div v-if="reactieId === reactie.id">
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