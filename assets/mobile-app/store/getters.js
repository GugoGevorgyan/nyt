/** @format */

export default {
    isAuth(state) {
        return !!state.client.client_id;
    },
};
