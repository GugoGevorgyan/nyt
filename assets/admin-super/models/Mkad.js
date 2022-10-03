/** @format */

export default class Mkad {
    constructor(Mkad = {}) {
        this.tariff_type_id = Mkad.tariff_type_id || null;
        this.price_distance_1_15 = Mkad.price_distance_1_15 || null;
        this.price_distance_16_30 = Mkad.price_distance_16_30 || null;
        this.price_distance_31_60 = Mkad.price_distance_31_60 || null;
        this.price_distance_61_more = Mkad.price_distance_61_more || null;
        this.price_distance_1_15_minute = Mkad.price_distance_1_15_minute || null;
        this.price_distance_16_30_minute = Mkad.price_distance_16_30_minute || null;
        this.price_distance_31_60_minute = Mkad.price_distance_31_60_minute || null;
        this.price_distance_61_more_minute = Mkad.price_distance_61_more_minute || null;
        this.back = Mkad.back || null;
        this.back_minute = Mkad.back_minute || null;
        this.free_wait_every_stop = Mkad.free_wait_every_stop || null;
    }
}
