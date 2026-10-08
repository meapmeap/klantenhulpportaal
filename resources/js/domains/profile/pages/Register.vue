<script setup lang="ts">
    import { ref } from 'vue';
    import { authStore } from '../../../services/store/auth';
    import axios from 'axios';
    import { useRouter } from 'vue-router';

    const email = ref('');
    const password = ref('');
    const password_confirmation = ref('');
    const firstName = ref('');
    const lastName = ref('');
    const phone = ref('');

    const error = ref('');

    const router = useRouter();

    const register = async () => {
        error.value = ''; 
        
        if (password.value !== password_confirmation.value) { 
            error.value = 'Wachtwoorden zijn niet gelijk.'; 
            return; 
        } 
        
        try { 
            await authStore.register( firstName.value, lastName.value, email.value, password.value, phone.value ); 
            router.push({ name: 'profile.login' }); 
        } catch (errorResponse) { 
            error.value = 'Er is iets misgegaan.'; 
            if (axios.isAxiosError(errorResponse)) { 
                error.value = errorResponse.response?.data?.message ?? error.value; 
            } 
        }
    }
</script>

<template>
    <p v-if="error">{{ error }}</p>
    <h2>Vul de volgende gegevens in, klik vervolgens op registreren om een account aan te maken.</h2>
    <div>
        <form @submit.prevent="register">
            <label for="email">Email adres:</label>
            <input id="email" v-model="email" type="email" required />
            <br><br>
            <label for="firstname">Voornaam:</label>
            <input id="firstname" v-model="firstName" type="text" required />
            <br><br>
            <label for="lastname">Achternaam:</label>
            <input id="lastname" v-model="lastName" type="text" required />
            <br><br>
            <label for="phone">Telefoonnummer:</label>
            <input id="phone" v-model="phone" type="tel" />
            <br><br>
            <label for="password">Wachtwoord:</label>
            <input id="password" v-model="password" type="password" required />
            <br><br>
            <label for="password_confirmation">Herhaal wachtwoord:</label>
            <input id="password_confirmation" v-model="password_confirmation" type="password" required />
            <br><br>
            <button type="submit">Registreren</button>
        </form>
    </div>
</template>