/** @format */

import Form from "../base/Form";
import axios from "axios";

export default class Dashboard extends Form {
    tokenKeyName = "yui_sess_key";

    constructor(formData = {}) {
        super();

        this.nickname = formData.nickname;
        this.password = formData.password;
    }

    rules = {
        nickname: {
            required: true,
            min: 3,
            exists: {
                table: "system_workers",
                col: "nickname",
            },
        },
        password: {
            required: true,
        },
    };

    stopSession() {
        return axios.post("stop-session");
    }

    sessionInRequest(nickname, password) {
        return axios.post("/app/worker/start-session", {
            nickname: nickname,
            password: password,
            token: localStorage.getItem(this.tokenKeyName),
        });
    }
}
