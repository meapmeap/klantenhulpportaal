<script setup lang="ts">
    import { ref } from 'vue';
    import { authStore } from '../../../services/store/auth';
    import { getErrorByProperty, getMessage, destroyErrors, destroyMessage, handleApiError } from '../../../services/error';

    const email_adres = ref('');
    const message = ref('');

    const sendResetLink = async () => {
        destroyErrors();
        destroyMessage();
        message.value = '';
        
        try {
            message.value = await authStore.sendResetLink(email_adres.value);
        } catch (errorResponse) {
            handleApiError(errorResponse);
        } 
    };
</script>

<template>
    <section class="column">
        <h2>Wachtwoord vergeten</h2>

        <form @submit.prevent="sendResetLink" class="column">
            <label for="email">Vul hier je E-mailadres in:</label>
            <input id="email" v-model="email_adres" type="email" required autocomplete="email" class="input-field" />

            <p v-if="getErrorByProperty('email_adres').value">{{ getErrorByProperty('email_adres').value?.join(', ') }}</p>

            <button type="submit">Stuur reset link</button>
        </form>

        <p v-if="message">{{ message }}</p>
        <p v-if="getMessage">{{ getMessage }}</p>
    </section>
</template>