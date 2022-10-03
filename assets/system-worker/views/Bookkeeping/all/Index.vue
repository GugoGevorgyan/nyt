<!-- @format -->

<template>
    <v-container fluid>
        <div>
            <v-data-table
                :calculate-widths="true"
                :fixed-header="true"
                :headers="paginated.headers"
                :height="window.height"
                :item-class="rowClasses"
                :items="paginated._payload"
                :items-per-page="Number(paginated.per_page)"
                :loading="paginated.loading"
                dense
                class="elevation-4"
                hide-default-footer
                item-key="id"
                loader-height="2"
                multi-sort
            >
                <template v-slot:no-data>
                    <div class="justify-center align-center text-center">
                        <h1 class="font-weight-medium mt-10">Транзакций пока нет !</h1>
                    </div>
                </template>

                <template v-slot:loading>
                    <div class="justify-center align-center text-center">
                        <h1 class="font-weight-medium mt-10">Загрузка...</h1>
                    </div>
                </template>

                <template v-slot:top>
                    <div ref="toolbar">
                        <v-toolbar color="white" flat height="53px">
                            <v-row>
                                <v-col cols="12" style="max-width: 15%">
                                    <v-select
                                        v-model="paginated.payment_types"
                                        :items="paymentTypes"
                                        background-color="grey lighten-4"
                                        color="yellow darken-3"
                                        item-color="yellow darken-3"
                                        class="rounded-2"
                                        clearable
                                        deletable-chips
                                        dense
                                        hide-details
                                        item-text="name"
                                        item-value="type"
                                        hint="оплати"
                                        label="оплати"
                                        menu-props="auto"
                                        multiple
                                        outlined
                                        small-chips
                                    >
                                        <template v-slot:selection="{ item, index }">
                                            <small v-if="0 === index"
                                                >Выбрано: {{ paginated.payment_types.length }}</small
                                            >
                                        </template>
                                    </v-select>
                                </v-col>
                                <v-divider inset vertical />
                                <v-col cols="12" style="max-width: 15%">
                                    <v-select
                                        v-model="paginated.transaction_types"
                                        :items="transactionTypes"
                                        class="rounded-2"
                                        clearable
                                        background-color="grey lighten-4"
                                        color="yellow darken-3"
                                        item-color="yellow darken-3"
                                        deletable-chips
                                        dense
                                        hide-details
                                        item-text="name"
                                        item-value="type"
                                        hint="транзакции"
                                        label="транзакции"
                                        menu-props="auto"
                                        multiple
                                        outlined
                                        small-chips
                                    >
                                        <template v-slot:selection="{ item, index }">
                                            <small v-if="0 === index"
                                                >Выбрано: {{ paginated.transaction_types.length }}</small
                                            >
                                        </template>
                                    </v-select>
                                </v-col>
                                <v-divider inset vertical />
                                <v-col cols="12" style="max-width: 15%">
                                    <v-autocomplete
                                        background-color="grey lighten-4"
                                        color="yellow darken-3"
                                        item-color="yellow darken-3"
                                        class="rounded-2"
                                        clearable
                                        dense
                                        hide-details
                                        v-model="paginated.parks"
                                        :items="parks"
                                        item-text="name"
                                        item-value="park_id"
                                        label="Парки"
                                        hint="Парки"
                                        menu-props="auto"
                                        multiple
                                        outlined
                                    >
                                        <template v-slot:selection="{ item, index }">
                                            <small v-if="0 === index">Выбрано: {{ paginated.parks.length }}</small>
                                        </template>
                                    </v-autocomplete>
                                </v-col>
                                <v-divider inset vertical />
                                <v-col cols="12" md="2">
                                    <v-autocomplete
                                        v-model="paginated.driver"
                                        :items="drivers"
                                        single-line
                                        background-color="grey lighten-4"
                                        color="yellow darken-3"
                                        item-color="yellow darken-3"
                                        class="rounded-2"
                                        clearable
                                        dense
                                        hide-details
                                        item-text="full_name"
                                        item-value="driver_id"
                                        label="Водители"
                                        hint="Водители"
                                        menu-props="auto"
                                        outlined
                                    />
                                </v-col>
                                <v-divider inset vertical />
                                <v-col cols="12" md="2">
                                    <el-date-picker
                                        v-model="datePicker"
                                        :picker-options="pickerOptions"
                                        end-placeholder="до"
                                        range-separator="|"
                                        size="large"
                                        start-placeholder="от"
                                        style="max-width: 100%"
                                        type="daterange"
                                    />
                                </v-col>
                                <v-divider inset vertical />
                                <v-col class="d-flex align-center" cols="12" md="1">
                                    <v-tooltip right>
                                        <template v-slot:activator="{ on, attrs }">
                                            <div v-bind="attrs" v-on="on" style="margin-bottom: -18px">
                                                <v-switch v-model="paginated.payed" dense />
                                            </div>
                                        </template>
                                        <span>Оплаченные</span>
                                    </v-tooltip>

                                    <v-spacer />
                                    <v-tooltip right>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                                @click="transactionDialog = true"
                                                v-bind="attrs"
                                                v-on="on"
                                                color="primary"
                                                icon
                                                class="mr-1"
                                                small
                                            >
                                                <v-icon v-text="'mdi-plus'" />
                                            </v-btn>
                                        </template>
                                        <span>Ручная транзакция</span>
                                    </v-tooltip>

                                    <v-spacer />
                                    <v-tooltip right>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                                :disabled="!paginated.driver"
                                                @click="printTransaction"
                                                v-bind="attrs"
                                                v-on="on"
                                                color="black darken-5"
                                                icon
                                                small
                                            >
                                                <v-icon v-text="'mdi-cloud-print-outline'" />
                                            </v-btn>
                                        </template>
                                        <span>Принт</span>
                                    </v-tooltip>
                                </v-col>
                                <v-spacer />
                                <v-divider inset vertical />
                                <v-col class="d-flex align-center" cols="12" md="1">
                                    {{ paginated.sum }}
                                </v-col>
                            </v-row>
                        </v-toolbar>
                    </div>
                    <v-divider />
                </template>

                <!--      CONTENT          -->
                <template v-slot:item.payed="{ item }">
                    <div
                        :style="{ background: item.payed ? 'green' : 'red' }"
                        style="height: 10px; width: 10px; float: left; border-radius: 50%"
                    />
                </template>

                <template v-slot:item.actions="{ item }">
                    <v-btn
                        v-if="
                            item.type.type &&
                            item.type.type !== TRANSACTION.BALANCE &&
                            item.type.type !== TRANSACTION.DEBT
                        "
                        @click="selectRow(item)"
                        icon
                        small
                    >
                        <v-icon color="blue darken-5" v-text="'mdi-information-variant'" />
                    </v-btn>
                </template>
                <!--      END CONTENT          -->

                <template v-slot:footer>
                    <table-footer :paginated="paginated" />
                </template>
            </v-data-table>
        </div>

        <v-dialog v-model="detailDialog" overlay-opacity="0.7" max-width="1100" content-class="details-dialog">
            <bookkeeping-details v-if="detailDialog" :selected-id="selectedId" @close="detailDialog = $event" />
        </v-dialog>

        <v-dialog persistent v-model="transactionDialog" overlay-opacity="0.8" max-width="900" width="100%">
            <transaction
                v-if="transactionDialog"
                :transaction-types="transactionTypes"
                :drivers="drivers"
                @close="transactionDialog = $event"
            />
        </v-dialog>
    </v-container>
</template>

<script lang="js" src="./index.main.js" />
<style lang="scss" src="./index.style.scss" />
