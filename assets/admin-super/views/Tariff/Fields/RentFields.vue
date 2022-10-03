<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="4">
                <select-location-rent :tariff="tariff" @getRequestId='setIdsValue($event)'></select-location-rent>
            </v-col>
            <v-col cols="12" md="4">
                <v-autocomplete
                    type="number"
                    :error-messages="errors.collect('hours')"
                    :items='rentTimes'
                    color="yellow darken-3"
                    label="Часы аренды"
                    name="hours"
                    outlined
                    dense
                    v-model="tariff.rent.hours"
                    v-validate="tariff.rules.rent.hours"
                    data-vv-as="часы"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="12" class='pb-0'>
            <v-alert type="info" outlined dense width='420px'>Для альтернативные тарифы нужно выбрать класс автомобиля и страна</v-alert>
            </v-col>
            <v-col cols="12" md="12" class='py-0'>
            <v-select
                color="yellow darken-3"
                label="Альтернативные тарифы не в зоне"
                :loading='loading'
                :items="alternativeTariffs"
                :disabled='!alternativeTariffs.length'
                v-model="rent_alternative_in_area"
                item-color="yellow darken-3"
                item-text="name"
                item-value="tariffable_id"
                clearable
                dense
                single-line
                hide-details
            />
                <v-select
                color="yellow darken-3"
                label="Альтернативные тарифы в зоне"
                :loading='loading'
                :items="alternativeTariffs"
                :disabled='!alternativeTariffs.length'
                v-model="rent_alternative_out_area"
                item-color="yellow darken-3"
                item-text="name"
                item-value="tariffable_id"
                class='mt-5'
                clearable
                dense
                single-line
                hide-details
            />
            </v-col>
        </v-row>
        <v-row>
            <v-col cols='12' md='4'>
                <v-checkbox
                    v-model="showFields.sitting_fee"
                    :false-value="0"
                    :true-value="1"
                    label="Плата за сидение"
                    type="checkbox"
                    color="primary"
                    name="sitting_fee"
                ></v-checkbox>
                <div v-if='sittingIsPaid'>
                    <v-alert
                        border='top'
                        colored-border
                        type='info'
                        elevation='2'
                    >
                        Требуется хотя бы один ввод
                    </v-alert>
                    <v-text-field
                        type="number"
                        :error-messages="errors.collect('sit_fix_price')"
                        hide-details
                        color="yellow darken-3"
                        label="Фиксированная цена"
                        name="sit_fix_price"
                        outlined
                        dense
                        v-model="tariff.rent.sit_fix_price"
                        v-validate="{rules: { required: isNull }}"
                        data-vv-as="Фиксированная цена"
                    ></v-text-field>
                    <v-text-field
                        class='mt-2'
                        type="number"
                        :error-messages="errors.collect('sit_price_km')"
                        hide-details
                        color="yellow darken-3"
                        label="Цена сидения км."
                        name="sit_price_km"
                        outlined
                        dense
                        v-model="tariff.rent.sit_price_km"
                        v-validate="{rules: {required: isNull}}"
                        data-vv-as="Цена сидения км."
                    ></v-text-field>
                    <v-text-field
                        class='mt-2'
                        type="number"
                        :error-messages="errors.collect('sit_price_minute')"
                        hide-details
                        color="yellow darken-3"
                        label="Цена сидения мин."
                        name="sit_price_minute"
                        outlined
                        dense
                        v-model="tariff.rent.sit_price_minute"
                        v-validate="{rules: {required: isNull}}"
                        data-vv-as="Цена сидения мин."
                    ></v-text-field>
                </div>
            </v-col>

        </v-row>
        <v-row>
            <v-col cols="12" md="12" style="height: 600px">
                <select-area :tariff="tariff" :option="tariff.rent" />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import SelectLocationRent from '../components/SelectLocationRentOption';
import axios from 'axios';
import SelectArea from './../components/SelectArea';


export default {
    name: 'RentFields',
    components: { SelectArea, SelectLocationRent },
    props: [
        "tariff",
        "alternativeTariffs",
        "loading"
    ],
    data () {
        return {
            areas: [],
            showFields: {
                hours: null,
                area_id: null,
                sitting_fee: null,
                sit_fix_price: null,
                sit_price_km: null,
                sit_price_minute: null,
                rent_alt: []
            },
            rent_alternative_in_area: null,
            rent_alternative_out_area: null,
            ids: Object,
            sittingIsPaid: false,
            rentTimes: [],
            isNull: false,
            current_tariff_rent_alt_id_in_area: null,
            current_tariff_rent_alt_id_out_area: null,
        }
    },
    watch: {
        'showFields.sitting_fee': function() {
            this.sittingIsPaid = this.showFields.sitting_fee > 0 ? true: false
            this.isNull = this.getBoolValue()
        },
        'showFields.sit_fix_price': function() {
            this.isNull = this.getBoolValue()
        },
        'showFields.sit_price_km': function() {
            this.isNull = this.getBoolValue()
        },
        'showFields.sit_price_minute': function() {
            this.isNull = this.getBoolValue();
        },
        rent_alternative_in_area(val) {
                this.alternativeTariffs.forEach((v,k) => {
                    if (v.tariffable_id === val) {
                        // this If for Edit version
                        if (!this.tariff.rent.rent_alt) {
                            this.tariff.rent['rent_alt'] = [];
                        } else {
                            this.tariff.rent.rent_alt.forEach((value,key) => {
                                if (this.tariff.rent.rent_alt.length && this.tariff.rent.rent_alt[key].in_area === 1) {
                                    this.tariff.rent.rent_alt.splice(key,1);
                                }
                            });
                        }
                        this.tariff.rent.rent_alt.push({
                            tariff_rent_alt_id: this.current_tariff_rent_alt_id_in_area,
                            alt_id: val,
                            alt_type: this.alternativeTariffs[k].tariffable_type,
                            in_area: 1,
                        });
                    }
                });
        },
        rent_alternative_out_area(val) {
            this.alternativeTariffs.forEach((v,k) => {
                // this If for Edit version
                if (v.tariffable_id === val) {
                    if (!this.tariff.rent.rent_alt) {
                        this.tariff.rent['rent_alt'] = [];
                    } else {
                        this.tariff.rent.rent_alt.forEach((value,key) => {
                            if (this.tariff.rent.rent_alt.length && this.tariff.rent.rent_alt[key].in_area === 2) {
                                this.tariff.rent.rent_alt.splice(key,1);
                            }
                        });
                    }
                        this.tariff.rent.rent_alt.push({
                            tariff_rent_alt_id: this.current_tariff_rent_alt_id_out_area,
                            alt_id: val,
                            alt_type: this.alternativeTariffs[k].tariffable_type,
                            in_area: 2,
                        });
                }
            });
        },
        'tariff.country_id': function(newVal, oldVal) {
            if (newVal && (newVal !== oldVal) && this.tariff.car_class_id) {
                this.$emit('openAlternativeTariffs');
            }
        }
    },
    methods: {
        getBoolValue() {
            if ((this.tariff.rent.sit_price_km === null || this.tariff.rent.sit_price_km === '')
                && (this.tariff.rent.sit_fix_price === null || this.tariff.rent.sit_fix_price === '')
                && (this.tariff.rent.sit_price_minute === null || this.tariff.rent.sit_price_minute === ''))
                return true;
            return false
        },
        setValues() {
            if (this.tariff.current_tariff && this.tariff.current_tariff.tariff_rent_id) {
                this.tariff.rent = this.tariff.current_tariff;
            } else {
                this.tariff.rent = this.showFields;
            }
        },
        setIdsValue(object) {
            this.ids = object
        },
        getRentTimes() {
            axios.get(`/admin/super/get/rent-times`).then(res => {
                this.rentTimes = Object.values(res.data)
            })
        },
        setAlternativeTariffs() {
            if (this.tariff.current_tariff) {
                if (this.tariff.current_tariff.alt_behind.length) {
                    if (this.tariff.current_tariff.alt_behind[0].pivot.in_area === 1) {
                        this.rent_alternative_in_area = this.tariff.current_tariff.alt_behind[0].tariffable_id;
                    } else {
                        this.rent_alternative_out_area = this.tariff.current_tariff.alt_behind[0].tariffable_id;
                    }
                    if (this.tariff.current_tariff.alt_behind[1].pivot.in_area === 1) {
                        this.rent_alternative_in_area = this.tariff.current_tariff.alt_behind[1].tariffable_id;
                    } else {
                        this.rent_alternative_out_area = this.tariff.current_tariff.alt_behind[1].tariffable_id;
                    }
                }
                if (this.tariff.current_tariff.alt_region.length) {
                    this.loading = false;
                    if (this.tariff.current_tariff.alt_region[0].pivot.in_area === 1) {
                        this.rent_alternative_in_area = this.tariff.current_tariff.alt_region[0].tariffable_id;
                        this.current_tariff_rent_alt_id_in_area = this.tariff.current_tariff.alt_region[0].pivot.tariff_rent_alt_id;
                    } else {
                        this.rent_alternative_out_area = this.tariff.current_tariff.alt_region[0].tariffable_id;
                        this.current_tariff_rent_alt_id_out_area = this.tariff.current_tariff.alt_region[0].pivot.tariff_rent_alt_id;
                    }
                    if (this.tariff.current_tariff.alt_region[1].pivot.in_area === 1) {
                        this.rent_alternative_in_area = this.tariff.current_tariff.alt_region[1].tariffable_id;
                        this.current_tariff_rent_alt_id_in_area = this.tariff.current_tariff.alt_region[1].pivot.tariff_rent_alt_id;
                    } else {
                        this.rent_alternative_out_area = this.tariff.current_tariff.alt_region[1].tariffable_id;
                        this.current_tariff_rent_alt_id_out_area = this.tariff.current_tariff.alt_region[1].pivot.tariff_rent_alt_id;
                    }
                }
                if (this.tariff.current_tariff.alt_destination.length) {
                    this.loading = false;
                    if (this.tariff.current_tariff.alt_destination[0].pivot.in_area === 1) {
                        this.rent_alternative_in_area = this.tariff.current_tariff.alt_destination[0].tariffable_id;
                    } else {
                        this.rent_alternative_out_area = this.tariff.current_tariff.alt_destination[0].tariffable_id;
                    }
                    if (this.tariff.current_tariff.alt_region[1].pivot.in_area === 1) {
                        this.rent_alternative_in_area = this.tariff.current_tariff.alt_destination[1].tariffable_id;
                    } else {
                        this.rent_alternative_out_area = this.tariff.current_tariff.alt_destination[1].tariffable_id;
                    }
                }
            }
        },
        // set_tariff_rent_alt_id_out_area(id) {
        //     let tariff_rent_alt_id;
        //     // console.log('id - ', id);
        //     // console.log('currik = ', this.tariff.current_tariff);
        //         if (this.tariff.current_tariff.alt_region && this.tariff.current_tariff.alt_region.length) {
        //             console.log('id - ', id, 'alt_id - ', this.tariff.current_tariff.alt_region[0].pivot.alt_id);
        //             console.log('check = ', id === this.tariff.current_tariff.alt_region[0].pivot.alt_id, this.tariff.current_tariff.alt_region[0].pivot.in_area === 2);
        //             console.log('check = ', id === this.tariff.current_tariff.alt_region[1].pivot.alt_id, this.tariff.current_tariff.alt_region[1].pivot.in_area === 2);
        //             if (id === this.tariff.current_tariff.alt_region[0].pivot.alt_id
        //                 && this.tariff.current_tariff.alt_region[0].pivot.in_area === 2) {
        //                 console.log('pivot 0', this.tariff.current_tariff.alt_region[0].pivot);
        //
        //                 tariff_rent_alt_id = this.tariff.current_tariff.alt_region[0].pivot.tariff_rent_alt_id;
        //
        //             } else if (id === this.tariff.current_tariff.alt_region[1].pivot.alt_id
        //                 && this.tariff.current_tariff.alt_region[1].pivot.in_area === 2) {
        //                 console.log('pivot 1', this.tariff.current_tariff.alt_region[0].pivot);
        //
        //                 tariff_rent_alt_id = this.tariff.current_tariff.alt_region[0].pivot.tariff_rent_alt_id;
        //                 // return this.tariff.rent.alt_region[1].pivot
        //             }
        //         }
        //
        //     return tariff_rent_alt_id;

        //     if (this.tariff.current_tariff.alt_behind.length) {
        //         if (this.tariff.current_tariff.alt_behind[0].pivot.in_area === 1) {
        //             this.rent_alternative_in_area = this.tariff.current_tariff.alt_behind[0].tariff_id;
        //         } else {
        //             this.rent_alternative_out_area = this.tariff.current_tariff.alt_behind[0].tariff_id;
        //         }
        //         if (this.tariff.current_tariff.alt_behind[1].pivot.in_area === 1) {
        //             this.rent_alternative_in_area = this.tariff.current_tariff.alt_behind[1].tariff_id;
        //         } else {
        //             this.rent_alternative_out_area = this.tariff.current_tariff.alt_behind[1].tariff_id;
        //         }
        //     }
        //     if (this.tariff.current_tariff.alt_region.length) {
        //         this.loading = false;
        //         if (this.tariff.current_tariff.alt_region[0].pivot.in_area === 1) {
        //             this.rent_alternative_in_area = this.tariff.current_tariff.alt_region[0].tariff_id;
        //         } else {
        //             this.rent_alternative_out_area = this.tariff.current_tariff.alt_region[0].tariff_id;
        //         }
        //         if (this.tariff.current_tariff.alt_region[1].pivot.in_area === 1) {
        //             this.rent_alternative_in_area = this.tariff.current_tariff.alt_region[1].tariff_id;
        //         } else {
        //             this.rent_alternative_out_area = this.tariff.current_tariff.alt_region[1].tariff_id;
        //         }
        //     }
        //     if (this.tariff.current_tariff.alt_destination.length) {
        //         this.loading = false;
        //         if (this.tariff.current_tariff.alt_destination[0].pivot.in_area === 1) {
        //             this.rent_alternative_in_area = this.tariff.current_tariff.alt_destination[0].tariff_id;
        //         } else {
        //             this.rent_alternative_out_area = this.tariff.current_tariff.alt_destination[0].tariff_id;
        //         }
        //         if (this.tariff.current_tariff.alt_region[1].pivot.in_area === 1) {
        //             this.rent_alternative_in_area = this.tariff.current_tariff.alt_destination[1].tariff_id;
        //         } else {
        //             this.rent_alternative_out_area = this.tariff.current_tariff.alt_destination[1].tariff_id;
        //         }
        //     }
        // }
    },
    mounted() {
        this.tariff.addComponent(this);
    },
    created() {
        this.setAlternativeTariffs();
        this.setValues();
        this.getRentTimes();
        if (this.tariff.country_id) {
            this.$emit('openAlternativeTariffs');
        }
    }
};
</script>

<style scoped>

</style>
