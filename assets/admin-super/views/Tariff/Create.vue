<!-- @format -->

<template>
    <tariff-form :rounds="rounds" :car-classes="carClasses" :tariff-types="tariffTypes" :payment-types="paymentTypes"></tariff-form>
</template>
<script>
import TariffForm from './TariffForm';
export default {
    props: ['carClasses', 'paymentTypes', 'tariffTypes', 'rounds'],
    name: 'TariffCreate',
    components: { TariffForm },
};
</script>
