/** @format */

import Model from "../base/Model";
import axios from "axios";

export default class ContractSigning extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "contract-signing";

    /**
     * @type {string}
     */
    primaryKey = "driver_contract_id";

    /**
     * @type {string[]}
     */
    hidden = [];

    /**
     * @type {object}
     */
    rules = {
        driver_id: "required",
        expiration_day: "required",
        work_start_day: "required",
    };

    /**
     * @param contract
     */
    constructor(contract = {}) {
        super("contract-signing", process.env.MIX_APP_WORKER_URL, "", {});

        this.driver_contract_id = contract.driver_contract_id || 0;
        this.driver_id = contract.driver_id || null;
        this.expiration_day = contract.expiration_day || null;
        this.work_start_day = contract.work_start_day || null;
    }

    getContractFile() {
        let formData = new FormData();
        formData.append("driver_id", this.driver_id);
        formData.append("expiration_day", this.expiration_day);
        formData.append("work_start_day", this.work_start_day);

        return axios.post("contract-signing/print-contract", formData);
    }

    signContract() {
        return axios.put("contract-signing/sign-contract/" + this.driver_contract_id, {});
    }
}
