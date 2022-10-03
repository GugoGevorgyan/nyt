<template>
    <div v-if='isDestination'>
        <v-switch
            v-if="option && option.hasOwnProperty('sitting_fee')"
            :true-value="1"
            :false-value="0"
            label="Плата за сидение"
            name="sitting_fee"
            v-model="option.sitting_fee"
            dense
            data-vv-as="плата за сидение"
        >
        </v-switch>
    <div v-if='sittingIsPaid'>
        <v-alert v-if='sittingIsPaid' type="info" outlined dense> Требуется хотя бы один ввод </v-alert>
        <v-text-field
            type="number"
            :error-messages="errors.collect('sit_fix_price')"
            hide-details
            color="yellow darken-3"
            label="Фиксированная цена"
            name="sit_fix_price"
            outlined
            dense
            v-model="option.sit_fix_price"
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
            v-model="option.sit_price_km"
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
            v-model="option.sit_price_minute"
            v-validate="{rules: {required: isNull}}"
            data-vv-as="Цена сидения мин."
        ></v-text-field>
    </div>
    </div>
    <div v-else>
            <v-switch
                v-if="option && option.hasOwnProperty('sitting_fee')"
                :true-value="1"
                :false-value="0"
                label="Плата за сидение"
                name="sitting_fee"
                v-model="option.sitting_fee"
                dense
                data-vv-as="плата за сидение"
            >
            </v-switch>
        <v-alert v-if='sittingIsPaid' type="info" outlined dense> Требуется хотя бы один ввод </v-alert>
        <div v-if="sittingIsPaid">
                <v-text-field
                    v-if="option && option.hasOwnProperty('sit_fix_price')"
                    :error-messages="errors.collect('sit_fix_price')"
                    hide-details
                    type="number"
                    color="yellow darken-3"
                    label="Фиксированная цена"
                    name="sit_fix_price"
                    outlined
                    dense
                    v-model="option.sit_fix_price"
                    v-validate="{rules: {required: isNull}}"
                    data-vv-as="Фиксированная цена"
                />
                <v-text-field
                    v-if="option && option.hasOwnProperty('sit_price_minute') && (tariffObj.tariff_type_id == 3 || tariffObj.tariff_type_id == 1)"
                    class='mt-2'
                    :error-messages="errors.collect('sit_price_minute')"
                    hide-details
                    type="number"
                    color="yellow darken-3"
                    label="Цена минуты сидения"
                    name="sit_price_minute"
                    outlined
                    dense
                    v-model="option.sit_price_minute"
                    v-validate="{rules: {required: isNull}}"
                    data-vv-as="цена минуты сидения"
                />
                <v-text-field
                    v-if="option && option.hasOwnProperty('sit_price_km') && (tariffObj.tariff_type_id == 3 || tariffObj.tariff_type_id == 2)"
                    class='mt-2'
                    :error-messages="errors.collect('sit_price_km')"
                    hide-details
                    type="number"
                    color="yellow darken-3"
                    label="Цена километра сидения"
                    name="sit_price_km"
                    outlined
                    dense
                    v-model="option.sit_price_km"
                    v-validate="{rules: {required: isNull}}"
                    data-vv-as="цена километра сидения"
                />
            </div>
            <div v-else>
                <v-alert type="info" outlined dense> Плата за сидение не установлена </v-alert>
            </div>
    </div>
</template>

<script>
export default {
    name: 'SittingFee',
    props:['option','tariffObj'],
    data() {
        return {
            sittingIsPaid: false,
            isNull: false,
            isDestination: false
        }
    },
    watch: {
        'option.sitting_fee': function() {
            if (this.option.sitting_fee) {
                this.sittingIsPaid = true;
                this.isNull = this.getBoolValue();
            } else {
                this.sittingIsPaid = false;
                this.isNull = false;
                this.option.sit_fix_price = null;
                this.option.sit_price_km = null;
                this.option.sit_price_minute = null;
            }
        },
        'option.sit_fix_price': function() {
            this.isNull = this.getBoolValue()
        },
        'option.sit_price_km': function() {
            this.isNull = this.getBoolValue()
        },
        'option.sit_price_minute': function() {
            this.isNull = this.getBoolValue()
        },
    },
    methods: {
        getBoolValue() {
            if (this.isDestination) {
                if ((this.option.sit_price_km === null || this.option.sit_price_km === '')
                    && (this.option.sit_fix_price === null || this.option.sit_fix_price === '')
                    && (this.option.sit_price_minute === null || this.option.sit_price_minute === ''))
                    return true;
                return false
            } else {
                if (this.tariffObj.tariff_type.type == 1) {
                    if ((this.option.sit_fix_price === null || this.option.sit_fix_price === '')
                        && (this.option.sit_price_minute === null || this.option.sit_price_minute === ''))
                        return true;
                    return false
                } else if (this.tariffObj.tariff_type.type == 2) {
                    if ((this.option.sit_fix_price === null || this.option.sit_fix_price === '')
                        && (this.option.sit_price_km === null || this.option.sit_price_km === ''))
                        return true;
                    return false
                } else {
                    if ((this.option.sit_price_km === null || this.option.sit_price_km === '')
                        && (this.option.sit_fix_price === null || this.option.sit_fix_price === '')
                        && (this.option.sit_price_minute === null || this.option.sit_price_minute === ''))
                        return true;
                    return false
                }
            }
        },
    },
    mounted() {
        this.tariffObj.addComponent(this);
    },
    created() {
        if (this.tariffObj.tariff_type_id == 4) {
            this.isDestination = true;
        } else {
            this.isDestination = false;
        }

        if (this.option.sitting_fee) {
            this.sittingIsPaid = true;
        }
    }
};
</script>

<style scoped>

</style>
