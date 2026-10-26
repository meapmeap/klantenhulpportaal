import { ref, onMounted } from 'vue';
import type { Message, NewMessage } from '../store';
import { destroyMessage, destroyErrors, handleApiAction } from '../../../services/error';

interface MessageActions {
    getAll: (ticketId: number) => Promise<Message[]>;
    create: (ticketId: number, message: NewMessage) => Promise<Message>;
    update: (ticketId: number, messageId: number, message: NewMessage) => Promise<Message>;
    delete: (ticketId: number, messageId: number) => Promise<void>;
}

export function useTicketMessages(ticketId: number, actions: MessageActions) {
    const messages = ref<Message[]>([]);
    const editingMessageId = ref<number | null>(null);

    const loadMessages = async () => {
        const result = await handleApiAction(() => actions.getAll(ticketId));

        if (result.success) {
            messages.value = result.data;
        }
    };

    onMounted(loadMessages);

    const addMessage = async (bericht: string) => {
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() => actions.create(ticketId, { bericht: bericht.trim() }));

        if (!result.success) return;

        messages.value.push(result.data);
    };

    const editMessage = async (message: Message, bericht: string) => {
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() => actions.update(ticketId, message.id, { bericht: bericht.trim() }));

        if (!result.success) return;

        const index = messages.value.findIndex(item => item.id === message.id);

        if (index !== -1) {
            messages.value[index] = result.data;
        }

        editingMessageId.value = null;
    };

    const cancelEdit = () => {
        editingMessageId.value = null;
    };

    const deleteMessage = async (message: Message) => {
        destroyErrors();
        destroyMessage();

        const result = await handleApiAction(() => actions.delete(ticketId, message.id));

        if (!result.success) return;

        messages.value = messages.value.filter(item => item.id !== message.id);
    };

    return {
        messages,
        editingMessageId,
        loadMessages,
        addMessage,
        editMessage,
        cancelEdit,
        deleteMessage
    };
}