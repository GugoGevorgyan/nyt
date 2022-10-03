/** @format */

export default class Collection {
    /** @type {Array} **/
    items = [];

    /**
     * Collection constructor
     *
     * @param {Array} items
     * @throws Error
     */
    constructor(items) {
        if (!Array.isArray(items)) throw Error(`argument "items" must be array ${typeof items} was given!`);

        this.items = items;
    }

    /**
     * Get all items
     *
     * @return {Array}
     */
    all() {
        return this.items;
    }
}
