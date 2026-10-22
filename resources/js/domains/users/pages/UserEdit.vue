<script setup lang="ts">
    import { useRoute, useRouter } from 'vue-router';
    import Form from '../components/UserForm.vue';
    import { userStore, NewUser } from '../store';

    const route = useRoute();
    const router = useRouter();

    const userId = Number(route.params.id);
    const user = userStore.getters.getById(userId);

    const handleSubmit = async (data: NewUser) => {
        await userStore.actions.update(userId, {...data, id: userId});
        router.push({ name: 'users.overview' });
    }
</script>

<template>
    <div>
        <h2>Gegevens van gebruiker wijzigen</h2>
        <Form v-if="user" :user="user" :is-editing="true" @submit="handleSubmit" />
    </div>
</template>