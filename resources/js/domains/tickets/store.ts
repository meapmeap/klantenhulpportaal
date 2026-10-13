import { storeModuleFactory } from "../../services/store";
import { getRequest, postRequest } from "../../services/http";

export interface Ticket {
    id: number;
    titel: string;
    categorie_id: number;
    categorie: string | null;
    status: string;
    created_by: number;
    user_id: number | null;
    created_at: string;
    updated_at: string;
    creator: string | null;
    admin: string | null;
}

export type NewTicket = Pick<Ticket, 'titel' | 'categorie_id' | 'user_id'>;

export interface Message {
    id: number;
    bericht: string;
    user_id: number;
    user: string;
    ticket_id: number;
    created_at: string;
    updated_at: string;
}

export type NewMessage = Pick<Message, 'bericht'>;

export const statuses = ['Nieuw', 'In behandeling', 'Afgehandeld']

export const ticketStore = storeModuleFactory<Ticket, NewTicket>('tickets');

export const getReacties = async (ticketId: number) => {
    const { data } = await getRequest(`/api/tickets/${ticketId}/reacties`);

    return data;
};

export const addReactie = async (ticketId: number, reactie: NewMessage) => {
    const { data } = await postRequest(`/api/tickets/${ticketId}/reacties`, reactie);

    return data;
};