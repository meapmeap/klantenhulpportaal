<script setup lang="ts">
    import { ref } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { postRequest } from '../../../services/http';

    const route = useRoute();
    const router = useRouter();

    const password = ref('');
    const password_confirmation = ref('');
    const message = ref('');
    const error = ref('');

    const resetPassword = async () => {
        message.value = '';
        error.value = '';

        try {
            const response = await postRequest('/api/reset-password', {
                email_adres: route.query.email,
                token: route.query.token,
                password: password.value,
                password_confirmation: password_confirmation.value,
            });

            message.value = response.data.message;

            password.value = '';
            password_confirmation.value = '';
        } catch (err: any) {
            error.value = err.response?.data?.message ?? 'Er is iets misgegaan bij het wijzigen van je wachtwoord.';
        }
    };
</script>

<template>
    <div>
        <h2>Nieuw wachtwoord instellen</h2>

        <form @submit.prevent="resetPassword">
            <label for="password">Nieuw wachtwoord:</label>
            <input id="password" v-model="password" type="password" required autocomplete="new-password" />

            <label for="password_confirmation">Herhaal je wachtwoord:</label>
            <input id="password_confirmation" v-model="password_confirmation" type="password"  required autocomplete="new-password" />

            <button type="submit">Wachtwoord wijzigen</button>
        </form>

        <p v-if="message">{{ message }}</p>
        <p v-if="error">{{ error }}</p>
    </div>
</template>