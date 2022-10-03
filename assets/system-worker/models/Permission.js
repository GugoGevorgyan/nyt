/** @format */

import Model from "../base/Model";
import DriverInfo from "./DriverInfo";
import service from "./../base/Service";

export default class Permission extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "permission";

    /**
     * @type {string}
     */
    primaryKey = "permission_id";

    /**
     * @type {string}
     */
    adminUrl = process.env.MIX_ADMIN_URN;

    /**
     * @type {{tutor_id: {exists: {col: string, table: string}, required: boolean}, license_revocation: string, license_qr_code: string, license_type: string, penalty: string, photo: string, license_revocation_cause: string, passport_serial: string, experience: string, license_file: string, passport_expiry: string, passport_scan: string, registration_date: string, phone: string, learn_status: string, learn_end: string, passwordConfirm: string, surname: string, name: string, learn_start: string, penalty_size: string, license_code: string, email: string}}
     */
    rules = {};

    /**
     * @param permissions
     * @param onlyIds
     */
    constructor(permissions = {}, onlyIds = false) {
        super("driver-candidates", process.env.MIX_APP_WORKER_URL, "", {});

        if (onlyIds) {
            candidates.forEach(item => {
                this.deleteIds.push(item.driver_candidate_id);
            });
        }

        this.permission_id = permissions.permission_id || null;
        this.role_id = permissions.role_id || null;
        this.name = permissions.name || null;
        this.alias = permissions.alias || null;
        this.description = permissions.description || null;
    }
}
