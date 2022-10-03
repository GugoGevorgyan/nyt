<!-- @format -->

<template>
    <tariff-form
        :rounds="rounds"
        :tariff-obj="tariff"
        :car-classes="carClasses"
        :tariff-types="tariffTypes"
        :payment-types="paymentTypes"
    ></tariff-form>
</template>
<script>
import TariffForm from './TariffForm';
export default {
    props: ['tariff', 'carClasses', 'paymentTypes', 'tariffTypes', 'rounds'],
    name: 'TariffEdit',
    components: { TariffForm },
};
</script>
