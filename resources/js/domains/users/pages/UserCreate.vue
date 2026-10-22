<script setup lang="ts">
    import Form from '../components/UserForm.vue';
    import { useRouter } from 'vue-router';
    import { ref } from 'vue';
    import { userStore, NewUser } from '../store.js';

    const router = useRouter();

    const user = ref<NewUser>({
        voornaam: '',
        achternaam: '',
        email_adres: '',
        telefoonnummer: '',
        rol: '', 
        wachtwoord: ''
    })

    const handleSubmit = async (data: NewUser) => {
        await userStore.actions.create(data);
        router.push({ name: 'users.overview' });
    }
</script>

<template>
    <div>
        <h2>Nieuw account aanmaken</h2>
        <Form :user="user" @submit="handleSubmit" :is-editing="false" />
    </div>
</template>