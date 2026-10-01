import Login from "./pages/Login.vue";
import Register from "./pages/Register.vue";
import ForgotPassword from "./pages/ForgotPassword.vue";
import ResetPassword from "./pages/ResetPassword.vue";

export const profileRoutes = [
    { path: '/', component: Login, name: 'profile.login' },
    { path: '/register', component: Register, name: 'profile.register' },
    { path: '/forgot', component: ForgotPassword, name: 'profile.forgot' },
    { path: '/reset-password', component: ResetPassword, name: 'profile.reset' }
];