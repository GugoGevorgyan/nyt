/** @format */

import CorporateLogin from "../../components/auth/CorporateLogin";
import ClientLogin from "../../components/auth/ClientLogin";

export default {
    components: {
        CorporateLogin,
        ClientLogin,
    },

    data: () => ({
        tab: null,
        items: [
            { title: "Клиент", url: "/login-client" },
            { title: "Корпоративный", url: "/login-corporate" },
        ],
        window: {
            width: 0,
            height: 0,
            heightDif: 150,
        },
    }),

    methods: {
        next() {
            const active = parseInt(this.active);
            this.active = 2 > active ? active + 1 : 0;
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },
    },

    props: {
        loginClientByPhone: {
            required: true,
        },
        loginClientByName: {
            required: true,
        },
        sendSmsRoute: {
            required: true,
        },
        corporateAdminLogin: {
            required: true,
        },
        mask: {
            required: true,
        },
    },

    beforeCreate() {
        if ("/login-client" === this.$route.path) {
            // this.$router.push('/login-client');
        } else {
            // this.$router.push('/login-corporate');
        }
    },

    created() {
        this.handleResize();
        localStorage.setItem('mask', this.mask);
        window.addEventListener("resize", this.handleResize);
    },
};
