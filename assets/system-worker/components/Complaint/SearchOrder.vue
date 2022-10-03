<!-- @format -->

<template>
    <div>
        <div style="font-size: 18px" class="my-2">Прикрепить заказ</div>
        <div class="d-flex">
            <div class="mr-4">
                <v-alert v-if="accepted" type="success" dense outlined>
                    <div class="d-flex align-center">
                        <small class="mr-1"
                            >Прикреплен заказ под номером <strong>{{ accepted }}</strong></small
                        >
                        <v-tooltip right>
                            <template v-slot:activator="{ on: on }">
                                <v-btn color="black" v-on="on" icon small @click="$emit('discharge')">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </template>
                            <small>Открепить заказ</small>
                        </v-tooltip>
                    </div>
                </v-alert>
                <v-alert v-else-if="searched && !findOrderObj" type="error" outlined>
                    <small
                        >Заказ по номеру <strong>{{ searched }}</strong> не найден</small
                    >
                </v-alert>
                <v-alert v-else type="info" dense outlined>
                    <small>Найдите заказ по номеру, и прикрепите к жалобе</small>
                </v-alert>
            </div>
            <template v-if="!accepted">
                <div style="width: 150px" class="mr-2">
                    <v-text-field
                        :disabled="searchOrderLoading || accepted"
                        :loading="searchOrderLoading"
                        @keypress="$event.keyCode === 13 && !searchOrderDisabled ? searchOrder() : null"
                        type="number"
                        color="yellow darken-3"
                        :error-messages="errors.collect('searchOrderId')"
                        label="Номер"
                        name="searchOrderId"
                        v-model="searchOrderId"
                        outlined
                        dense
                        data-vv-as="номер заказа"
                    ></v-text-field>
                </div>
                <v-btn
                    @click="searchOrder()"
                    :disabled="searchOrderDisabled"
                    :dark="!searchOrderDisabled"
                    fab
                    small
                    color="yellow darken-3"
                >
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
            </template>
        </div>
        <v-divider class="mt-2 mb-6" />

        <v-dialog v-model="findOrderObjDialog" width="1800" persistent>
            <v-card v-if="findOrderObj">
                <v-card-title>Най денный заказ под номером {{ findOrderObj.order.order_id }}</v-card-title>
                <v-card-text>
                    <info-box :order-id="findOrderObj.order_id" :order-obj="findOrderObj" :height="600" />
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn outlined small color="error" @click="findOrderObjDialog = false">отменить</v-btn>
                    <v-btn small color="primary" @click="findOrderObjAccept()">прикрепить заказ</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import axios from "axios";
import Snackbar from "../../facades/Snackbar";
import InfoBox from "../Order/InfoBox";

export default {
    name: "SearchOrder",
    components: { InfoBox },
    props: {
        orderId: {
            required: true,
        },
    },
    data() {
        return {
            searchOrderId: null,
            searchOrderLoading: false,
            searched: null,

            findOrderObj: null,
            findOrderObjDialog: false,

            accepted: null,
        };
    },
    watch: {
        orderId() {
            this.accepted = this.orderId;
            if (!this.orderId) {
                this.searchOrderId = null;
            }
        },
        findOrderObj() {
            if (this.findOrderObj) {
                this.findOrderObjDialog = true;
            }
        },
        searchOrderId() {
            this.searched = null;
            this.findOrderObj = null;
        },
    },
    computed: {
        searchOrderDisabled() {
            return !this.searchOrderId || this.searchOrderLoading || this.searched === this.searchOrderId;
        },
    },
    methods: {
        searchOrder() {
            if (!this.searchOrderDisabled) {
                this.searchOrderLoading = true;
                axios
                    .get(process.env.MIX_APP_WORKER_URL + "order/info/" + this.searchOrderId)
                    .then(response => {
                        this.searchOrderLoading = false;
                        this.findOrderObj = response.data.order ? response.data : null;
                        this.searched = this.searchOrderId;
                    })
                    .catch(error => {
                        this.searchOrderLoading = false;
                        this.searched = this.searchOrderId;
                        Snackbar.error(error.response.data.message);
                    });
            }
        },
        findOrderObjAccept() {
            this.findOrderObjDialog = false;
            this.$emit("accept", this.findOrderObj.order.order_id);
        },
    },
};
</script>
