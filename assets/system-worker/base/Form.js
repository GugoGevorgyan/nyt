/** @format */

import _ from "lodash";

export default class Form {
    /** @type {String} */
    scope = "";
    /** @type {Object} */
    rules = {};
    /** @type {Array} */
    hidden = [];

    pickerOptions = {
        shortcuts: [
            {
                text: "Last week",
                onClick(picker) {
                    let start = new Date();
                    let end = new Date();
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                    picker.$emit("pick", [start, end]);
                },
            },
            {
                text: "Last month",
                onClick(picker) {
                    let start = new Date();
                    start.setMonth(start.getMonth() - 1, 1);
                    let end = new Date();
                    end.setMonth(end.getMonth(), 0);
                    picker.$emit("pick", [start, end]);
                },
            },
            {
                text: "Current month",
                onClick(picker) {
                    let start = new Date();
                    start.setDate(1);
                    let end = new Date();
                    end.setMonth(end.getMonth() + 1, 0);
                    picker.$emit("pick", [start, end]);
                },
            },
        ],
    };

    /**
     * Build form hidden
     *
     * @param {Array} hidden
     * @return {Object}
     */
    build(hidden = []) {
        let defaults = [
            "scope",
            "rules",
            "service",
            "hidden",
            "except",
            "only",
            "formData",
            "configs",
            "deleteIds",
            "primaryKey",
        ]
            .concat(hidden)
            .concat(this.hidden);

        return _.omit(this, defaults);
    }

    /**
     * Server validation errors
     *
     * @param {Object} response
     * @param {Boolean} scoped
     * @return {Array}
     */
    static errors(response, scoped = false) {
        let err = [],
            field = "",
            msg = "";

        if (422 === response.status) {
            let errors = response.data.errors;

            for (let key in errors) {
                if (errors.hasOwnProperty(key)) {
                    field = key;
                    msg = errors[key][0];

                    err.push({ field, msg });
                }
            }
        }

        return err;
    }

    /**
     * @param event
     * @param property
     * @param preview
     */
    getFiles(event, property = null, preview = null) {
        for (let i = 0; i < event.target.files.length; i++) {
            let index = this[property].length;
            let file = event.target.files[i];
            this[property].push(file);
            this[preview][index] = null;
            this.getPreview(file, index, preview);
        }
    }

    /**
     * @param file
     * @param index
     * @param preview
     */
    getPreview(file, index, preview = null) {
        let reader = new FileReader();
        reader.onload = e => {
            this[preview].splice(index, 1, e.target.result);
        };
        reader.readAsDataURL(file);
    }

    /**
     * @param index
     * @param file
     * @param preview
     */
    removeFile(index, file = null, preview = null) {
        this[file].splice(index, 1);
        this[preview].splice(index, 1);
    }
}
