<!-- @format -->

<template>
    <div>
        <v-data-table
            :loader-height="2"
            :height="tableHeight"
            :fixed-header="true"
            :headers="paginated.headers"
            :items="paginated.data"
            :items-per-page="Number(paginated.per_page)"
            :loading="paginated.loading"
            hide-default-footer
            item-key="driver_id"
            :calculate-widths="true"
            dense
            disable-sort
        >
            <!--Header-->
            <template v-slot:top>
                <div ref="toolbar" class="px-2">
                    <v-row>
                        <v-col cols="12" md="2">
                            <v-text-field
                                clearable
                                append-icon="mdi-magnify"
                                color="yellow darken-3"
                                hide-details
                                label="Поиск"
                                dense
                                single-line
                                v-model="paginated.search"
                            />
                        </v-col>
                    </v-row>
                </div>
            </template>

            <!--Content-->
            <template v-slot:item.mark="{ item }">
                <small>{{ item.car.mark }}</small> <small>{{ item.car.model }}</small>
            </template>
            <template v-slot:item.color="{ item }">
                <div class="d-flex align-center">
                    <color-round class="mr-2" :color="item.car.color"></color-round>
                    <small>{{ item.car.color }}</small>
                </div>
            </template>
            <template v-slot:item.state_license_plate="{ item }">
                <small>{{ item.car.state_license_plate }}</small>
            </template>
            <template v-slot:item.garage_number="{ item }">
                <small>{{ item.car.garage_number }}</small>
            </template>
            <template v-slot:item.classes="{ item }">
                <small>{{ commaJoin(item.car.classes, "class_name") }}</small>
            </template>
            <template v-slot:item.park="{ item }">
                <small>{{ item.car.park.name }}</small>
            </template>
            <template v-slot:item.driver="{ item }">
                <v-menu transition="slide-x-transition" bottom right offset-x :close-on-content-click="false">
                    <template v-slot:activator="{ on, attrs }">
                        <div class="d-flex justify-space-between align-center" style="width: 100%">
                            <small
                                >{{ item.driver_info.patronymic }} {{ item.driver_info.name }}
                                {{ item.driver_info.surname }}</small
                            >
                            <v-btn small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon small>mdi-eye</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <div class="d-flex pa-2" style="background-color: white">
                        <v-img class="mt-2" width="100" height="100" :src="item.driver_info.photo"></v-img>
                        <v-list class="pa-0">
                            <v-list-item>
                                <v-list-item-content class="pa-0">
                                    <v-list-item-title>Имя:</v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ item.driver_info.patronymic }} {{ item.driver_info.name }}
                                        {{ item.driver_info.surname }}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item two-line>
                                <v-list-item-content>
                                    <v-list-item-title>Телефон:</v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ item.phone }}
                                        <v-btn small icon color="success" @click="$emit('call', item.phone)">
                                            <v-icon small>mdi-phone</v-icon>
                                        </v-btn>
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </div>
                </v-menu>
            </template>
            <template v-slot:item.status="{ item }">
                <div class="d-flex align-center">
                    <template v-if="item.active_order_shipment && item.active_order_shipment.status.status === 1">
                        <v-progress-circular class="mr-2" indeterminate :size="15" :width="2" color="#00E676" />
                        <small>Принимает заказ...</small>
                    </template>
                    <template v-else>
                        <color-round class="mr-2" :color="item.status.color" />
                        <small>{{ item.status.text }}</small>
                    </template>
                </div>
            </template>

            <!--Footer-->
            <template v-slot:footer>
                <table-footer :paginated="paginated" />
            </template>
        </v-data-table>
    </div>
</template>

<script lang="js" src="./BoardsTable.main.js"></script>
