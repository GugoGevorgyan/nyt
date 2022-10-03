/** @format */

import Model from "../base/Model";
import http from "axios";
import Snackbar from "../facades/Snackbar";

export default class DriverInfo extends Model {
    /**
     * @type {string}
     */
    scope = "info";

    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string[]}
     */
    hidden = [
        "baseUrl",
        "adminUrl",
        "passport_scan_orig"
    ];

    /**
     * @type {undefined}
     */
    licenseQrCodeFile = undefined;

    /**
     * @type {string}
     */
    primaryKey = "driver_info_id";

    /**
     * @type {{birthday: string, passport_serial: string, experience: string, zip_code: string, photo_file: {ext: [string, string, string], required: boolean}, passport_scan: string, surname: string, passport_issued_by: string, passport_scan_file: {ext: [string, string, string], required: boolean}, license_scan_file: {ext: [string, string, string], required: boolean}, email: string, citizen: string, license_qr_code: string, address: string, license_type: string, passport_when_issued: string, region_id: string, license_qr_code_file: string, passport_expiry: string, patronymic: string, passport_number: string, name: string, license_code: string, country_id: string, city_id: string}}
     */
    rules = {
        /*license*/
        license_type: "required",
        license_qr_code: "required|ext:jpeg,jpg,png",
        license_code: "required|min:6|max:16",
        license_date: "required",
        license_expiry: "required",

        /*passport*/
        passport_serial: "required|max:16|min:2",
        passport_number: "required|max:16|min:6",
        passport_issued_by: "required",
        passport_when_issued: { required: true },
        citizen: { required: true },

        /*address*/
        address: { required: true, min: "3" },

        /*personal*/
        name: "required|min:3|max:16",
        surname: "required|min:3|max:16",
        patronymic: "required|min:3|max:16",
        email: { required: false },
        birthday: { required: true },
        experience: { required: true, numeric: true, max: "2" },
        id_kis_art: { required: false },

        /*files*/
        photo_file: { required: true, ext: ["jpeg", "jpg", "png"] },
        license_scan_file: { required: false, ext: ["jpeg", "jpg", "png"] },
        license_qr_code_file: { required: false, ext: ["jpeg", "jpg", "png"] },
        passport_scan: { required: true, ext: ["jpeg", "jpg", "png"] },
    };

    /**
     * @param info
     */
    constructor(info = {}) {
        super("driver-info", process.env.MIX_APP_WORKER_URL, "", {
            create: "save-create",
            delete: "delete",
            deletes: "deletes",
            update: "update",
        });

        /*ids*/
        this.driver_info_id = info.driver_info_id || null;

        /*license*/
        this.license_scan = info.license_scan || null;
        this.license_qr_code = info.license_qr_code || null;
        this.license_code = info.license_code || null;
        this.license_type_ids = info.license_type_ids || [];
        this.license_date = info.license_date || null;
        this.license_expiry = info.license_expiry || null;

        /*passport*/
        this.passport_serial = info.passport_serial || null;
        this.passport_number = info.passport_number || null;
        this.passport_issued_by = info.passport_issued_by || null;
        this.passport_when_issued = info.passport_when_issued
            ? new Date(info.passport_when_issued).toISOString().slice(0, 10)
            : null;
        this.citizen = info.citizen || null;

        /*address*/
        this.address = info.address || null;

        /*personal*/
        this.photo = info.photo;
        this.birthday = info.birthday || null;
        this.name = info.name || null;
        this.surname = info.surname || null;
        this.patronymic = info.patronymic || null;
        this.email = info.email || null;
        this.experience = info.experience || null;
        this.id_kis_art = info.id_kis_art || null;

        /*files*/
        this.photo_file = info.photo_file || undefined;
        this.license_scan_file = info.license_scan_file || undefined;
        this.license_qr_code_file = info.license_qr_code_file || undefined;

        this.passport_scan = [];
        this.passport_scan_orig = info.passport_scan || null;
    }

    /**
     * @param infoId
     */
    downloadPspScan(infoId) {
        http.get(`/app/worker/driver-candidates/download-contract-passport-scan/${infoId}`, { responseType: "blob" })
            .then(file => {
                let blob = new Blob([file.data], { type: "application/pdf" });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "test.pdf";
                link.click();
                link.remove();
            })
            .catch(error => {
                Snackbar.error("Отсутствует");
            });
    }
}
