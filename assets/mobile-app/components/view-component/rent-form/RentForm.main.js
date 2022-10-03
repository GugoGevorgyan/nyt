/** @format */

export default {
    computed: {
        orderType: {
            get() {
                return this.$store.state.app.orderType;
            },
            set(type) {
                this.$store.state.app.orderType = type;
            },
        },
    },
};
