/** @format */

import { csrf } from "../common/bootstrap";

/** @format */

export const ORDER_STATUS = {
    STATELESS: 1,
    PENDING_SEARCH: 2,
    ACCEPT_ORDER: 3,
    EXPECT_TAXI: 4,
    WAITING_TAXI: 5,
    IN_ORDER: 6,
    ASSESSMENT: 7,
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
    namespace: "Src.Broadcasting.Broadcast.Client",
    encrypted: true,
    csrfToken: csrf,
    authEndpoint: "/broadcasting/auth",
    transports: ["websocket", "polling"],
    autoConnect: true,
    rejectUnauthorized: "-",
    perMessageDeflate: "-",
};
