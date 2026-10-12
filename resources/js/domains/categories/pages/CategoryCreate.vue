<script setup lang="ts">
    import Form from '../components/CategoryForm.vue';
    import { useRouter } from 'vue-router';
    import { ref } from 'vue';
    import { categorieStore, NewCategorie } from '../store';

    const router = useRouter();

    const categorie = ref<NewCategorie>({naam: '', beschrijving: ''});

    const handleSubmit = async (data: NewCategorie) => {
        await categorieStore.actions.create(data);
        router.push({name: 'categories.overview'});
    }
</script>

<template>
    <div>
        <h2>Nieuwe categorie</h2>
        <Form :categorie="categorie" @submit="handleSubmit" />
    </div>
</template>