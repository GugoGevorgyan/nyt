<!-- @format -->

<template>
    <v-card v-if="driver">
        <v-img height="250" :src="driver.driver_info.photo || lazyImage"></v-img>
        <v-btn
            :disabled="loading || signLoading"
            color="success"
            x-small
            fab
            absolute
            style="top: 5px; right: 5px"
            @click="$emit('close')"
        >
            <v-icon small>mdi-close</v-icon>
        </v-btn>
        <v-card-text class="pa-3">
            <p class="title"
                >{{ driver.driver_info.name }} {{ driver.driver_info.surname }} {{ driver.driver_info.patronymic }}
            </p>
            <div class="d-flex align-center">
                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <div v-on="on" class="d-flex align-center">
                            <v-rating
                                class="mr-1"
                                dense
                                small
                                readonly
                                :value="driver.mean_assessment"
                                :color="ratingColor(driver.mean_assessment)"
                                :background-color="ratingColor(driver.mean_assessment)"
                            />
                            <div class="grey--text">({{ driver.rating }})</div>
                        </div>
                    </template>
                    <span>Рейтинг</span>
                </v-tooltip>
            </div>
            <div class="my-1 subtitle-1 black--text">
                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <div v-on="on"> <v-icon small>mdi-phone</v-icon> {{ driver.phone | VMask(phoneMask) }} </div>
                    </template>
                    <span>Телефон</span>
                </v-tooltip>
            </div>
            <div class="my-1 subtitle-1 black--text">
                <v-tooltip left>
                    <template v-slot:activator="{ on }">
                        <div v-on="on">
                            <v-icon small color="red">mdi-alert</v-icon>
                            {{ driver.crashes_count }} (виновен:{{ driver.crashes.length }})
                        </div>
                    </template>
                    <span>Количество ДТП</span>
                </v-tooltip>
            </div>
            <div class="my-1 subtitle-1 black--text">
                <small class="mr-1">Тип контракта:</small>
                <span>{{ contract.subtype.name.toLowerCase() }}</span>
            </div>
            <div class="my-1 subtitle-1 black--text">
                <small class="mr-1">Тип работы:</small>
                <span>{{ contract.type.type.toLowerCase() }}</span>
            </div>
            <div class="my-1 subtitle-1 black--text">
                <small class="mr-1">Количество подписанных контрактов:</small>
                <span>{{ driver.contracts_count }}</span>
            </div>
            <v-btn
                v-if="newCar"
                class="mt-4"
                :loading="loading"
                color="primary"
                :outlined="!!printedContract"
                small
                @click="printContract()"
            >
                <v-icon small class="mr-1">mdi-printer-check</v-icon>
                {{ printedContract ? "Распечатать снова" : "Распечатать контракт" }}
            </v-btn>
            <v-btn
                v-if="printedContract"
                class="mt-4"
                :loading="signLoading"
                color="primary"
                small
                @click="signContract()"
            >
                <v-icon small class="mr-1">mdi-check</v-icon>
                контракт подписан
            </v-btn>
            <v-btn
                v-if='driver.car_id'
            color='error'
            @click="$emit('remove', driver)"
            :loading='removeDriverLoading'
            >
                Удалить водителя
            </v-btn>
        </v-card-text>
    </v-card>
</template>
<script>
import axios from "axios";
import Snackbar from "../../../facades/Snackbar";
import ContractSigning from "../../../models/ContractSigning";
export default {
    props: {
        driver: {
            required: true,
        },
        newCar: {
            required: true,
        },
        removeDriverLoading: {
            required: true
        }
    },
    data() {
        return {
            lazyImage: "/storage/img/noimage.png",
            printedContract: false,
            loading: false,
            signLoading: false,
        };
    },
    watch: {
        driver: function () {
            this.printedContract = null;
        },
        newCar: function () {
            this.printedContract = null;
        },
    },
    computed: {
        contract() {
            return this.driver.active_contract || this.driver.unsigned_contract;
        },
        phoneMask() {
            return this.$store.state.phoneMask;
        }
    },
    methods: {
        ratingColor(value) {
            if (5 === value) {
                return "#00C853";
            } else if (3 <= value) {
                return "yellow darken-3";
            } else {
                return "#C62828";
            }
        },

        // update() {
        //     this.addLoading = true
        //     axios.post('park-management/update-driver-car', this.updateData)
        //         .then(response => {
        //             this.$emit('close');
        //             this.$emit('updated');
        //             this.addLoading = false;
        //             Snackbar.info(response.data.message);
        //         })
        //         .catch(error => {
        //             this.addLoading = false;
        //             Snackbar.error(error.response.data.message);
        //         });
        // },

        printContract() {
            let data = {
                driver_id: this.driver.driver_id,
                contract_id: this.driver.unsigned_contract.driver_contract_id,
                car_id: this.newCar,
            };
            this.loading = true;
            axios
                .post("park-management/print-contract", data)
                .then(response => {
                    this.loading = false;
                    let w = window.open(response.data.file);
                    w.print();
                    this.printedContract = response.data.contract;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.loading = false;
                    this.printedContract = null;
                });
        },
        signContract() {
            if (this.printedContract) {
                this.signLoading = true;
                axios
                    .post("park-management/sign-contract", { contract_id: this.printedContract })
                    .then(response => {
                        Snackbar.info(response.data.message);
                        this.signLoading = false;
                        this.$emit("close");
                        this.$emit("updated");
                    })
                    .catch(error => {
                        Snackbar.error(error.response.data.message);
                        this.signLoading = false;
                        this.printedContract = null;
                    });
            }
        },
    },
};
</script>
