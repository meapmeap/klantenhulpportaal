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
    <form @submit.prevent="handleSubmit" class="column">
        <label for="reactie">Bericht:</label>
        
        <textarea id="reactie" v-model="reactie" required class="input-field"></textarea>
        
        <div class="row">
            <button type="submit">Opslaan</button>
            <button type="button" @click="emit('cancel')">Annuleren</button>
       </div> 
    </form>
</template>