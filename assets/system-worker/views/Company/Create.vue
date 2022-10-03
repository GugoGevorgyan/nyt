<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <company-form :company-obj="undefined" :admins-count="0" :phoneMask='phoneMask' />
    </v-container>
</template>

<script>
import CompanyForm from "./form/CompanyForm";
export default {
    components: { CompanyForm },
    props: ['phoneMask'],
    data() {
        return {};
    },
};
</script>
