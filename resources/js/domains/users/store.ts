import { storeModuleFactory } from "../../services/store";

export interface Admin {
    id: number;
    voornaam: string;
    achternaam: string;
}

export type NewAdmin = never;

export const adminStore = storeModuleFactory<Admin, NewAdmin>('users');