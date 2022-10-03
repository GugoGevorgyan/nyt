/** @format */

export default {
    mini: "true" === localStorage.getItem("navigate"),
    phoneMask: "",

    worker: {
        worker_id: null,
        franchise_id: null,
        is_admin: false,
        name: "",
        surname: "",
        patronymic: "",
        email: "",
        rating: null,
        logged: false,
        in_session: false,
    },

    auth: {
        check: false,
        user: null,
    },

    snackbar: {
        text: "",
        show: false,
        color: undefined,
        action: {},
    },

    initUrl: "",
    roles: [],
    permissions: [],
    menu: {},
    modules: [],

    messenger: {
        toggleMsgContent: false,
        toggleChatContentLoader: false,
        msgNotifications: [],
        chatWorkers: [],
    },

    dashboard: {
        session: true,
    },

    map: undefined,
    dark: "true" === localStorage.getItem("color_mode"),
};
