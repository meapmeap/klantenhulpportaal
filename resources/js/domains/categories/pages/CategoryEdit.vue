<script setup lang="ts">
    import { useRoute, useRouter } from 'vue-router';
    import { Categorie, categorieStore } from '../store';
    import Form from '../components/CategoryForm.vue'

    const route = useRoute();
    const router = useRouter();

    const categorieId = Number(route.params.id);
    const categorie = categorieStore.getters.getById(categorieId);

    const handleSubmit = async (data: Categorie) => {
        await categorieStore.actions.update(categorieId, data);
        router.push({name: 'categories.overview'});
    }
</script>

<template>
    <div>
        <h2>Categorie aanpassen.</h2>
        <Form v-if="categorie" :categorie="categorie" @submit="handleSubmit" />
    </div>
</template>