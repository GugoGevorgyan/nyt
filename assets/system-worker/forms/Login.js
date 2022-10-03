/** @format */

import Form from "../base/Form";

export default class Login extends Form {
    rules = {
        nickname: {
            required: true,
            max: 64,
            min: 3,
            exists: {
                table: "system_workers",
                col: "nickname",
            },
        },
        password: {
            max: 64,
            min: 6,
            required: true,
        },
    };

    constructor(loginData = {}) {
        super();

        this.nickname = loginData.nickname || "";
        this.password = loginData.password || "";
    }
}
