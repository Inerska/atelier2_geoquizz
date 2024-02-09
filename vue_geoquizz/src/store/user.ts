import { defineStore } from 'pinia';

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
        loginUser(profileId, accessToken, refreshToken) {
            this.user.loggedIn = true;
            this.user.profileId = profileId;
            this.user.accessToken = accessToken;
            this.user.refreshToken = refreshToken;
        },
        logoutUser() {
            this.user.loggedIn = false;
            this.user.profileId = null;
            this.user.accessToken = null;
            this.user.refreshToken = null;
        },
    },
});
