<script setup>
    import { authStore } from './services/store/auth';
    import { useRouter } from 'vue-router';
    import { computed } from 'vue';

    const router = useRouter();

    const isLoggedIn = computed(() => authStore.isLoggedIn.value);

    const logout = async() => {
        try{
            await authStore.logout();

            router.push({ name: 'profile.login' });
        } catch (error){
            console.error('Uitloggen mislukt:', error);
        }
    };
</script>

<template>
    <nav>
        <button v-if="isLoggedIn" @click="logout">Uitloggen</button>
    </nav>
    <router-view></router-view>
</template>