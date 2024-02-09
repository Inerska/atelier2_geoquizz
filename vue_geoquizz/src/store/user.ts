import { defineStore } from 'pinia';
import { PartialAuthUser } from '../utils/types';

export const useUserStore = defineStore('userStore', {
    state() {
        return {
            user: null as PartialAuthUser | null,
        }
    },
    getters: {
        isLoggedIn(state) {
            return state.user !== null;
        }
    },
    actions: {
        loginUser(user: PartialAuthUser) {
            this.user = user;
        },
        logoutUser() {
            this.user = null;
        },
    },
});
