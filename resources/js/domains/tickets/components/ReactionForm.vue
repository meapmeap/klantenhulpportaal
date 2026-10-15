<script setup lang="ts">
    import { ref, watch } from 'vue';

    const props = defineProps<{initialMessage?: string;}>();
    const emit = defineEmits<{submit: [message: string]; cancel: [];}>();

    const reactie = ref(props.initialMessage ?? '');

    watch(() =>props.initialMessage, (message) =>{
        reactie.value = message ?? '';
    });

    const handleSubmit = () => {
        if(!reactie.value.trim()) {return;}

        emit('submit', reactie.value);
    }
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <label for="reactie">Bericht:</label>
        <br>
        <textarea id="reactie" v-model="reactie" required></textarea>
        <br>
        <button type="submit">Opslaan</button>
        <button v-if="initialMessage !== undefined" type="button" @click="emit('cancel')">
            Annuleren
        </button>
    </form>
</template>