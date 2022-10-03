/** @format */

import { mapMutations, mapState } from "vuex";

export default {
    name: "App",
    props: ["initialData"],

    data: () => ({
        tab: null,
        items: [
            { title: "Персональная информация", url: "/profile/info" },
            { title: "История заказов", url: "/profile/orders" },
            { title: "Список адресов", url: "/profile/address" },
            { title: "Компании", url: "/profile/companies" },
            { title: "Предварительные заказы", url: "/profile/preorders" },
        ],
    }),

    computed: {
        ...mapState(["client"]),
    },

    methods: {
        ...mapMutations(["initialize"]),
    },

    beforeCreate() {
        if (this.$route.path === "/profile") {
            this.$router.push("/profile/info");
        }
    },

    created() {
        this.initialize(this.initialData);
    },
};
