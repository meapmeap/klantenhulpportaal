import { ref, computed } from "vue";
import { getRequest, postRequest, putRequest, deleteRequest } from "../http";
import { setMessage, destroyMessage } from "../error";

interface StoreItem { id: number; }

export const storeModuleFactory = <Item extends StoreItem, NewItem >(moduleName: string) => {
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
        },

        clearState: () => {
            state.value = {};
        }
    };

    const actions = {
        getAll: async (url = `/api/${moduleName}`) => {
            const response = await getRequest(url);

            if (!response.data) return;

            setters.setAll(response.data);
        },

        create: async (item: NewItem) => {
            const { data } = await postRequest(`/api/${moduleName}`, item);
            if (!data) return;
            state.value[data.id] = Object.freeze(data);
        },

        update: async (id: number, item: Item) => {
            const { data } = await putRequest(`/api/${moduleName}/${id}`, item);
            if (!data) return;
            state.value[data.id] = Object.freeze(data);
        },

        delete: async (id: number) => {
            destroyMessage();

            try {
                await deleteRequest(`/api/${moduleName}/${id}`);
                delete state.value[id];
            } catch (error: any) {
                setMessage(error.response?.data?.message);
            }
        }
    };

    return { getters, setters, actions };
};