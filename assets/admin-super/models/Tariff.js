/** @format */
import axios from 'axios';
import Model from '../base/Model';
import {serialize} from 'object-to-formdata';

export default class Tariff extends Model {
    /**
     * @type {string}
     */
    primaryKey = 'tariff_id';

    hidden = ['rules'];

    components = [];

    options = [
        { value: 'regions_cities', component: 'RegionsCitiesFields', title: 'Регионально-городской тариф' },
        { value: 'destination', component: 'DestinationFields', title: 'Тариф из зоны А в зону Б' },
        { value: 'rent', component: 'RentFields', title: 'Аренда автомобиля (с водителем)' },
    ];

    optionComponent = null;

    optionTitle = null;

    optionsSelectable = [
        { text: 'Регионально-городской тариф', value: 'regions_cities', disabled: false },
    ];

    rules = {
        country_id: 'required',
        name: 'required',
        car_class_id: 'required',
        tariff_type_id: 'required',
        tariff_type: 'required',
        region_id: 'required',
        city: '',
        payment_type_id: 'required',
        paid_parking_client: 'required',
        tool_roads_client: 'required',
        is_default: 'required',
        status: 'required',
        free_wait_minutes: 'required|numeric|max_value:180',
        paid_wait_minute: 'required|numeric',
        minimal_price: 'required',
        rounding_price: 'required',
        option: 'required',
        date_from: 'required',
        date_to: 'required',

        rent: {
            hours: 'required|numeric',
            price: 'required|numeric',
            area_id: 'required',
        },

        regions_cities: {
            price_min: 'required|numeric',
            price_km: 'required|numeric',
            sitting_fee: '',
            sit_price_minute: 'required|numeric',
            sit_price_km: 'required|numeric',
            free_wait_stop_minutes: 'required|numeric|max_value:180',
            paid_wait_stop_minute: 'required|numeric',
            enable_speed_wait: '',
            speed_wait_limit: 'required|numeric|max_value:200',
            speed_wait_price_minute: 'required|numeric',
            minimal_distance_value: '',
            minimal_duration_value: '',
            area_id: 'required|numeric'
        },

        tariff_behind: {
            price_min: 'required|numeric',
            price_km: 'required|numeric',
            sitting_fee: '',
            sit_price_minute: 'required|numeric',
            sit_price_km: 'required|numeric',
            free_wait_stop_minutes: 'required|numeric|max_value:180',
            paid_wait_stop_minute: 'required|numeric',
            enable_speed_wait: '',
            speed_wait_limit: 'required|numeric|max_value:200',
            speed_wait_price_minute: 'required',
            minimal_distance_value: '',
            minimal_duration_value: '',
            zone_distance: 'required|numeric'
        },

        destination: {
            destination_from_id: 'required|numeric',
            destination_to_id: 'required|numeric',
            free_wait_stop_minutes: 'required|numeric|max_value:180',
            paid_wait_stop_minute: 'required',
        },
    };

    updateLoading = false;
    storeLoading = false;

    constructor(tariff = {}) {
        super('tariff', 'admin-super', '', {
            store: 'store',
            update: 'edit',
            delete: '../tariff',
            deletes: 'deletes',
        });

        this.tariff_id = tariff.tariff_id || null;
        this.name = tariff.name || null;
        this.country_id = tariff.country_id || null;
        this.region_id = tariff.region_id || null;
        this.region = tariff.region ? tariff.region.ids : [];
        this.city_ids = tariff.city_ids || [];
        this.city = tariff.city || [];
        this.tariff_type = tariff.tariff_type || null;
        this.tariff_type_id = tariff.tariff_type_id || null;
        this.payment_type_id = tariff.payment_type_id || null;
        this.car_class_id = tariff.car_class_id || null;
        this.tool_roads_client = tariff.tool_roads_client || 0;
        this.paid_parking_client = tariff.paid_parking_client || 0;
        this.is_default = tariff.is_default || 0;
        this.status = tariff.status || 0;
        this.free_wait_minutes = tariff.free_wait_minutes || null;
        this.paid_wait_minute = tariff.paid_wait_minute || null;
        this.minimal_price = tariff.minimal_price || null;
        this.minimal_distance_value = tariff.minimal_distance_value || null;
        this.minimal_duration_value = tariff.minimal_duration_value || null;
        this.rounding_price = tariff.rounding_price || null;
        this.option = tariff.option || null;
        this.date_from = tariff.date_from || null;
        this.date_to = tariff.date_to || null;
        this.current_tariff = tariff.current_tariff || null;
        this.tariff_behind = tariff.tariff_behind || null;
        this.all_alternatives = tariff.all_alternatives || null;

        this.regions_cities = tariff.regions_cities || null;
        this.destination = tariff.destination || null;
        this.rent = tariff.rent || null;
    }

    setOption() {
        if (this.current_tariff) {
            if (this.current_tariff.tariff_destination_id) {
                this.option = 'destination';
            } else if (this.current_tariff.tariff_region_city_id) {
                this.option = 'regions_cities';
            }else if (this.current_tariff.tariff_rent_id) {
                this.option = 'rent';
            }
        }
    }

    setCity() {
        if (this.current_tariff && this.city.ids) {
            this.city_ids = [];
            this.city.ids.forEach(id => {
                this.city_ids.push(id);
            });
        }
    }

    resetFields() {
        this.optionComponent = null;
        this.regions_cities = null;
        this.destination = null;
        this.rent = null;

        this.tariff_id = null;
        this.name = null;
        this.country_id = null;
        this.region_id = null;
        this.region = [];
        this.city_ids = [];
        this.city = [];
        this.payment_type_id = null;
        this.car_class_id = null;
        this.tool_roads_client = 0;
        this.paid_parking_client = 0;
        this.is_default = 0;
        this.status = 0;
        this.free_wait_minutes = null;
        this.paid_wait_minute = null;
        this.minimal_price = null;
        this.minimal_distance_value = null;
        this.minimal_duration_value = null;
        this.rounding_price = null;
        this.date_from = null;
        this.date_to = null;
        this.current_tariff = null;
        this.tariff_behind = null;

    }

    addComponent(component) {
        this.components.push(component);
    }

    removeComponent(component) {
        let index = this.components.findIndex(item => {
            return item === component;
        });
        this.components.splice(index, 1);
    }

    createFormData() {
        let data = {
            _method: this.tariff_id ? 'put' : 'post',
            name: this.name,
            country_id: this.country_id,
            car_class_id: this.car_class_id,
            tariff_type_id: this.tariff_type_id,
            region_id: this.region_id,
            region: this.region,
            city_ids: this.city_ids,
            payment_type_id: this.payment_type_id,
            paid_parking_client: this.paid_parking_client,
            tool_roads_client: this.tool_roads_client,
            is_default: this.is_default,
            status: this.status,
            free_wait_minutes: this.free_wait_minutes,
            paid_wait_minute: this.paid_wait_minute,
            minimal_price: this.minimal_price,
            minimal_distance_value: this.minimal_distance_value,
            minimal_duration_value: this.minimal_duration_value,
            rounding_price: this.rounding_price,
            date_from: this.date_from,
            date_to: this.date_to,
            option: this.option,

            regions_cities: this.regions_cities,
            destination: this.destination,
            rent: this.rent,
        };

        return serialize(data, { indices: true });
    }

    storeTariff() {
        this.setCity();
        this.storeLoading = true;
        return axios.post('/admin/super/tariff/store', this.createFormData());
    }

    updateTariff() {
        this.setCity();
        this.updateLoading = true;
        return axios.post('/admin/super/tariff/update/' + this.tariff_id, this.createFormData());
    }
}
