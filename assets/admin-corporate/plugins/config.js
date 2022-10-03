import { csrf } from "../common/bootstrap";

/** @format */

export const ORDER_STATUS = {
    PENDING: 1,
    PROCESS: 2,
    PAUSED: 3,
    COMPLETED: 4,
    CANCELED: 5,
};

export const DRIVER_STATUS = {
    IS_FREE: 1,
    ON_ACCEPT: 2,
    DRIVER_ON_WAY: 3,
    IN_PLACE: 4,
    IN_ORDER: 5,
};

export const PAYMENT_TYPE = {
    CASH: 1,
    COMPANY: 2,
    CARD: 3,
};

export const BROADCASTING = {
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsPort: process.env.MIX_PUSHER_PORT,
    wssPort: process.env.MIX_PUSHER_PORT,
    forceTLS: process.env.MIX_PUSHER_TLS,
    disableStats: true,
    enabledTransports: ["ws", "wss"],
    disabledTransports: ["sockjs", "xhr_polling", "xhr_streaming"],
    namespace: "Src.Broadcasting.Broadcast.AdminCorporate",
    encrypted: true,
    csrfToken: csrf,
    authEndpoint: "broadcasting/auth",
    transports: ['websocket', 'polling'],
    autoConnect: true,
    rejectUnauthorized: "-",
    perMessageDeflate: "-"
};
