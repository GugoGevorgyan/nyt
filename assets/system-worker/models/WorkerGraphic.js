/** @format */

import Model from "../base/Model";

export default class WorkerGraphic extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "schedule";

    /**
     * @type {string}
     */
    primaryKey = "worker_graphic_id";

    /**
     * @type {string}
     */
    adminUrl = process.env.MIX_ADMIN_URN;

    /**
     * @type {{tutor_id: {exists: {col: string, table: string}, required: boolean}, license_revocation: string, license_qr_code: string, license_type: string, penalty: string, photo: string, license_revocation_cause: string, passport_serial: string, experience: string, license_file: string, passport_expiry: string, passport_scan: string, registration_date: string, phone: string, learn_status: string, learn_end: string, passwordConfirm: string, surname: string, name: string, learn_start: string, penalty_size: string, license_code: string, email: string}}
     */
    rules = {};

    /**
     * @param schedules
     * @param onlyIds
     */
    constructor(schedules = {}, onlyIds = false) {
        super();

        this.work_days_count = schedules.work_days_count || null;
        this.work_days = schedules.work_days || null;
        this.weekend_days_count = schedules.weekend_days_count || null;
        this.weekend_days = schedules.weekend_days || null;
        this.opening_hours = schedules.opening_hours || null;
    }
}
