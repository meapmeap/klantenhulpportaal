<script setup lang="ts" generic="Item extends Categorie | NewCategorie">
    import { Categorie, NewCategorie } from '../store';
    import { ref } from 'vue';

    const props = defineProps<{ categorie: Item }>();
    const emit = defineEmits<{ submit: [categorie: Item]; }>();

    const form = ref({ ...props.categorie });

    const handleSubmit = () => { emit('submit', form.value) }
</script>

<template>
    <div class="section">
        <form @submit.prevent="handleSubmit" class="column">
            <label>Categorie: </label>
            <input v-model="form.naam" required class="input-field" />

            <label>Korte Omschrijving: </label>
            <textarea v-model="form.beschrijving" class="input-field"></textarea>

            <div class="row">
                <button type="submit">Opslaan</button> | 
                <router-link :to="{name: 'categories.overview'}">Annuleren</router-link>
            </div>
        </form>
    </div>
</template>