<!-- @format -->

<template>
    <v-card outlined elevation="4" tile height="100%">
        <v-card-title ref="title">{{ title }}</v-card-title>
        <v-divider />
        <v-card-text>
            <v-form :data-vv-scope="entity.scope" autocomplete="off">
                <v-row>
                    <v-col cols="12" md="6">
                        <div class="mb-4 text-center">
                            <span class="title black--text font-weight-light">Основная информация</span>
                        </div>
                        <v-layout wrap>
                            <v-flex xs6 class="pa-1">
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.name`)"
                                    label="Название организации"
                                    name="name"
                                    v-model="entity.name"
                                    v-validate="entity.rules.name"
                                    data-vv-as="название организации"
                                />
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.email`)"
                                    label="Эл. адрес"
                                    name="email"
                                    v-model="entity.email"
                                    v-validate="entity.rules.email"
                                    data-vv-as="эл. адрес"
                                />
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.phone`)"
                                    label="Телефон"
                                    name="phone"
                                    v-mask="phoneMask"
                                    v-model="entity.phone"
                                    v-validate="entity.rules.phone"
                                    data-vv-as="телефон"
                                />
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.address`)"
                                    label="Адрес"
                                    name="address"
                                    v-model="entity.address"
                                    v-validate="entity.rules.address"
                                    data-vv-as="адрес"
                                />
                            </v-flex>
                            <v-flex xs6 class="pa-1">
                                <v-autocomplete
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.country_id`)"
                                    :items="countries"
                                    item-value="country_id"
                                    item-text="name"
                                    label="Страна"
                                    name="country_id"
                                    v-model="entity.country_id"
                                    v-validate="entity.rules.country_id"
                                    data-vv-as="страна"
                                />
                                <v-autocomplete
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :loading="entity.regionsLoading"
                                    :disabled="!entity.regionsSelectable.length"
                                    :error-messages="errors.collect(`${entity.scope}.region_id`)"
                                    :items="entity.regionsSelectable"
                                    item-value="region_id"
                                    item-text="name"
                                    label="Регион"
                                    name="region_id"
                                    v-model="entity.region_id"
                                    v-validate="entity.rules.region_id"
                                    data-vv-as="регион"
                                />
                                <v-autocomplete
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :loading="entity.citiesLoading"
                                    :disabled="!entity.citiesSelectable.length"
                                    :error-messages="errors.collect(`${entity.scope}.city_id`)"
                                    :items="entity.citiesSelectable"
                                    item-value="city_id"
                                    item-text="name"
                                    label="Город"
                                    name="city_id"
                                    v-model="entity.city_id"
                                    v-validate="entity.rules.city_id"
                                    data-vv-as="город"
                                />
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.zip_code`)"
                                    label="Почтовый индекс (zip)"
                                    name="zip_code"
                                    v-model="entity.zip_code"
                                    v-validate="entity.rules.zip_code"
                                    data-vv-as="почтовый индекс"
                                />
                            </v-flex>
                        </v-layout>

                        <div class="mb-4 text-center">
                            <span class="title black--text font-weight-light">Директор</span>
                        </div>

                        <v-layout wrap>
                            <v-flex xs6 class="pa-1">
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.director_surname`)"
                                    label="Фамилия директора"
                                    name="director_surname"
                                    v-model="entity.director_surname"
                                    v-validate="entity.rules.director_surname"
                                    data-vv-as="фамилия директора"
                                />
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.director_patronymic`)"
                                    label="Отчество директора"
                                    name="director_patronymic"
                                    v-model="entity.director_patronymic"
                                    v-validate="entity.rules.director_patronymic"
                                    data-vv-as="отчество директора"
                                />
                            </v-flex>
                            <v-flex xs6 class="pa-1">
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.director_name`)"
                                    label="Имя директора"
                                    name="director_name"
                                    v-model="entity.director_name"
                                    v-validate="entity.rules.director_name"
                                    data-vv-as="имя директора"
                                />
                            </v-flex>
                        </v-layout>

                        <!-- banks -->
                        <div class="mb-4 d-flex align-center justify-center">
                            <span class="title black--text font-weight-light">Банки</span>
                            <v-btn color="primary" fab @click="addBank()" x-small class="ml-4">
                                <v-icon small>mdi-plus</v-icon>
                            </v-btn>
                        </div>
                        <div class="pa-1" style="height: 165px; border-radius: 4px; border: 1px dashed gray">
                            <div class="pa-1 " style="height: 100%; overflow-y: auto">
                                <v-list class="pa-0" dense v-if="allBanks.length">
                                    <v-list-item class="elevation-3 mb-1" v-for="(item, i) in allBanks" :key="i">
                                        <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-space-between align-center">
                                                <span>{{ item.name }}</span>
                                                <div>
                                                    <v-btn x-small icon color="primary" @click="updateBank(item, i)">
                                                        <v-icon small>mdi-pencil</v-icon>
                                                    </v-btn>
                                                    <v-btn
                                                        :loading="
                                                            item.entity_bank_id &&
                                                            bankDeleteLoading === item.entity_bank_id
                                                        "
                                                        x-small
                                                        icon
                                                        color="error"
                                                        @click="
                                                            item.entity_bank_id
                                                                ? removeBankRequest(item.entity_bank_id)
                                                                : removeBank(i)
                                                        "
                                                    >
                                                        <v-icon small>mdi-close</v-icon>
                                                    </v-btn>
                                                </div>
                                            </v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                                <div
                                    v-else
                                    class="d-flex justify-center align-center"
                                    style="height: 100%; width: 100%"
                                >
                                    <span class="title">Добавьте банк</span>
                                </div>
                            </div>
                        </div>
                    </v-col>
                    <!--                    <v-divider vertical class="pa-5" />-->
                    <v-col cols="12" md="6">
                        <div class="mb-4 text-center">
                            <span class="title black--text font-weight-light">Регистрация</span>
                        </div>
                        <v-layout wrap>
                            <v-flex xs6 class="pa-1">
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.tax_inn`)"
                                    label="ИНН"
                                    name="tax_inn"
                                    hint="Идентификационный номер налогоплательщика"
                                    v-model="entity.tax_inn"
                                    v-validate="entity.rules.tax_inn"
                                    data-vv-as="ИНН"
                                />
                                <v-text-field
                                    dense
                                    color="yellow darken-3"
                                    outlined
                                    :error-messages="errors.collect(`${entity.scope}.registration_certificate_number`)"
                                    label="Номер свидетельства о регистрации"
                                    hint="Номер свидетельства о регистрации"
                                    name="registration_certificate_number"
                                    v-model="entity.registration_certificate_number"
                                    v-validate="entity.rules.registration_certificate_number"
                                    data-vv-as="Номер свидетельства о регистрации"
                                />
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.tax_psrn`)"
                                    label="ОГРН"
                                    hint="Основной государственный регистрационный номер"
                                    name="tax_psrn"
                                    v-model="entity.tax_psrn"
                                    v-validate="entity.rules.tax_psrn"
                                    data-vv-as="ОГРН"
                                />
                                <v-text-field
                                    dense
                                    color="yellow darken-3"
                                    outlined
                                    :error-messages="errors.collect(`${entity.scope}.tax_psrn_issued_by`)"
                                    label="Кем выдан ОГРН"
                                    name="tax_psrn_issued_by"
                                    v-model="entity.tax_psrn_issued_by"
                                    v-validate="entity.rules.tax_psrn_issued_by"
                                    data-vv-as="Кем выдан ОГРН"
                                />
                                <v-text-field
                                    dense
                                    color="yellow darken-3"
                                    outlined
                                    :error-messages="errors.collect(`${entity.scope}.arcfo`)"
                                    label="ОКФС"
                                    hint="Общероссийский классификатор форм собственности"
                                    name="arcfo"
                                    v-model="entity.arcfo"
                                    v-validate="entity.rules.arcfo"
                                    data-vv-as="ОКФС"
                                />
                                <v-text-field
                                    dense
                                    color="yellow darken-3"
                                    outlined
                                    :error-messages="errors.collect(`${entity.scope}.aucneb`)"
                                    label="ОКОНХ"
                                    hint="Общесоюзный классификатор отраслей народного хозяйства"
                                    name="aucneb"
                                    v-model="entity.aucneb"
                                    v-validate="entity.rules.aucneb"
                                    data-vv-as="ОКОНХ"
                                />
                            </v-flex>
                            <v-flex xs6 class="pa-1">
                                <v-select
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.type_id`)"
                                    label="Тип организации"
                                    name="type_id"
                                    v-model="entity.type_id"
                                    v-validate="entity.rules.type_id"
                                    data-vv-as="тип организации"
                                    :items="entityTypes"
                                    item-text="name"
                                    item-value="entity_type_id"
                                />

                                <date-picker
                                    prepend-inner-icon="mdi-calendar"
                                    v-model="entity.registration_certificate_date"
                                    v-validate="entity.rules.registration_certificate_date"
                                    label="Дата окончания контракта"
                                    name="registration_certificate_date"
                                    :error-messages="errors.collect('registration_certificate_date')"
                                    data-vv-as="дата окончания контракта"
                                    @blur="date = parseDate(entity.registration_certificate_date)"
                                />
                                
                                <v-text-field
                                    dense
                                    color="yellow darken-3"
                                    outlined
                                    :error-messages="errors.collect(`${entity.scope}.tax_psrn_serial`)"
                                    label="Серия ОГРН"
                                    name="tax_psrn_serial"
                                    v-model="entity.tax_psrn_serial"
                                    v-validate="entity.rules.tax_psrn_serial"
                                    data-vv-as="Серия ОГРН"
                                />
                                
                                <date-picker
                                    prepend-inner-icon="mdi-calendar"
                                    v-model="entity.tax_psrn_date"
                                    v-validate="entity.rules.tax_psrn_date"
                                    label="Дата выдачи ОГРН"
                                    name="tax_psrn_date"
                                    :error-messages="errors.collect('tax_psrn_date')"
                                    data-vv-as="дата выдачи ОГРН"
                                    @blur="date = parseDate(entity.tax_psrn_date)"
                                />

                                <v-text-field
                                    dense
                                    color="yellow darken-3"
                                    outlined
                                    :error-messages="errors.collect(`${entity.scope}.arceo`)"
                                    label="ОКПО"
                                    hint="Общероссийский классификатор предприятий и организаций"
                                    name="arceo"
                                    v-model="entity.arceo"
                                    v-validate="entity.rules.arceo"
                                    data-vv-as="ОКПО"
                                />
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${entity.scope}.tax_kpp`)"
                                    label="КПП"
                                    hint="Код причины постановки"
                                    name="tax_kpp"
                                    v-model="entity.tax_kpp"
                                    v-validate="entity.rules.tax_kpp"
                                    data-vv-as="КПП"
                                />
                                <v-text-field
                                    dense
                                    color="yellow darken-3"
                                    outlined
                                    :error-messages="errors.collect(`${entity.scope}.arclf`)"
                                    label="ОКОПФ"
                                    hint="Общероссийский классификатор организационно-правовых форм"
                                    name="arclf"
                                    v-model="entity.arclf"
                                    v-validate="entity.rules.arclf"
                                    data-vv-as="ОКОПФ"
                                />
                            </v-flex>
                        </v-layout>
                    </v-col>
                </v-row>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="justify-end">
            <v-btn color="primary" :loading="loading" @click="entityObj ? update() : store()">{{
                entityObj ? "Обновить" : "Создать"
            }}</v-btn>
        </v-card-actions>

        <!--Bank dialog-->
        <v-dialog persistent v-model="bankDialog" width="600">
            <v-card :loading="bankDialogLoading">
                <v-card-title>
                    {{ ~updateBankIndex ? "Обновить банк " + allBanks[updateBankIndex].name : "Новый банк" }}
                </v-card-title>
                <v-card-text>
                    <v-form :data-vv-scope="bank.scope">
                        <div class="mb-4 text-center">
                            <span class="title black--text font-weight-light">Основная информация</span>
                        </div>
                        <v-layout wrap>
                            <v-flex xs6 class="pa-1">
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${bank.scope}.name`)"
                                    label="Название банка"
                                    name="name"
                                    v-model="bank.name"
                                    v-validate="bank.rules.name"
                                    data-vv-as="название"
                                />
                                <v-autocomplete
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :loading="bank.regionsLoading"
                                    :disabled="!bank.regionsSelectable.length"
                                    :error-messages="errors.collect(`${bank.scope}.region_id`)"
                                    :items="bank.regionsSelectable"
                                    item-value="region_id"
                                    item-text="name"
                                    label="Регион"
                                    name="region_id"
                                    v-model="bank.region_id"
                                    v-validate="bank.rules.region_id"
                                    data-vv-as="регион"
                                />
                            </v-flex>
                            <v-flex xs6 class="pa-1">
                                <v-autocomplete
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${bank.scope}.country_id`)"
                                    :items="countries"
                                    item-value="country_id"
                                    item-text="name"
                                    label="Страна"
                                    name="country_id"
                                    v-model="bank.country_id"
                                    v-validate="bank.rules.country_id"
                                    data-vv-as="страна"
                                />
                                <v-autocomplete
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :loading="bank.citiesLoading"
                                    :disabled="!bank.citiesSelectable.length"
                                    :error-messages="errors.collect(`${bank.scope}.city_id`)"
                                    :items="bank.citiesSelectable"
                                    item-value="city_id"
                                    item-text="name"
                                    label="Город"
                                    name="city_id"
                                    v-model="bank.city_id"
                                    v-validate="bank.rules.city_id"
                                    data-vv-as="город"
                                />
                            </v-flex>
                        </v-layout>
                        <div class="mb-4 text-center">
                            <span class="title black--text font-weight-light">Номера счетов</span>
                        </div>
                        <v-layout wrap>
                            <v-flex xs12 class="pa-1">
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${bank.scope}.bank_account_number`)"
                                    label="Номер банковского счета"
                                    name="bank_account_number"
                                    v-model="bank.bank_account_number"
                                    v-validate="bank.rules.bank_account_number"
                                    data-vv-as="номер банковского счета"
                                />
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${bank.scope}.correspondent_account_number`)"
                                    label="Номер корреспондентского счета"
                                    name="correspondent_account_number"
                                    v-model="bank.correspondent_account_number"
                                    v-validate="bank.rules.correspondent_account_number"
                                    data-vv-as="номер корреспондентского счета"
                                />
                                <v-text-field
                                    color="yellow darken-3"
                                    outlined
                                    dense
                                    :error-messages="errors.collect(`${bank.scope}.bank_identification_account`)"
                                    label="Банковский идентификационный счет"
                                    name="bank_identification_account"
                                    v-model="bank.bank_identification_account"
                                    v-validate="bank.rules.bank_identification_account"
                                    data-vv-as="банковский идентификационный счет"
                                />
                            </v-flex>
                        </v-layout>
                    </v-form>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn color="error" text small @click="closeBankDialog()">отмена</v-btn>
                    <v-btn
                        color="primary"
                        small
                        :loading="bankDialogLoading"
                        @click="
                            entityObj ? (bank.entity_bank_id ? updateBankRequest() : createBankRequest()) : appendBank()
                        "
                        >принять</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script lang="js" src="./EntityForm.main.js"/>
