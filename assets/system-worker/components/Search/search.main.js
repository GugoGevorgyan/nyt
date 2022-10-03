/** @format */

export default {
    name: "search",

    date() {
        return {
            selectedItem: 1,
            items: [
                { text: "Real-Time", icon: "mdi-clock" },
                { text: "Audience", icon: "mdi-account" },
                { text: "Conversions", icon: "mdi-flag" },
            ],
        };
    },

    computed: {
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                localStorage.setItem("color_mode", mode);
                this.$store.state.dark = mode;
            },
        },
    }
};
