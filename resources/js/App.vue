<script setup>
    import { authStore } from './services/store/auth';
    import { ticketStore } from './domains/tickets/store';
    import { useRouter } from 'vue-router';
    import { computed, onMounted } from 'vue';

    const router = useRouter();

    const isLoggedIn = computed(() => authStore.isLoggedIn.value);
    const isAdmin = computed(() => authStore.isAdmin.value);

    onMounted(async () => {
        await authStore.fetchUser();
    });

    const logout = async() => {
        try{
            await authStore.logout();
            ticketStore.setters.clearState();
            router.push({ name: 'profile.login' });
        } catch (error){
            console.error('Uitloggen mislukt:', error);
        }
    };
</script>

<template>
    <nav>
        <button v-if="isLoggedIn" @click="logout">Uitloggen | </button>
        <router-link v-if="isLoggedIn" :to="{name: 'tickets.create'}"> Nieuwe ticket aanmaken | </router-link>
        <router-link v-if="isLoggedIn" :to="{name: 'tickets.overview'}">Ticket overzicht</router-link>
        <router-link v-if="isAdmin" :to="{name: 'categories.overview'}"> | Categorieen overzicht | </router-link>
    </nav>
    <router-view></router-view>
</template>