<script setup lang="ts">
    import { ref } from 'vue';  
    import { useRouter } from 'vue-router';
    import { authStore } from '../../../services/store/auth';
    import { getMessage, destroyErrors, destroyMessage, handleApiError } from '../../../services/error';

    const email = ref('');
    const password = ref('');
    
    const router = useRouter();

    const login = async () => {
        destroyErrors();
        destroyMessage();

        try {
            await authStore.login(email.value, password.value);
            router.push({name: 'tickets.overview'});
        } catch (errorResponse) {
            handleApiError(errorResponse);
        }
    }
</script>

<template>
    <section class="column">
        <h2>Log in.</h2>

        <p v-if="getMessage">{{ getMessage }}</p>
        
        <form @submit.prevent="login" class="column">
            <label for="username">Email adres:</label>
            <input id="username" v-model="email" type="email" required class="input-field" />

            <label for="password">wachtwoord:</label>
            <input id="password" v-model="password" type="password" required class="input-field" />

            <button type="submit">Inloggen</button>

            <div class="row">
                <router-link :to="{name: 'profile.forgot'}">Wachtwoord vergeten?</router-link> | 
                <router-link :to="{name: 'profile.register'}">Nieuw account aanmaken.</router-link>
            </div>
        </form>
    </section>
</template>