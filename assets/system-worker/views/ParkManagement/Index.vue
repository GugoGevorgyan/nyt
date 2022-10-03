<!-- @format -->

<template>
    <v-container fluid>
        <v-card tile elevation="4">
            <v-data-table
                loader-height="2"
                dense
                :fixed-header="true"
                :headers="paginated.headers"
                :items="paginated.data"
                :items-per-page="Number(paginated.per_page)"
                :loading="paginated.loading"
                hide-default-footer
                item-key="car_id"
                :calculate-widths="true"
                :height="window.height"
                disable-sort
                :dark="darkMode"
            >
                <!--HEADER-->
                <template v-slot:top>
                    <v-toolbar flat :dark="darkMode" :color="darkMode ? 'black' : 'grey lighten-4'">
                        <v-row>
                            <v-col cols="12" md="2">
                                <v-toolbar-title class="mr-5">Менеджмент парка</v-toolbar-title>
                            </v-col>

                            <v-spacer />

                            <v-col cols="12" md="2">
                                <v-select
                                    v-model="paginated.filter_class"
                                    :items="filter_classes"
                                    color="yellow darken-3"
                                    dense
                                    class="rounded-2"
                                    item-text="class_name"
                                    item-value="car_class_id"
                                    label="Класс"
                                    name="filter_class"
                                    outlined
                                    hide-details
                                />
                            </v-col>

                            <v-col cols="12" md="2">
                                <v-select
                                    v-model="paginated.filter_status"
                                    :items="filter_statuses"
                                    color="yellow darken-3"
                                    class="rounded-2"
                                    dense
                                    item-text="text"
                                    item-value="car_status_id"
                                    label="Статус"
                                    name="filter_status"
                                    outlined
                                    hide-details
                                />
                            </v-col>

                            <v-col cols="12" md="4">
                                <v-text-field
                                    class="rounded-2"
                                    append-icon="mdi-magnify"
                                    color="yellow darken-3"
                                    hide-details
                                    outlined
                                    dense
                                    v-model="paginated.search"
                                    :dark="darkMode"
                                >
                                    <template v-slot:prepend-inner>
                                        <v-select
                                            style="margin-top: -8.5px; right: 10.6px; max-width: 150px"
                                            v-model="paginated.search_attribute"
                                            :items="paginated.search_attributes"
                                            dense
                                            :dark="darkMode"
                                            filled
                                            hide-selected
                                            item-text="text"
                                            item-value="value"
                                            name="search_attribute"
                                            hide-details
                                        />
                                    </template>
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-toolbar>
                </template>

                <template v-slot:item.classes="{ item }">
                    <small>{{ commaJoin(item.classes, "class_name") }}</small>
                </template>
                <template v-slot:item.current_driver="{ item }">
                    <div class="d-flex justify-space-between align-center pa-1" v-if="item.current_driver">
                        <small>
                            {{ item.current_driver.driver_info.name }} {{ item.current_driver.driver_info.patronymic }}
                            {{ item.current_driver.driver_info.surname }}
                        </small>
                        <template>
                            <v-menu v-if="item.current_driver.driver_info.photo" offset-x>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn small icon color="grey darken-2" v-bind="attrs" v-on="on">
                                        <v-icon small dark>mdi-camera-outline</v-icon>
                                    </v-btn>
                                </template>
                                <v-img width="200" :src="item.current_driver.driver_info.photo"></v-img>
                            </v-menu>
                        </template>
                    </div>
                    <v-chip v-else x-small outlined color="error">нет активного водителя</v-chip>
                </template>
                <template v-slot:item.crashes_count="{ item }">
                    <v-chip :color="0 < item.crashes_count ? '#E53935' : '#00C853'" text-color="white" x-small>
                        {{ item.crashes_count ? item.crashes_count : "нет дтп" }}
                        <v-icon x-small v-if="item.crashes_count" right color="white">mdi-alert</v-icon>
                    </v-chip>
                </template>
                <template v-slot:item.insurance_days_left="{ item }">
                    <v-chip :color="30 > item.insurance_days_left ? '#E53935' : '#00C853'" text-color="white" x-small>
                        {{ item.insurance_days_left < 1 ? "истек" : item.insurance_days_left + " дня/дней" }}
                    </v-chip>
                </template>
                <template v-slot:item.inspection_days_left="{ item }">
                    <v-chip :color="30 > item.inspection_days_left ? '#E53935' : '#00C853'" text-color="white" x-small>
                        {{ item.inspection_days_left < 1 ? "истек" : item.inspection_days_left + " дня/дней" }}
                    </v-chip>
                </template>
                <template v-slot:item.status="{ item }">
                    <div class="d-flex align-center justify-space-between">
                        <v-tooltip left>
                            <template v-slot:activator="{ on }">
                                <v-chip x-small :color="item.status.color" outlined v-on="on">
                                    {{ item.status.text }}
                                </v-chip>
                            </template>
                            <span>{{ item.status.description }}</span>
                        </v-tooltip>

                        <v-menu right offset-x transition="scale-transition">
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    :loading="statusLoading === item.car_id"
                                    icon
                                    color="grey darken-2"
                                    small
                                    v-on="on"
                                >
                                    <v-icon small>mdi-cogs</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item-group v-model="item.status_id">
                                    <v-list-item
                                        @click="changeStatus(item.car_id, status.car_status_id)"
                                        v-for="status in statuses"
                                        :key="status.car_status_id"
                                        :value="status.car_status_id"
                                    >
                                        <v-list-item-subtitle>
                                            {{ status.text }} ({{ status.description }})
                                        </v-list-item-subtitle>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-menu>
                    </div>
                </template>
                <template v-slot:item.drivers="{ item }">
                    <div class="d-flex align-center">
                        <v-menu
                            bottom
                            origin="center center"
                            transition="scale-transition"
                            v-if="item.drivers.length < 2"
                            :close-on-content-click="false"
                            :close-on-click="!driverDialog"
                            :loading="true"
                        >
                            <template v-slot:activator="{ on }">
                                <v-btn small icon color="orange" v-on="on">
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                            </template>
                            <v-list width="400px">
                                <v-list-item>
                                    <v-text-field
                                        append-icon="mdi-magnify"
                                        clearable
                                        label="Поиск"
                                        v-model="freeDriverSearch"
                                    />
                                </v-list-item>
                                <div style="overflow-y: auto; min-height: 150px; max-height: 300px">
                                    <template v-if="freeDrivers.length">
                                        <v-list>
                                            <v-list-item-group>
                                                <v-list-item
                                                    v-for="freeDriver in freeDrivers"
                                                    :key="freeDriver.driver_id"
                                                    v-show="!freeDriver.hidden"
                                                    @click="driverDialogShow(freeDriver, item.car_id)"
                                                >
                                                    <v-list-item-avatar>
                                                        <v-img :src="freeDriver.driver_info.photo"></v-img>
                                                    </v-list-item-avatar>
                                                    <v-list-item-content>
                                                        <v-list-item-title>
                                                            {{ freeDriver.driver_info.name }}
                                                            {{ freeDriver.driver_info.surname }}
                                                            {{ freeDriver.driver_info.patronymic }}
                                                        </v-list-item-title>
                                                    </v-list-item-content>
                                                </v-list-item>
                                            </v-list-item-group>
                                        </v-list>
                                    </template>
                                    <div
                                        v-else-if="freeDriversLoading"
                                        class="d-flex justify-center align-center"
                                        style="height: 100%"
                                    >
                                        <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                    </div>
                                    <div v-else class="d-flex justify-center align-center" style="height: 100%">
                                        <span>Результатов не найдено</span>
                                    </div>
                                </div>
                            </v-list>
                        </v-menu>
                        <v-chip
                            v-for="driver in item.drivers"
                            :key="driver.id"
                            color="orange"
                            dark
                            outlined
                            x-small
                            class="mx-1"
                            @click="driverDialogShow(driver, false)"
                        >
                            {{ driver.driver_info.name }} {{ driver.driver_info.patronymic }}
                            {{ driver.driver_info.surname }}
                        </v-chip>
                    </div>
                </template>
                <template v-slot:item.park="{ item }">
                    <span v-if="item.park">{{ item.park.name }}</span>
                    <v-chip v-else x-small outlined color="error">не прикреплен к парку</v-chip>
                </template>

                <!--table footer-->
                <template v-slot:footer>
                    <table-footer :paginated="paginated" />
                </template>
            </v-data-table>
        </v-card>

        <v-dialog max-width="300" width="100%" v-model="driverDialog" persistent>
            <driver-dialog
                :driver="driverDialogData"
                :newCar="driverDialogNewCar"
                :removeDriverLoading="removeDriverLoading"
                @close="driverDialogClose()"
                @updated="driverUpdated()"
                @remove="removeDriverOnCar($event)"
            />
        </v-dialog>
    </v-container>
</template>
<script lang="js" src="./index.main.js" />
<style scoped lang="scss" src="./index.main.scss" />
