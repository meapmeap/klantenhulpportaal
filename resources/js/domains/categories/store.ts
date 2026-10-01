import { storeModuleFactory } from "../../services/store";

export interface Categorie {
    id: number;
    naam: string;
    beschrijving: string;
}

export type NewCategorie = Omit<Categorie, 'id'>;

export const categorieStore = storeModuleFactory<Categorie, NewCategorie>('categories');