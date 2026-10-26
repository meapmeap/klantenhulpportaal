<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import { statuses, ticketStore } from '../store';
    import { userStore } from '../../users/store';
    import { handleApiAction, destroyErrors, destroyMessage } from '../../../services/error';

    const props = defineProps<{ ticketId: number }>();

    const ticket = ticketStore.getters.getById(props.ticketId);
    const admins = userStore.getters.all;

    const selectedAdmin = ref<number | null>(ticket.value?.user_id ?? null);
    const selectedStatus = ref(ticket.value?.status ?? '');

    onMounted(async () => {
        await handleApiAction(() =>
            userStore.actions.getAll('/api/users/admins')
        );
    });

    const confirmChanges = async () => {
        if (!ticket.value) return;

        destroyErrors();
        destroyMessage();

        await handleApiAction(() =>
            ticketStore.actions.update(props.ticketId, {
                ...ticket.value!,
                user_id: selectedAdmin.value,
                status: selectedStatus.value
            })
        );
    };
</script>

<template>
    <div class="column-text">
        <h2>Admin opties:</h2>
        <div class="row">
            <label for="admin">Ticket toewijzen aan: </label>
            <select id="admin" v-model="selectedAdmin">
                <option :value="null">Geen admin</option>
                <option v-for="admin in admins" :key="admin.id" :value="admin.id">
                    {{ admin.voornaam }} {{ admin.achternaam }}
                </option>
            </select>
        </div>
        
        <div class="row">
            <label for="status">Status van ticket wijzigen: </label>
            <select id="status" v-model="selectedStatus">
                <option v-for="status in statuses" :key="status" :value="status">
                    {{ status }}
                </option>
            </select>
        </div>
        
        <button type="button" @click="confirmChanges">
            Wijzigingen opslaan
        </button>
    </div>
</template>