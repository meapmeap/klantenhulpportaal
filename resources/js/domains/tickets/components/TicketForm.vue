<script setup lang="ts" generic="Item extends Ticket | NewTicket">
    import { onMounted, ref } from 'vue';
    import { Ticket, NewTicket } from '../store';
    import { categorieStore } from '../../categories/store';
    import { useRoute } from 'vue-router';

    const route = useRoute();

    const categories = categorieStore.getters.all;

    onMounted(() => {
        categorieStore.actions.getAll();
    });

    const ticketId = Number(route.params.id);

    const props = defineProps<{ ticket: Item }>();
    const emit = defineEmits<{ submit: [ticket: Item]; }>();

    const form = ref({ ...props.ticket });

    const handleSubmit = () => { emit('submit', form.value) }
</script>

<template>
    <div class="section">
        <form @submit.prevent="handleSubmit" class="column">
            <label>Omschrijving van het probleem: </label>
            <textarea v-model="form.titel" required class="input-field"></textarea>

            <div class="row">
                <label>Categorie: </label>
                <select v-model="form.categorie_id" required>
                    <option v-for="categorie in categories" :key="categorie.id" :value="categorie.id">
                    {{ categorie.naam }}
                    </option>
                </select>
            </div>

            <div class="row">
                <button type="submit">Versturen</button> | 
                <router-link v-if="ticketId" :to="{name: 'tickets.details', params: { id: ticketId }}">Annuleren</router-link>
                <router-link v-if="!ticketId" :to="{name: 'tickets.overview'}">Annuleren</router-link>
            </div>
        </form>
    </div>
</template>