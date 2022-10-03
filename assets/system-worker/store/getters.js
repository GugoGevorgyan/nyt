/** @format */

export default {
    toggleNavi: (state, getter) => {
        let local = Boolean(localStorage.getItem("navigate"));

        if (local) {
            return !local;
        }

        return state.mini;
    },
};
