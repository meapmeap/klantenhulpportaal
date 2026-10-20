<script setup lang="ts">
    import { categorieStore } from '../store';
    import { authStore } from '../../../services/store/auth';
    import { computed, onMounted } from 'vue';

    const categories = categorieStore.getters.all;

    onMounted(async () => {
        await categorieStore.actions.getAll();
    })

    const isAdmin = computed(() => authStore.isAdmin.value);

    const deleteCategory = async (id: number) => {
        const confirmed = confirm('Weet je zeker dat je deze categorie wil verwijderen?');

        if (!confirmed) {return;}

        await categorieStore.actions.delete(id);
    }

</script>

<template>
    <div v-if="isAdmin">
        <router-link :to="{name: 'categories.create'}">Nieuwe categorie maken.</router-link>
        <table>
            <thead>
                <tr>
                    <th>Categorie</th>
                    <th>Beschrijving</th>
                    <th>acties</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="categorie in categories" :key="categorie.id">
                    <td>{{ categorie.naam }}</td>
                    <td>{{ categorie.beschrijving }}</td>
                    <td>
                        <router-link :to="{ name: 'categories.edit', params: { id: categorie.id } }">Bewerken</router-link> | 
                        <button @click="deleteCategory(categorie.id)">Verwijderen</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>