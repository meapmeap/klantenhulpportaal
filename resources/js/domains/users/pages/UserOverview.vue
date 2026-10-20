<script setup lang="ts">
    import { userStore } from '../store';
    import { onMounted } from 'vue';

    const users = userStore.getters.all;

    onMounted(async () => {
        await userStore.actions.getAll();
    });

    const deleteUser = async (id: number) => {
        const confirmed = window.confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');
        if (!confirmed) return;

        await userStore.actions.delete(id);
    }
</script>

<template>
    <div>
        <br>
        <router-link :to="{ name: 'users.create' }">Nieuw account aanmaken</router-link>
        <br><br>
        <h2>Alle gebruikers: </h2>
        <table>
            <thead>
                <tr>
                    <th>Voornaam</th>
                    <th>Achternaam</th>
                    <th>E-mailadres</th>
                    <th>Telefoonnummer</th>
                    <th>Rol</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.voornaam }}</td>
                    <td>{{ user.achternaam }}</td>
                    <td>{{ user.email_adres }}</td>
                    <td>{{ user.telefoonnummer || '-' }}</td>
                    <td>{{ user.rol === 'admin' ? 'Admin' : 'Gebruiker' }}</td>
                    <td>
                        <router-link :to="{ name: 'users.edit', params: { id: user.id } }">Bewerken</router-link> | 
                        <button @click="deleteUser(user.id)">Verwijderen</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>