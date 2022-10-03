<!-- @format -->

<template>
    <div>
        <v-card rounded="6" elevation="4" class="border">
            <v-card-title>
                <span class="headline">
                    {{ employee.client_id ? "Обновить информацию клиента" : "Добавить клиента" }}
                </span>
            </v-card-title>
            <v-divider />

            <v-card-text>
                <v-container grid-list-md>
                    <v-form autocomplete="off">
                        <v-layout wrap>
                            <v-flex xs12 :sm6="!employeePhoneDisabled" :sm4="employeePhoneDisabled">
                                <v-text-field
                                    :loading="checkClientLoading"
                                    v-model="employee.phone"
                                    ref='phoneVisible'
                                    label="Телефон*"
                                    color="yellow darken-3"
                                    outlined
                                    v-mask="phoneMask"
                                    data-vv-as="телефон"
                                    name="phone"
                                    :error-messages="errors.collect('phone')"
                                    v-validate="employee.rules.phone"
                                    :disabled="employeePhoneDisabled"
                                    :readonly='employee.client_id > 0'
                                />
                            </v-flex>
                            <v-flex xs12 sm2 v-if="employeePhoneDisabled">
                                <v-btn small color="error" @click="resetEmployee()">Сбросить</v-btn>
                            </v-flex>

                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="employee.surname"
                                    label="Фамилия*"
                                    color="yellow darken-3"
                                    outlined
                                    data-vv-as="фамилия"
                                    name="surname"
                                    :error-messages="errors.collect('surname')"
                                    v-validate="employee.rules.surname"
                                    :disabled="employeeFieldsDisabled"
                                />
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="employee.name"
                                    label="Имя*"
                                    color="yellow darken-3"
                                    outlined
                                    data-vv-as="имя"
                                    name="name"
                                    :error-messages="errors.collect('name')"
                                    v-validate="employee.rules.name"
                                    :disabled="employeeFieldsDisabled"
                                />
                            </v-flex>

                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="employee.patronymic"
                                    label="Отчество"
                                    color="yellow darken-3"
                                    outlined
                                    data-vv-as="отчество"
                                    name="patronymic"
                                    :error-messages="errors.collect('patronymic')"
                                    v-validate="employee.rules.patronymic"
                                    :disabled="employeeFieldsDisabled"
                                />
                            </v-flex>

                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="employee.limit"
                                    label="Лимит"
                                    color="yellow darken-3"
                                    outlined
                                    clearable
                                    data-vv-as="лимит"
                                    name="limit"
                                    :error-messages="errors.collect('limit')"
                                    v-validate="employee.rules.limit"
                                    :disabled="employeeFieldsDisabled"
                                />
                            </v-flex>

                            <v-flex xs12 sm6>
                                <v-select
                                    v-model="employee.car_classes"
                                    :items="carClasses"
                                    item-text="class_name"
                                    item-value="car_class_id"
                                    item-color="yellow darken-3"
                                    :menu-props="{ offsetY: true }"
                                    label="Классы автомобилей"
                                    color="yellow darken-3"
                                    outlined
                                    :multiple="classMultiple"
                                    clearable
                                    open-on-clear
                                    data-vv-as="классы автомобилей"
                                    name="car_classes"
                                    :error-messages="errors.collect('car_classes')"
                                    v-validate="employee.rules.car_classes"
                                    :disabled="employeeFieldsDisabled"
                                />
                            </v-flex>

                            <v-flex xs5>
                                <v-checkbox
                                    v-model="employee.allow_weekends"
                                    label="Разрешить выходные"
                                    color="yellow darken-3"
                                    data-vv-as="разрешить выходные"
                                    name="allow_weekends"
                                    :error-messages="errors.collect('allow_weekends')"
                                    v-validate="employee.rules.allow_weekends"
                                    :disabled="employeeFieldsDisabled"
                                />
                                <v-checkbox
                                    v-model="employee.allow_order"
                                    color="yellow darken-3"
                                    label="Разрешить заказы"
                                    data-vv-as="разрешить заказы"
                                    name="разрешить заказы"
                                    :error-messages="errors.collect('allow_order')"
                                    v-validate="employee.rules.allow_order"
                                    :disabled="employeeFieldsDisabled"
                                />
                            </v-flex>
                        </v-layout>
                    </v-form>
                </v-container>
            </v-card-text>

            <v-divider />

            <v-card-actions>
                <v-spacer />
                <v-btn text @click="$emit('close')">Отмена</v-btn>
                <v-btn
                    dark
                    :loading="employeeLoading"
                    color="yellow darken-3"
                    depressed
                    @click="clientSaveOrUpdate ? updateEmployee() : createEmployee()"
                >
                    {{ clientSaveOrUpdate ? "Обновить" : "Сохранить" }}
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-dialog v-model="existsClientDialog" max-width="400" width="100%">
            <v-card v-if="existsClient || existsClientCompany">
                <v-card-title>Информация о {{ employee.phone }}</v-card-title>
                <v-card-text>
                    <v-alert border="left" colored-border type="info" elevation="2" v-if="existsClientCompany">
                        Невозможно создать корпоративного клиента с данным номером телефона
                    </v-alert>
                    <v-alert border="left" colored-border type="info" elevation="2" v-else>
                        Клиент с номером {{ existsClient.phone }} уже существует!
                    </v-alert>
                </v-card-text>
                <v-card-actions v-if="existsClientCompany" class="d-flex justify-end">
                    <v-btn text color="primary" @click="closeExistsClientDialog()">Ок</v-btn>
                </v-card-actions>
                <v-card-actions v-else class="d-flex justify-end">
                    <v-btn text tile color="error" @click="closeExistsClientDialog()">Отмена</v-btn>
                    <v-btn text tile color="primary" @click="setClientValues()"> Загрузить информацию </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script src="./Employee.main.js" />
