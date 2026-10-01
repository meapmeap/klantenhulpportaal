import { storeModuleFactory } from "../../services/store";

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

export type NewTicket = Omit<Ticket, 'id' | 'status' | 'created_by' | 'created_at' | 'updated_at' | 'categorie' | 'creator' | 'admin'>;

export const ticketStore = storeModuleFactory<Ticket, NewTicket>('tickets');