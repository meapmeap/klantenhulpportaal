<script setup lang="ts">
    import { ref } from 'vue';
    import { authStore } from '../../../services/store/auth';
    import { useRouter } from 'vue-router';
    import { getErrorByProperty, getMessage, setErrorBag, destroyErrors, destroyMessage, handleApiError } from '../../../services/error';

    const email = ref('');
    const password = ref('');
    const password_confirmation = ref('');
    const firstName = ref('');
    const lastName = ref('');
    const phone = ref('');

    const router = useRouter();

    const register = async () => {
        destroyErrors();
        destroyMessage(); 

        if (password.value !== password_confirmation.value) {
            setErrorBag({ password_confirmation: ['Wachtwoorden zijn niet gelijk.'] });
            return;
        }
        
        try { 
            await authStore.register( firstName.value, lastName.value, email.value, password.value, phone.value ); 
            router.push({ name: 'profile.login' }); 
        } catch (errorResponse) { 
            handleApiError(errorResponse);
        }
    }
</script>

<template>
    <section class="column">
        <h2>Vul de volgende gegevens in, klik vervolgens op registreren om een account aan te maken.</h2>

        <p v-if="getMessage">{{ getMessage }}</p>
        
        <form @submit.prevent="register" class="column">
            <label for="email">Email adres:</label>
            <input id="email" v-model="email" type="email" required class="input-field" />
            <p v-if="getErrorByProperty('email_adres').value">{{ getErrorByProperty('email_adres').value?.join(', ') }}</p>

            <label for="firstname">Voornaam:</label>
            <input id="firstname" v-model="firstName" type="text" required class="input-field" />
            <p v-if="getErrorByProperty('voornaam').value">{{ getErrorByProperty('voornaam').value?.join(', ') }}</p>

            <label for="lastname">Achternaam:</label>
            <input id="lastname" v-model="lastName" type="text" required class="input-field" />
            <p v-if="getErrorByProperty('achternaam').value">{{ getErrorByProperty('achternaam').value?.join(', ') }}</p>

            <label for="phone">Telefoonnummer:</label>
            <input id="phone" v-model="phone" type="tel" class="input-field" />
            <p v-if="getErrorByProperty('telefoonnummer').value">{{ getErrorByProperty('telefoonnummer').value?.join(', ') }}</p>

            <label for="password">Wachtwoord:</label>
            <input id="password" v-model="password" type="password" required class="input-field" />
            <p v-if="getErrorByProperty('wachtwoord').value">{{ getErrorByProperty('wachtwoord').value?.join(', ') }}</p>

            <label for="password_confirmation">Herhaal wachtwoord:</label>
            <input id="password_confirmation" v-model="password_confirmation" type="password" required class="input-field" />
            <p v-if="getErrorByProperty('password_confirmation').value">{{ getErrorByProperty('password_confirmation').value?.join(', ') }}</p>

            <button type="submit">Registreren</button>
        </form>
    </section>
</template>