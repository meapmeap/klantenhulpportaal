import { ref, computed } from "vue";
import { getRequest, postRequest, putRequest, deleteRequest } from "../http";
import { setMessage, destroyMessage } from "../error";

interface StoreItem { id: number; }

export const storeModuleFactory = <Item extends StoreItem, NewItem extends Omit<Item, 'id'>>(moduleName: string) => {
    const state = ref<Record<number, Item>>({});

    const getters = {
        all: computed(() => Object.values(state.value)),
        getById: (id: number) => computed(() => state.value[id])
    };

    const setters = {
        setAll: (items: Item[]) => {
            for (const item of items) state.value[item.id] = Object.freeze(item);
        },

        deleteByItem: (item: Item) => {
            delete state.value[item.id];
        }
    };

    const actions = {
        getAll: async (url = moduleName) => {
            const { data } = await getRequest(url);
            if (!data) return;
            setters.setAll(data);
        },

        create: async (item: NewItem) => {
            const { data } = await postRequest(moduleName, item);
            if (!data) return;
            setters.setAll(data);
        },

        update: async (id: number, item: Item) => {
            const { data } = await putRequest(`${moduleName}/${id}`, item);
            if (!data) return;
            setters.setAll(data);
        },

        delete: async (id: number) => {
            destroyMessage();

            try {
                await deleteRequest(`${moduleName}/${id}`);
                delete state.value[id];
            } catch (error: any) {
                setMessage(error.response?.data?.message);
            }
        }
    };

    return { getters, setters, actions };
};