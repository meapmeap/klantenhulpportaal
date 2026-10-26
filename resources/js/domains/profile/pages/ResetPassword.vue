<script setup lang="ts">
    import { ref } from 'vue';
    import { useRoute } from 'vue-router';
    import { authStore } from '../../../services/store/auth';
    import { getErrorByProperty, getMessage, setErrorBag, destroyErrors, destroyMessage, handleApiError } from '../../../services/error';

    const route = useRoute();

    const password = ref('');
    const password_confirmation = ref('');
    const message = ref('');

    const resetPassword = async () => {
        destroyErrors();
        destroyMessage();
        message.value = '';

        if (!route.query.email || !route.query.token) {
            setErrorBag({ token: ['De wachtwoordresetlink is ongeldig of onvolledig.'] });
            return;
        }

        if (password.value !== password_confirmation.value) {
            setErrorBag({ password_confirmation: ['Wachtwoorden zijn niet gelijk.'] });
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
        } catch (errorResponse) {
            handleApiError(errorResponse);
        }
    };
</script>

<template>
    <section class="column">
        <h2>Nieuw wachtwoord instellen</h2>

        <form @submit.prevent="resetPassword" class="column">
            <label for="password">Nieuw wachtwoord:</label>
            <input id="password" v-model="password" type="password" required autocomplete="new-password" class="input-field" />
            <p v-if="getErrorByProperty('password').value">{{ getErrorByProperty('password').value?.join(', ') }}</p>

            <label for="password_confirmation">Herhaal je wachtwoord:</label>
            <input id="password_confirmation" v-model="password_confirmation" type="password"  required autocomplete="new-password" class="input-field" />
            <p v-if="getErrorByProperty('password_confirmation').value">{{ getErrorByProperty('password_confirmation').value?.join(', ') }}</p>

            <p v-if="getErrorByProperty('token').value">{{ getErrorByProperty('token').value?.join(', ') }}</p>

            <button type="submit">Wachtwoord wijzigen</button>
        </form>

        <p v-if="message">{{ message }}</p>
        <p v-if="getMessage">{{ getMessage }}</p>
    </section>
</template>