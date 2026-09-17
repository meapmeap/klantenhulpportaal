<script setup lang="ts">
    import { ref } from 'vue';  
    import { useRouter } from 'vue-router';
    import axios from 'axios';
    import { authStore } from '../../../services/store/auth';

    const email = ref('');
    const password = ref('');

    const error = ref('');
    
    const router = useRouter();

    const login = async () => {
        error.value = '';
        try {
            await authStore.login(email.value, password.value);
            router.push({name: 'tickets.overview'});
        } catch (errorResponse) {
            error.value = 'er is iets mis gegaan.';
            if(axios.isAxiosError(errorResponse)){
                error.value = errorResponse.response?.data?.message ?? error.value;
            }
        }
    }
</script>

<template>
    <div>
        <p v-if="error">{{ error }}</p><br>
        <h1>Log in.</h1><br>
        <form @submit.prevent="login">
            <label for="username">Email adres:</label>
            <input id="username" v-model="email" type="text" required />
            <br><br>
            <label for="password">wachtwoord:</label>
            <input id="password" v-model="password" type="password" required />
            <br><br>
            <button type="submit">Inloggen</button>
            <br><br>
            <router-link :to="{name: 'profile.forgot'}">Wachtwoord vergeten?</router-link> | 
            <router-link :to="{name: 'profile.register'}">Nieuw account aanmaken.</router-link>
        </form>
    </div>
</template>