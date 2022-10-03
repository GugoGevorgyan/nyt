<!-- @format -->

<template>
    <v-container fill-height justify-center align-content-center>
        <v-card max-width="550" min-width="550" class="border" elevation="4">
            <v-progress-linear
                v-if="loadData"
                class="mb-0"
                rounded
                :active="loadData"
                :indeterminate="loadData"
                color="grey darken-1"
                absolute
                height="3"
                top
            />
            <v-toolbar color="yellow darken-2" :class="!loadData ? 'mt-3' : ''" light dense outlined rounded>
                <v-toolbar-title>
                    Заказы
                    {{ preorders.length }}
                </v-toolbar-title>
            </v-toolbar>

            <v-card-text
                v-if="!preorders.length"
                :style="{ 'max-height': window.height + 'px' }"
                style="overflow: auto"
            >
                <v-list three-line>
                    <template>
                        <v-skeleton-loader ref="skeleton" type="list-item" class="mx-auto" />

                        <v-divider />

                        <v-list-item>
                            <v-list-item-avatar>
                                <v-skeleton-loader
                                    ref="skeleton"
                                    type="list-item-avatar-three-line, image, article"
                                    class="mx-auto"
                                />
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-skeleton-loader ref="skeleton" type="list-item-three-line" class="mx-auto" />
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-btn icon disabled>
                                    <v-icon color="grey lighten-1" v-text="'mdi-timetable'" />
                                </v-btn>
                                <v-btn icon disabled>
                                    <v-icon color="grey lighten-1" v-text="'mdi-close'" />
                                </v-btn>
                            </v-list-item-action>
                        </v-list-item>
                    </template>
                </v-list>
            </v-card-text>

            <v-card-text v-else :style="{ 'max-height': window.height + 'px' }" style="overflow: auto">
                <v-list three-line>
                    <template v-for="(item, index) in preorders">
                        <v-subheader v-if="item.driver">
                            <span class="font-italic">Водитель: </span>
                            <span class="font-weight-bold">
                                {{ item.driver.name + " " + item.driver.surname + " " + item.driver.patronymic }}
                            </span>
                        </v-subheader>
                        <v-subheader v-else class="font-weight-medium" v-text="'Заказ ещё на распределении'" />

                        <v-divider />

                        <v-list-item>
                            <v-list-item-avatar width="80" height="80">
                                <div v-if="item.driver">
                                    <v-img width="80" height="99" :src="item.driver.photo" />
                                </div>
                                <v-icon v-else large v-text="'mdi-cat'" color="#565453" />
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-title>
                                    <v-icon v-text="'mdi-map-marker-up'" color="red" />
                                    <span class="font-italic">Из: </span>
                                    <span class="font-weight-bold">
                                        {{ item.details.address_from | formatAdd }}
                                    </span>
                                </v-list-item-title>
                                <v-list-item-title>
                                    <v-icon v-text="'mdi-map-marker-down'" color="green" />
                                    <span class="font-italic">В: </span>
                                    <span v-if="item.details.address_to" class="font-weight-bold">
                                        {{ item.details.address_to | formatAdd }}
                                    </span>
                                    <span v-else class="font-weight-bold" v-text="'Не известно'" />
                                </v-list-item-title>
                                <v-list-item-subtitle>
                                    <v-icon v-text="'mdi-cash'" color="green" />
                                    <span class="font-italic">Цена: </span>
                                    <span class="font-weight-bold">
                                        {{ item.cost ? item.cost.price + " " + item.cost.currency : 0 }}
                                    </span>
                                </v-list-item-subtitle>
                            </v-list-item-content>

                            <v-list-item-action>
                                <date-time
                                    time-header-color="orange lighten-1"
                                    date-header-color="orange lighten-1"
                                    label="Дата и Время"
                                    @clickOpen="openDatePicker(item)"
                                    @input="changeDate(item)"
                                    v-model="preDateTime"
                                    selector="icon"
                                    :max-date="maxDate"
                                    :min-date="minDate"
                                />

                                <v-btn icon @click="cancelPrOrderDialog(item._id)">
                                    <v-icon color="grey darken-2" v-text="'mdi-close'" />
                                </v-btn>
                            </v-list-item-action>
                        </v-list-item>
                        <v-spacer />
                        <span class="float-right">
                            <v-icon v-text="'mdi-update'" color="grey darken-4" small />
                            <span class="font-italic">{{ item.details.started }}</span>
                        </span>
                        <v-divider />
                    </template>
                </v-list>
            </v-card-text>
        </v-card>

        <v-dialog v-if="deleteDialog" v-model="deleteDialog" max-width="500">
            <v-card>
                <v-card-title class="title"> Вы уверены, что хотите удалить заказ?</v-card-title>

                <v-card-text>
                    <v-alert outlined type="error" dense>
                        <p>После удаления, заказ будет утерян!</p>
                        <strong style="color: dimgrey">{{ deletedPreOrder.details.address_from | formatAdd }}</strong>
                    </v-alert>
                </v-card-text>
                <v-divider />
                <v-card-actions>
                    <v-spacer />
                    <v-btn
                        class="rounded-2"
                        small
                        outlined
                        v-text="'отмена'"
                        @click="(deleteDialog = false), (deletedPreOrder = [])"
                    />
                    <v-btn
                        class="rounded-2"
                        :loading="deletePreLoader"
                        small
                        @click="cancelPrOrder(deletedPreOrder._id)"
                        color="error"
                        text
                        v-text="'удалить'"
                    />
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import DateTime from "../../../shared/components/form/DateTime";
import Snackbar from "../../facades/Snackbar";
import moment from "moment";

export default {
    name: "PreOrder",

    data() {
        return {
            preorders: [],
            loadData: false,
            deleteDialog: false,
            deletePreLoader: false,
            deletedPreOrder: [],
            preDateTime: moment().format("YYYY-MM-DD HH:mm"),
            createdDateTime: moment().format("YYYY-MM-DD HH:mm"),
            changeDiffDays: 14,
            window: {
                width: 0,
                height: 0,
                heightDif: 220,
            },
        };
    },

    components: { "date-time": DateTime },

    computed: {
        maxDate() {
            Date.prototype.addDays = function (days) {
                let date = new Date(this.valueOf());
                date.setDate(date.getDate() + days);
                return date;
            };
            let date = new Date();
            let diff = this.changeDiffDays - moment(this.createdDateTime).diff(moment(), "days");

            return date.addDays(diff).toISOString();
        },

        minDate() {
            return new Date().toISOString();
        },
    },

    methods: {
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },

        cancelPrOrderDialog(o_id) {
            this.deleteDialog = true;
            let index = this.preorders.findIndex(item => item._id === o_id);
            this.deletedPreOrder = this.preorders[index];
        },

        cancelPrOrder(o_id) {
            this.deletePreLoader = true;
            this.$http
                .delete(`cancel-pred/${o_id}`)
                .then(result => {
                    let index = this.preorders.findIndex(item => item._id === o_id);
                    this.preorders.splice(index, 1);
                    this.deletePreLoader = false;
                    this.deleteDialog = false;
                    Snackbar.info(result.data.message);
                })
                .catch(error => {});
        },

        openDatePicker(item) {
            this.preDateTime = moment(moment(item.details.started, "YYYY-MM-DD HH:mm").toISOString()).format(
                "YYYY-MM-DD HH:mm",
            );
            this.createdDateTime = moment(moment(item.details.created, "YYYY-MM-DD HH:mm").toISOString()).format(
                "YYYY-MM-DD HH:mm",
            );
        },

        changeDate(item) {
            let newDate = moment(this.preDateTime).format("YYYY-MM-DD HH:mm");
            item.details.started = newDate;
            this.$http
                .put(`change-pre-date/${item._id}/${newDate}`)
                .then(result => {
                    if (result.status !== 422) {
                        Snackbar.info(result.data.message + " " + newDate);
                    }
                })
                .catch(error => {})
                .finally(() => {
                    this.preDateTime = moment().format("YYYY-MM-DD HH:mm");
                    this.createdDateTime = moment().format("YYYY-MM-DD HH:mm");
                });
        },
    },

    created() {
        this.loadData = true;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
        this.$http
            .get("client/preorders")
            .then(response => {
                this.preorders = response.data;
                this.loadData = false;
            })
            .catch(error => {
                this.loadData = false;
            });
    },
};
</script>
