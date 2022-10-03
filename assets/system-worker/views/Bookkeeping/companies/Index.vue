<template>
    <v-container fluid>
        <v-card elevation="8" tile>
            <v-tabs
                grow
                v-model="tab"
                color="secondary"
                background-color="grey lighten-3"
                active-class="white"
                height="35px"
                right
                class="mb-2"
            >
                <v-tab dot v-for="type of tabItems" :key="type.key" :to="{ name: type.url }" v-text="type.name" />
            </v-tabs>
            <v-tabs-items v-model="tab">
                <router-view
                    :driver-types="driverTypes"
                    :companies="companies_"
                    :payment-types="paymentTypes"
                    :drivers="drivers"
                    :parks="parks"
                    :transactionTypes="transactionTypes"
                />
            </v-tabs-items>
        </v-card>
    </v-container>
</template>

<script>
export default {
    name: 'CompaniesIndex',

    props: {
        driverTypes: {
            required: true,
            type: Array,
        },
        companies: {
            required: true,
            type: Array,
        },
        paymentTypes: {
            required: true,
            type: Array,
        },
        drivers: {
            required: true,
            type: Array,
        },
        parks: {
            required: true,
            type: Array,
        },
        transactionTypes: {
            required: true,
            type: Array,
        },
    },
    data() {
        return {
            tab: 0,
            tabItems: [
                {
                    name: "Реестр заказов компаний",
                    key: 1,
                    url: "bookkeeping_company_orders_index",
                },
                {
                    name: "Реестр заказов компаний по ценам",
                    key: 2,
                    url: "bookkeeping_companies_orders_price_report_index",
                },
            ],
        };
    },
    computed: {
        companies_() {
            return this.companies.map(company => {
                company.title = company.name + ' / ' + company.company_id;

                return company;
            });
        }
    },
    created() {
        if ('/app/worker/bookkeeping/companies' === this.$route.path || '/app/worker/bookkeeping/companies' === this.$route.path) {
            this.$router.push({ name: 'bookkeeping_company_orders_index' });
        }
    }
};
</script>

<style scoped>

</style>
