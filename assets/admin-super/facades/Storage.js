/** @format */

export default class Storage {
    /**
     * Create storage file url
     *
     * @param {string} path
     * @return {string}
     */
    static url(path) {
        return `/storage/${path}`;
    }
}
