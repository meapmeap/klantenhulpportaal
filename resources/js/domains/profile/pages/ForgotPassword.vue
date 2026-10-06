<script setup lang="ts">
    import { ref } from 'vue';
    import { authStore } from '../../../services/store/auth';

    const email_adres = ref('');
    const message = ref('');
    const error = ref('');

    const sendResetLink = async () => {
        message.value = '';
        error.value = '';
        try {
            message.value = await authStore.sendResetLink(email_adres.value);
        } catch (err: any) {
            error.value = err.response?.data?.message ?? 'Er is iets misgegaan bij het aanvragen van de wachtwoordreset.';
        } 
    };
</script>

<template>
    <div>
        <h2>Wachtwoord vergeten</h2>
        <form @submit.prevent="sendResetLink">
            <label for="email">Vul hier je E-mailadres in:</label>
            <input id="email" v-model="email_adres" type="email" required autocomplete="email" />

            <button type="submit">Stuur reset link</button>
        </form>

        <p v-if="message">{{ message }}</p>
        <p v-if="error">{{ error }}</p>
    </div>
</template>