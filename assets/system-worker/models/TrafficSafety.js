/** @format */

import Model from "../base/Model";
import axios from "axios";
import moment from "moment";
import Snackbar from "../facades/Snackbar";

export default class TrafficSafety extends Model {
    /**
     * @type {boolean}
     */
    formData = true;

    /**
     * @type {string}
     */
    scope = "traffic-safety";

    /**
     * @type {string}
     */
    primaryKey = "car_id";

    /**
     * @type {string[]}
     */
    hidden = [
        "learnStatus",
        "baseUrl",
        "classes",
        "inspection_scan",
        "insurance_scan",
        "pts_file_orig",
        "sts_file_orig"
    ];

    /**
     * @type {object}
     */
    rules = {
        class_ids: "required",
        mark: "required|max:100",
        model: "required|max:100",
        year: "required",
        status_id: "required",
        entity_id: "required",
        insurance_date: "",
        insurance_expiration_date: "",
        insurance_scan_file: "ext:jpeg,jpg,png|size:512",
        inspection_date: "",
        inspection_expiration_date: "",
        inspection_scan_file: "ext:jpeg,jpg,png|size:512",
        vin_code: "required|min:6|max:32",
        body_number: "min:6|max:32",
        vehicle_licence_number: "required|min:6|max:32",
        vehicle_licence_date: "",
        color: "required|max:100",
        state_license_plate: "required|max:9",
        speedometer: "required",
        garage_number: "required",
        registration_date: "required",
        sts_number: "min:6|max:32",
        pts_number: "min:6|max:32",
        sts_file: { required: true, ext: ["jpeg", "jpg", "png"] },
        pts_file: { required: true, ext: ["jpeg", "jpg", "png"] },
        images: { required: true, ext: ["jpeg", "jpg", "png"] }
    };

    /**
     * @param car
     */
    constructor(car = {}) {
        super("traffic-safety", process.env.MIX_APP_WORKER_URL, "", {
            create: "store",
            update: "update",
            delete: "delete",
            deletes: "deletes",
        });
        this.car_id = car.car_id || 0;
        this.park_id = car.park_id || null;
        this.class = car.class || [];
        this.class_ids = car.class_ids || [];
        this.mark = car.mark || null;
        this.model = car.model || null;
        this.year = car.year ? moment(car.year, 'YYYY') : null;
        this.status_id = car.status_id || null;
        this.inspection_date = car.inspection_date || null;
        this.inspection_expiration_date = car.inspection_expiration_date || null;
        this.inspection_scan = car.inspection_scan || null;
        this.inspection_scan_file = car.inspection_scan_file || null;
        this.insurance_date = car.insurance_date || null;
        this.insurance_expiration_date = car.insurance_expiration_date || null;
        this.insurance_scan = car.insurance_scan || null;
        this.insurance_scan_file = car.insurance_scan_file || null;
        this.vin_code = car.vin_code || null;
        this.entity_id = car.entity_id || null;
        this.body_number = car.body_number || null;
        this.vehicle_licence_number = car.vehicle_licence_number || null;
        this.vehicle_licence_date = car.vehicle_licence_date || null;
        this.registration_date = car.registration_date || null;
        this.color = car.color || null;
        this.state_license_plate = car.state_license_plate || null;
        this.speedometer = car.speedometer || null;
        this.garage_number = car.garage_number || null;

        this.sts_number = car.sts_number || null;
        this.pts_number = car.pts_number || null;
        this.pts_file = [];
        this.pts_file_orig = car.pts_file || null;
        this.sts_file = [];
        this.sts_file_orig = car.sts_file || null;
        this.images = [];
        this.images_orig = car.images || [];

        this.setClassIds();
    }

    setClassIds() {
        this.class_ids = this.class.ids ? this.class.ids.map(x => Number(x)) : [];
    }

    updateInspection() {
        let data = new FormData();
        data.append("car_id", this.car_id);
        data.append("inspection_date", this.inspection_date);
        data.append("inspection_expiration_date", this.inspection_expiration_date);
        this.inspection_scan_file ? data.append("inspection_scan_file", this.inspection_scan_file) : null;

        return axios.post(process.env.MIX_APP_WORKER_URL + "traffic-safety/inspection/update", data);
    }

    updateInsurance() {
        let data = new FormData();
        data.append("car_id", this.car_id);
        data.append("insurance_date", this.insurance_date);
        data.append("insurance_expiration_date", this.insurance_expiration_date);
        this.insurance_scan_file ? data.append("insurance_scan_file", this.insurance_scan_file) : null;

        return axios.post(process.env.MIX_APP_WORKER_URL + "traffic-safety/insurance/update", data);
    }

    downloadScan(carId, type) {
        axios
            .get(`${process.env.MIX_APP_WORKER_URL}traffic-safety/download-scan/${carId}/${type}`, {
                responseType: "blob",
            })
            .then(file => {
                let blob = new Blob([file.data], { type: "application/pdf" });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "test_" + type + carId + ".pdf";
                link.click();
                link.remove();
            })
            .catch(error => {
                Snackbar.error(error.response.data.message);
            });
    }
}
