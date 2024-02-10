// Importez Pinia et définissez les interfaces nécessaires
import { defineStore } from 'pinia';
import { User } from './types';

interface UserState {
    user: User | null;
}
export const useUserStore = defineStore('userStore', {
    state: (): UserState => ({
        user: null,
    }),
    getters: {
        isLoggedIn: (state) => state.user !== null,
    },
    actions: {
        loginUser(user: User) {
            this.user = user;
        },
        logoutUser() {
            this.user = null;
        },
    },
});
