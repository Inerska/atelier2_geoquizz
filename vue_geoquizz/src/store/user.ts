import { defineStore } from 'pinia';
import { User } from '@/models/User';

export const useUserStore = defineStore('userStore', {
    state() {
        return {
            user : {
                loggedIn: false,
                profileId: null,
                accessToken: null,
                refreshToken: null,
            }
        }
    },
    getters: {
        isLoggedIn(state) {
            return state.user.loggedIn;
        },
        getProfileId(state) {
            return state.user.profileId;
        },
    },
    actions: {
        loginUser(user) {
            this.user = user;
        },
        logoutUser() {
            this.user.loggedIn = false;
            this.user.profileId = null;
            this.user.accessToken = null;
            this.user.refreshToken = null;
        },
    },
});
