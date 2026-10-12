<script setup lang="ts" generic="Item extends Categorie | NewCategorie">
    import { Categorie, NewCategorie } from '../store';
    import { ref } from 'vue';

    const props = defineProps<{ categorie: Item }>();
    const emit = defineEmits<{ submit: [categorie: Item]; }>();

    const form = ref({ ...props.categorie });

    const handleSubmit = () => { emit('submit', form.value) }
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <label>Categorie: </label>
        <input v-model="form.naam" required />
        <br><br>

        <label>Korte Omschrijving: </label>
        <textarea v-model="form.beschrijving"></textarea>
        <br><br>

        <button type="submit">Opslaan.</button>
        <router-link :to="{name: 'categories.overview'}">Annuleren</router-link>
    </form>
</template>