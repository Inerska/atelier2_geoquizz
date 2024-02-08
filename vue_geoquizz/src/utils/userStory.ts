import { reactive } from 'vue';

const state = reactive({
    user: null
});

function setUser(userData) {
    state.user = userData;
}

function clearUser() {
    state.user = null;
}

export default {
    state,
    setUser,
    clearUser
};