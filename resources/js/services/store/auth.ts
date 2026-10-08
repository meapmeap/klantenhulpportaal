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
        const { data } = await getRequest('/api/profile');
        
        user.value = data.data;
    } catch{
        user.value = null;
    }
};

const login = async (email_adres: string, wachtwoord: string) => {
    await getRequest('/sanctum/csrf-cookie');

    const { data } = await postRequest('/api/login', {
        email_adres,
        wachtwoord,
    });

    user.value = data.user;
};

const register = async (
    voornaam: string,
    achternaam: string,
    email_adres: string,
    wachtwoord: string,
    telefoonnummer?: string
) => {
    const { data } = await postRequest('/api/register', {
        voornaam,
        achternaam,
        email_adres,
        wachtwoord,
        telefoonnummer,
    });

    return data;
};

const sendResetLink = async (email_adres: string) => {
    const { data } = await postRequest('/api/forgot-password', { email_adres });

    return data.message;
};

const resetPassword = async (
    email_adres: string,
    token: string,
    password: string,
    password_confirmation: string
) => {
    const response = await postRequest('/api/reset-password', {
        email_adres,
        token,
        password,
        password_confirmation,
    });

    return response.data;
};

const logout = async() => {
    await postRequest('/api/logout', {});

    user.value = null;
};

export const authStore = {
    user, 
    isLoggedIn,
    isAdmin,
    fetchUser,
    login,
    register,
    sendResetLink,
    resetPassword,
    logout
};