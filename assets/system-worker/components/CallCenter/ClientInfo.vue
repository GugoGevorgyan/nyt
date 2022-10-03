<!-- @format -->

<template>
    <v-card flat :dark="darkMode">
        <v-card-text>
            <div :style="{ height: height }" style="overflow: auto">
                <client-form
                    v-if="!clientObj.client"
                    :client="client"
                    @updateClientObj="$emit('updateClientObj', $event)"
                />
                <v-container v-else fluid grid-list-lg>
                    <v-row>
                        <v-col cols="12">
                            <div class="d-flex align-center">
                                <span class="display-1 font-weight-light">
                                    {{ clientObj.client | clientTitle }}
                                </span>
                                <v-btn class="ml-1" icon color="success" @click="$emit('call', clientObj.client.phone)">
                                    <v-icon v-text="'mdi-phone'" />
                                </v-btn>
                                <v-spacer />
                                <v-btn icon color="primary" @click="showUpdate()">
                                    <v-icon v-text="'mdi-pencil'" />
                                </v-btn>
                            </div>
                            <v-divider />
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="2">
                            <!--Companies-->
                            <div class="mb-2">
                                <span class="display-1 font-weight-light">Компании</span>
                            </div>
                            <v-divider />
                            <div class="" style="overflow-y: auto; height: 500px">
                                <v-list v-if="clientObj.companies && clientObj.companies.length">
                                    <v-list-item
                                        class="pl-0"
                                        v-for="company in clientObj.companies"
                                        two-line
                                        :key="company.company_id"
                                    >
                                        <v-list-item-content>
                                            <v-list-item-subtitle>{{ company.name }}</v-list-item-subtitle>
                                            <v-list-item-title>Код: {{ company.code }}</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                                <div
                                    v-else
                                    class="d-flex justify-center align-center"
                                    style="width: 100%; height: 100%; border: 1px dashed gray"
                                >
                                    <span>Нет компаний</span>
                                </div>
                            </div>
                        </v-col>

                        <v-col cols="12" md="10">
                            <!--Last orders-->
                            <div class="d-flex justify-space-between mb-2">
                                <span class="display-1 font-weight-light">Последние заказы</span>
                            </div>
                            <v-divider />
                            <last-orders
                                v-if="clientObj.client && clientObj.orders && clientObj.orders.length"
                                :client="clientObj.client"
                                :loading="lastOrdersLoading"
                                :orders="clientObj.orders"
                                @call="$emit('call', $event)"
                                @copy="$emit('copyOrder', $event)"
                            />
                            <div
                                v-else
                                class="d-flex justify-center align-center"
                                :style="{ height: 500 + 'px' }"
                                style="width: 100%; border: 1px dashed gray"
                            >
                                <span>Нет заказов</span>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </v-card-text>

        <!--Update client dialog-->
        <v-dialog persistent v-model="updateDialog" max-width="600" width="100%" :dark="darkMode">
            <v-card class="border-lg" :dark="darkMode">
                <v-card-text>
                    <client-form
                        v-if="clientObj.client"
                        :client="updateClient"
                        @clientUpdated="setUpdatedClient($event)"
                        @close="closeUpdate()"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
import LastOrders from "./LastOrders/LastOrders";
import CallCenterClient from "../../models/CallCenterClient";
import ClientForm from "./ClientForm/ClientForm";

export default {
    components: { ClientForm, LastOrders },
    props: {
        height: {
            required: true,
        },
        clientObj: {
            required: true,
        },
    },
    data() {
        return {
            client: new CallCenterClient(),
            lastReqPhone: null,

            clientLoading: false,
            companiesLoading: false,
            lastOrdersLoading: false,

            updateDialog: false,
            updateClient: new CallCenterClient(),
        };
    },
    computed: {
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
        },
    },
    watch: {
        "clientObj.client": function () {
            this.$validator.pause();
            this.client = new CallCenterClient();
            this.$nextTick(() => {
                this.$validator.reset();
                this.$validator.resume();
            });
        },
    },
    filters: {
        clientTitle(client) {
            let initials = [client.name, client.surname, client.patronymic];
            return client.name || client.surname || client.patronymic
                ? `${initials.join(" ").trim()}, телефон: ${client.phone}`
                : `Телефон: ${client.phone}`;
        },
    },
    methods: {
        showUpdate() {
            this.updateDialog = true;
            this.updateClient = new CallCenterClient(this.clientObj.client);
        },
        closeUpdate() {
            this.updateDialog = false;
            this.updateClient = new CallCenterClient();
        },
        setUpdatedClient(client) {
            let obj = {
                client: client,
                companies: this.clientObj.companies,
                orders: this.clientObj.orders,
            };
            this.$emit("updateClientObj", obj);
            this.closeUpdate();
        },
    },
};
</script>
