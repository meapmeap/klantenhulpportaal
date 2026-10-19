import { computed, ref } from 'vue';
import axios from 'axios';

interface ErrorBag {
    [property: string]: string[];
}

const errorBag = ref<ErrorBag>({});
const message = ref('');

export const getErrorBag = computed(() => errorBag.value);
export const getMessage = computed(() => message.value);

export const getErrorByProperty = (property: string) => computed(() => errorBag.value[property]);

export const setErrorBag = (bag: ErrorBag) => (errorBag.value = bag);
export const setMessage = (newMessage: string) => {
    message.value = newMessage || 'Er is een fout opgetreden.';
};

export const destroyErrors = () => (errorBag.value = {});
export const destroyMessage = () => (message.value = '');

export const handleApiError = (error: unknown) => {
    if (axios.isAxiosError(error)) {
        const response = error.response?.data;

        if (response?.errors) {
            setErrorBag(response.errors);
            destroyMessage();
        } else {
            destroyErrors();
            setMessage(response?.message || 'Er is een fout opgetreden.');
        }
    } else {
        destroyErrors();
        setMessage('Er is een onverwachte fout opgetreden.');
    }
};

type ActionResult<T> =
    | { success: true; data: T }
    | { success: false };

export const handleApiAction = async <T>(action: () => Promise<T>): Promise<ActionResult<T>> => {
    try {
        const data = await action();
        return { success: true, data };
    } catch {
        // De Axios-interceptor heeft de fout al afgehandeld.
        return { success: false };
    }
};