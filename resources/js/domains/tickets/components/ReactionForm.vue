<script setup lang="ts">
    import { ref } from 'vue';

    const emit = defineEmits<{submit: [message: string];}>();

    const reactie = ref('');

    const handleSubmit = () => {
        if(!reactie.value.trim()) {return;}

        emit('submit', reactie.value);
        reactie.value = '';
    }
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <label>Bericht:</label>
        <br>
        <textarea id="reactie" v-model="reactie" required></textarea>
        <br>
        <button type="submit">Versturen</button>
    </form>
</template>