import { ref, computed } from "vue";
import { getRequest, postRequest } from "../http";

interface User {
    id: number;
    voornaam: string;
    achternaam: string;
    email_adres: string;
    telefoonnummer?: string;
    rol: string;
}

const user = ref<User | null>(null);
const isLoggedIn = computed(() => user.value !== null);
const isAdmin = computed(() => user.value?.rol === 'admin');

const fetchUser = async() => {
    try{
        const { data } = await getRequest('/profile');
        
        user.value = data.data;
    } catch{
        user.value = null;
    }
};

const login = async(email_adres: string, wachtwoord: string) => {
    await getRequest('/sanctum/csrf-cookie');

    const { data } = await postRequest('/login', {email_adres, wachtwoord});

    user.value = data.data;
};

const logout = async() => {
    await postRequest('/logout', {});

    user.value = null;
};

export const authStore = {
    user, 
    isLoggedIn,
    isAdmin,
    fetchUser,
    login,
    logout
};