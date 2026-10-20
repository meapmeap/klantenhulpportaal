import { storeModuleFactory } from "../../services/store";
import { User } from "../../services/store/auth";

export type NewUser = Omit<User, 'id'> & { wachtwoord: string; };

export const userStore = storeModuleFactory<User, NewUser>('users');