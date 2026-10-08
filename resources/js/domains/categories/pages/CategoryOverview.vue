<script setup>
    import { categorieStore } from '../store';
    import { authStore } from '../../../services/store/auth';
    import { computed, onMounted } from 'vue';

    const categories = categorieStore.getters.all;

    onMounted(async () => {
        await categorieStore.actions.getAll();
    })

    const isAdmin = computed(() => authStore.isAdmin.value);
</script>

<template>
    <div v-if="isAdmin">
        <button>
            Nieuwe categorie
        </button>
        <table>
            <tbody>
                <tr>
                    <th>Categorie</th>
                    <th>Beschrijving</th>
                    <th>acties</th>
                </tr>
                <tr v-for="categorie in categories" :key="categorie.id">
                    <td>{{ categorie.naam }}</td>
                    <td>{{ categorie.beschrijving }}</td>
                    <td>
                        <button>Bewerken</button> | 
                        <button>Verwijderen</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>