/** @format */

import Model from "../base/Model";
import Permission from "./Permission";

export default class Role extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "role";

    /**
     * @type {string}
     */
    primaryKey = "role_id";

    /**
     * @type {string}
     */
    adminUrl = process.env.MIX_ADMIN_URN;

    /**
     * @type {{tutor_id: {exists: {col: string, table: string}, required: boolean}, license_revocation: string, license_qr_code: string, license_type: string, penalty: string, photo: string, license_revocation_cause: string, passport_serial: string, experience: string, license_file: string, passport_expiry: string, passport_scan: string, registration_date: string, phone: string, learn_status: string, learn_end: string, passwordConfirm: string, surname: string, name: string, learn_start: string, penalty_size: string, license_code: string, email: string}}
     */
    rules = {};

    /**
     * @param roles
     * @param onlyIds
     */
    constructor(roles = {}, onlyIds = false) {
        super();

        this.role_id = roles.role_id || null;
        this.module_id = roles.module_id || null;
        this.name = roles.name || null;
        this.alias = roles.alias || null;
        this.description = roles.description || null;

        this.permissions(roles.permissions || {});
    }

    permissions(permissions = {}) {
        this.hasMany(Permission, permissions);
    }
}
