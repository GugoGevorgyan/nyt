/** @format */

import { csrf } from "../common/bootstrap";

/** @format */

export const TARIFF = {
    MIN: 1,
    KM: 2,
    KM_AND_MIN: 3,
    DESTINATION: 4,
};

export const TRANSACTION = {
    BALANCE: 1,
    DEBT: 2,
    WAYBILL: 3,
    ORDER: 4,
    CRASH: 5,
};

export const DRIVER_TYPES = {
    TENANT: 1,
    AGGREGATE: 2,
    ROLL: 3,
    CORPORATE: 4,
};

export const DRIVER_STATUS = {
    IS_FREE: 1,
    ON_ACCEPT: 2,
    ON_WAY: 3,
    IN_PLACE: 4,
    IN_ORDER: 5,
};

export const ORDER_STATUS = {
    PENDING: 1,
    PROCESS: 2,
    PAUSED: 3,
    COMPLETED: 4,
    CANCELED: 5,
};

export const BROADCAST = {
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsPort: process.env.MIX_PUSHER_PORT,
    wssPort: process.env.MIX_PUSHER_PORT,
    forceTLS: process.env.MIX_PUSHER_TLS,
    wsHost: window.location.hostname,
    disableStats: true,
    enabledTransports: ["ws", "wss"],
    disabledTransports: ["sockjs", "xhr_polling", "xhr_streaming"],
    namespace: "Src.Broadcasting.Broadcast.Worker",
    encrypted: true,
    csrfToken: csrf,
    authEndpoint: "/app/worker/broadcasting/auth",
    transports: ["websocket", "polling"],
    autoConnect: true,
    rejectUnauthorized: "-",
    perMessageDeflate: "-",
};
