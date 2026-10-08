<script setup lang="ts">
    import { ref } from 'vue';
    import { useRoute } from 'vue-router';
    import { authStore } from '../../../services/store/auth';

    const route = useRoute();

    const password = ref('');
    const password_confirmation = ref('');
    const message = ref('');
    const error = ref('');

    const resetPassword = async () => {
        message.value = '';
        error.value = '';

        if (!route.query.email || !route.query.token) {
            error.value = 'De wachtwoordresetlink is ongeldig of onvolledig.';
            return;
        }

        try {
            const response = await authStore.resetPassword(
                route.query.email as string,
                route.query.token as string,
                password.value,
                password_confirmation.value
            );

            message.value = response.message;

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