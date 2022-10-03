<!-- @format -->

<template>
    <v-container fluid>
        <v-card outlined>
            <!--Data Table-->
            <v-data-table
                class="elevation-4"
                loader-height="2"
                dense
                :fixed-header="true"
                :headers="paginated.headers"
                :items="paginated.data"
                :items-per-page="Number(paginated.per_page)"
                :loading="paginated.loading"
                hide-default-footer
                item-key="contract_id"
                :calculate-widths="true"
                :height="window.height"
                disable-sort
            >
                <!--HEADER-->
                <template v-slot:top>
                    <v-toolbar flat color="grey lighten-3">
                        <v-toolbar-title class="mr-5">История контрактов</v-toolbar-title>
                        <v-spacer />
                        <v-text-field
                            append-icon="mdi-magnify"
                            clearable
                            color="yellow darken-3"
                            hide-details
                            label="Поиск"
                            single-line
                            outlined
                            dense
                            v-model="paginated.search"
                        />
                        <v-spacer />
                        <v-select
                            style="max-width: 300px"
                            color="yellow darken-3"
                            outlined
                            dense
                            clearable
                            label="Статус"
                            :items="selectActiveItems"
                            item-text="text"
                            item-value="value"
                            v-model="paginated.active"
                            item-color="yellow darken-3"
                            menu-props="offsetY"
                            single-line
                            hide-details
                        />
                    </v-toolbar>
                </template>

                <!--content-->
                <template v-slot:item.car="{ item }">
                    <v-chip x-small @click="showCarDialog(item.car)" color="orange" outlined>{{
                        item.car.garage_number
                    }}</v-chip>
                </template>
                <template v-slot:item.active="{ item }">
                    <v-icon small v-if="item.active" color="success" v-text="'mdi-check'" />
                    <v-icon small v-else color="error" v-text="'mdi-close'" />
                </template>
                <template v-slot:item.created_at="{ item }">
                    {{ item.created_at | dateFormat }}
                </template>
                <template v-slot:item.duration="{ item }"> {{ item.duration }} дней </template>
                <template v-slot:item.terminate="{ item }">
                    <v-btn v-if="item.active" icon color="error" @click="showTerminateContractDialog(item)">
                        <v-icon small v-text="'mdi-delete-circle-outline'" />
                    </v-btn>
                    <span v-else>
                        {{ item.updated_at | formatDate }}
                    </span>
                </template>
                <template v-slot:item.contract_download="{ item }">
                    <v-btn color="green lighten-2" icon tile @click="downloadContract(item.driver_contract_id)">
                        <v-icon v-text="'mdi-download'" />
                    </v-btn>
                </template>
                <template v-slot:item.busy_days_price="{ item }">
                <v-menu
                    transition="slide-x-transition"
                    left
                    right
                    offset-x
                    :close-on-content-click="false"
                >
                    <template v-slot:activator='{ on, attrs }'>
                        <div class='d-flex justify-center align-center' style='width: 100%'>
                            <v-btn x-small v-bind='attrs' icon color='primary' v-on='on'>
                                <v-icon>mdi-information-outline</v-icon>
                            </v-btn>
                        </div>
                    </template>
                    <div style="background-color: white" class='pt-5'>
                        <v-list class='pa-0'>
                            <v-list-item>
                                <v-text-field
                                    v-model='item.free_days_price'
                                    v-validate='rules.free_days_price'
                                    :error-messages="errors.collect('free_days_price')"
                                    color='yellow darken-3'
                                    data-vv-as='цена свободных дней'
                                    dense
                                    label='Цена свободных дней'
                                    name='free_days_price'
                                    outlined
                                    prepend-inner-icon='mdi-currency-usd'
                                    type='number'
                                />
                            </v-list-item>
                            <v-list-item>
                                <v-text-field
                                    v-model='item.busy_days_price'
                                    v-validate='rules.busy_days_price'
                                    :error-messages="errors.collect('busy_days_price')"
                                    color='yellow darken-3'
                                    data-vv-as='цена свободных дней'
                                    dense
                                    label='Цена занятых дней'
                                    name='busy_days_price'
                                    outlined
                                    prepend-inner-icon='mdi-currency-usd'
                                    type='number'
                                />
                            </v-list-item>
                            <v-list-item>
                                <v-btn @click='editContractPrice(item)' color='info'>Обновить</v-btn>
                            </v-list-item>
                        </v-list>
                    </div>
                </v-menu>
                </template>
                <!--table footer-->
                <template v-slot:footer>
                    <table-footer :paginated="paginated" />
                </template>
            </v-data-table>
        </v-card>

        <!--Show car dialog-->
        <v-dialog v-model="carDialog" max-width="400" width="100%">
            <v-card v-if="showCar">
                <v-card-title class="justify-space-between">
                    Car number {{ showCar.garage_number }}
                    <v-btn icon color="error" @click="carDialog = false">
                        <v-icon v-text="'mdi-close'" />
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-list dense>
                        <v-list-item>
                            <v-list-item-content>Park:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.park.name }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Garage number:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.garage_number }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Class:</v-list-item-content>
                            <v-list-item-content class="align-end">{{
                                showCar.car_class.class_name
                            }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Model:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.model }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Mark:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.mark }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>State license plate:</v-list-item-content>
                            <v-list-item-content class="align-end">{{
                                showCar.state_license_plate
                            }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Vin code:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.vin_code }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Year:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.year }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Speedometer:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.speedometer }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Status:</v-list-item-content>
                            <v-list-item-content class="d-block">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-chip :color="showCar.status.color" small outlined>
                                            {{ showCar.status.text }}
                                        </v-chip>
                                    </template>
                                    <span>{{ showCar.status.description }}</span>
                                </v-tooltip>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Inspection date:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.inspection_date }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Inspection expiration date:</v-list-item-content>
                            <v-list-item-content class="align-end">{{
                                showCar.inspection_expiration_date
                            }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Insurance date:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.insurance_date }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Insurance expiration date:</v-list-item-content>
                            <v-list-item-content class="align-end">{{
                                showCar.insurance_expiration_date
                            }}</v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>Created:</v-list-item-content>
                            <v-list-item-content class="align-end">{{ showCar.created_at }}</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-card-text>
            </v-card>
        </v-dialog>

        <!--Show contract terminate dialog-->
        <v-dialog v-model="terminateDialog" max-width="400" width="100%">
            <v-card v-if="terminateContract">
                <v-card-title class="justify-space-between">
                    Расторжение контракта
                    <v-btn icon color="error" @click="closeTerminateDialog()">
                        <v-icon v-text="'mdi-close'" />
                    </v-btn>
                </v-card-title>
                <v-divider />
                <v-card-text>
                    <p class="my-2">
                        Вы уверены, что хотите расторгнуть контракт с
                        <strong>
                            {{ terminateContract.driver.driver_info.name }}
                            {{ terminateContract.driver.driver_info.surname }}
                            {{ terminateContract.driver.driver_info.patronymic }}
                        </strong>
                        ?
                    </p>
                    <p>Пожалуйста введите пароль для этого действия.</p>
                    <v-form>
                        <input :value="$csrf" name="_token" type="hidden" />
                        <v-text-field
                            data-vv-as="пароль"
                            label="Пароль"
                            name="password"
                            type="password"
                            v-model="terminatePassword"
                            v-validate="'required|min:6|max:32'"
                            :error-messages="errors.collect('password')"
                        />
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn color="primary" text @click="closeTerminateDialog()">отмена</v-btn>
                    <v-btn :loading="terminateLoading" color="error" text @click="terminate()">расторгнуть</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script lang="js" src="./index.main.js" />
<style scoped lang="scss" src="./index.main.scss" />
