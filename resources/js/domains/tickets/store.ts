import { storeModuleFactory } from "../../services/store";

export interface Ticket {
    id: number;
    titel: string;
    categorie_id: number;
    status: string;
    created_by: number;
    created_at: string;
    updated_at: string;
}

export type NewTicket = Omit<Ticket, 'id'>;

export const ticketStore = storeModuleFactory<Ticket, NewTicket>('tickets');