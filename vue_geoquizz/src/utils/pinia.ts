// Importez Pinia et définissez les interfaces nécessaires
import { defineStore } from 'pinia';
import { User } from './types';

interface UserState {
    user: User | null;
}

// Définissez le store
export const useUserStore = defineStore('userStore', {
    // Utilisez un état pour stocker les informations de l'utilisateur
    state: (): UserState => ({
        user: null, // L'utilisateur est initialement non connecté
    }),
    // Définissez les getters si nécessaire
    getters: {
        // Par exemple, un getter pour vérifier si l'utilisateur est connecté
        isLoggedIn: (state) => state.user !== null,
    },
    // Définissez les actions pour manipuler l'état
    actions: {
        // Action pour connecter un utilisateur
        loginUser(user: User) {
            this.user = user;
        },
        // Action pour déconnecter l'utilisateur
        logoutUser() {
            this.user = null;
        },
    },
});
