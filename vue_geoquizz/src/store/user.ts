import { defineStore } from 'pinia';
import { User } from '@/models/User';

export const useUserStore = defineStore('userStore', {
    state() {
        return {
            user: null as User | null,
        }
    },
    getters: {
        isLoggedIn(state) {
            return state.user !== null;
        }
    },
    actions: {
        loginUser(user) {
            this.user = user;
        },
        logoutUser() {
            this.user = null;
        },
    },
});
