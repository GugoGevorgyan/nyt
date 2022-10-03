/** @format */

export default class Storage {
    /**
     * Create storage file url
     *
     * @param value
     * @return {string}
     */
    static url(value) {
        return process.env.MIX_APP_URL + `/storage/${value}`;
    }

    /**
     * Create storage file url
     *
     * @param value
     * @return {string}
     */
    static publicUrl(value) {
        return process.env.MIX_APP_URL + `/${value}`;
    }
}
