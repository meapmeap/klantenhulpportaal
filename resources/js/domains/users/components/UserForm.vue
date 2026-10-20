<script setup lang="ts">
    import { NewUser } from "../store";
    import { ref } from "vue";

    const props = defineProps<{ user: NewUser; isEditing: boolean; }>();
    const emit = defineEmits<{ submit: [user: NewUser]; }>();

    const form = ref({ ...props.user });

    const handleSubmit = () => { emit('submit', form.value) }
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <label>Voornaam: </label>
        <input v-model="form.voornaam" required maxlength="40" />
        <br><br>

        <label>Achternaam: </label>
        <input v-model="form.achternaam" required maxlength="60" />
        <br><br>

        <label>E-mail adres: </label>
        <input v-model="form.email_adres" type="email" required  maxlength="255" />
        <br><br>

        <label>Telefoonnummer: </label>
        <input v-model="form.telefoonnummer" maxlength="20" />
        <br><br>

        <label>Rol: </label>
        <select v-model="form.rol" required> 
            <option value="user">Gebruiker</option> 
            <option value="admin">Admin</option> 
        </select>
        <br><br>

        <label>Wachtwoord: </label>
        <input v-model="form.wachtwoord" type="password" minlength="8" :required="!props.isEditing" autocomplete="new-password" /> 
        <p v-if="props.isEditing"> Laat leeg als je het wachtwoord niet wilt wijzigen. </p>
        <br><br>

        <button type="submit">Opslaan</button>
    </form>
</template>