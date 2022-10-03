<!-- @format -->

<template>
    <div>
        <v-card tile elevation="6">
            <v-card-title class="grey lighten-5">
                {{ companyObj ? "Информация о компании " + companyObj.name : "Добавить новую компанию" }}
            </v-card-title>
            <v-divider />
            <v-card-text :style="{ height: contentHeight + 'px' }" style="overflow-y: auto">
                <v-container>
                    <v-form data-vv-scope="company" autocomplete="off">
                        <v-row>
                            <v-col cols="12" lg="6">
                                <v-subheader>Общая информация</v-subheader>
                                <v-row>
                                    <v-col cols="12" lg="4">
                                        <v-text-field
                                            :error-messages="errors.collect('company.name')"
                                            v-model="company.name"
                                            color="yellow darken-3"
                                            label="Название"
                                            name="name"
                                            v-validate="company.rules.name"
                                            outlined
                                            dense
                                            data-vv-as="название"
                                        />
                                    </v-col>
                                    <v-col cols="12" lg="4">
                                        <v-text-field
                                            type="email"
                                            :error-messages="errors.collect('company.email')"
                                            v-model="company.email"
                                            color="yellow darken-3"
                                            label="Эл. адрес"
                                            name="email"
                                            v-validate="company.rules.email"
                                            outlined
                                            dense
                                            data-vv-as="эл. адрес"
                                        />
                                    </v-col>
                                    <v-col cols="12" lg="4">
                                        <v-text-field
                                            :error-messages="errors.collect('company.address')"
                                            v-model="company.address"
                                            color="yellow darken-3"
                                            label="Адрес голавного офиса"
                                            name="address"
                                            v-validate="company.rules.address"
                                            outlined
                                            dense
                                            data-vv-as="адрес"
                                        />
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" lg="12">
                                        <v-textarea
                                            rows="3"
                                            :error-messages="errors.collect('company.details')"
                                            v-model="company.details"
                                            color="yellow darken-3"
                                            label="Детали"
                                            name="details"
                                            v-validate="company.rules.details"
                                            outlined
                                            dense
                                            data-vv-as="детали"
                                        />
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" lg="4">
                                        <v-text-field
                                            :error-messages="errors.collect('company.period')"
                                            v-model="company.period"
                                            color="yellow darken-3"
                                            label="Период времени (мес)"
                                            name="period"
                                            type="number"
                                            v-validate="company.rules.period"
                                            outlined
                                            dense
                                            hint="Период времени в месяцах"
                                            data-vv-as="период времени"
                                        />
                                    </v-col>
                                    <v-col cols="12" lg="4">
                                        <v-text-field
                                            :error-messages="errors.collect('company.frequency')"
                                            v-model="company.frequency"
                                            color="yellow darken-3"
                                            label="Количество отчетов"
                                            name="frequency"
                                            type="number"
                                            v-validate="company.rules.frequency"
                                            outlined
                                            dense
                                            hint="Количество отчетов в установленный период времени"
                                            data-vv-as="количество отчетов"
                                        />
                                    </v-col>
                                    <v-col cols="12" lg="4">
                                        <v-text-field
                                            :error-messages="errors.collect('company.limit')"
                                            v-model="company.limit"
                                            color="yellow darken-3"
                                            label="Максимальная сумма заказов"
                                            name="limit"
                                            type="number"
                                            v-validate="company.rules.limit"
                                            outlined
                                            dense
                                            data-vv-as="максимальная сумма заказов"
                                        />
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" lg="4">
                                        <v-text-field
                                            :loading="codeLoading"
                                            :error-messages="errors.collect('company.code')"
                                            v-model="company.code"
                                            color="yellow darken-3"
                                            label="Внутренний код компании"
                                            name="code"
                                            type="number"
                                            v-validate="company.rules.code"
                                            outlined
                                            dense
                                            data-vv-as="внутренний код компании"
                                        />
                                    </v-col>
                                    <v-col cols="12" lg="4">
                                        <v-text-field
                                            :error-messages="errors.collect('company.order_canceled_timeout')"
                                            v-model="company.order_canceled_timeout"
                                            color="yellow darken-3"
                                            label="Время отемны заказа"
                                            name="order_canceled_timeout"
                                            type="number"
                                            v-validate="company.rules.order_canceled_timeout"
                                            outlined
                                            dense
                                            data-vv-as="время отемны заказа"
                                        />
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" lg="6">
                                        <div class="d-flex">
                                            <v-autocomplete
                                                class="mr-2"
                                                :items="entities"
                                                clearable
                                                data-vv-as="юридическое лицо"
                                                color="yellow darken-3"
                                                item-color="yellow darken-3"
                                                item-text="name"
                                                item-value="legal_entity_id"
                                                label="Юридическое лицо"
                                                open-on-clear
                                                name="entity_id"
                                                :error-messages="errors.collect('company.entity_id')"
                                                v-model="company.entity_id"
                                                v-validate="company.rules.entity_id"
                                                outlined
                                                dense
                                            />
                                            <v-tooltip right>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-btn
                                                        v-on="on"
                                                        fab
                                                        small
                                                        color="primary"
                                                        :href="
                                                            $router.resolve({
                                                                name: 'legal_entity_create',
                                                            }).href
                                                        "
                                                    >
                                                        <v-icon>mdi-plus</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Создать новое юр. лицо</span>
                                            </v-tooltip>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6" v-if="!adminsCount" class="d-flex justify-end">
                                        <v-alert
                                            v-if="company.admin_added"
                                            type="info"
                                            dense
                                            outlined
                                            style="width: 100%"
                                        >
                                            <div class="d-flex align-center">
                                                <div>
                                                    <small>Данные администратор приняты.</small>
                                                    <small v-if="companyObj"
                                                        >Обновите информацию компании чтобы добавить
                                                        администратора.</small
                                                    >
                                                </div>
                                                <v-spacer></v-spacer>
                                                <v-btn icon color="primary" small @click="adminDialog = true">
                                                    <v-icon>mdi-pencil</v-icon>
                                                </v-btn>
                                                <v-btn icon color="error" small @click="cancelAdmin()">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                            </div>
                                        </v-alert>
                                        <v-btn
                                            v-else
                                            color="primary"
                                            outlined
                                            small
                                            class="rounded-0"
                                            @click="adminDialog = true"
                                            >добавить администратора</v-btn
                                        >
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" sm="1" class="d-flex justify-center">
                                <v-divider vertical />
                            </v-col>
                            <v-col cols="12" lg="5">
                                <v-subheader>Телефонные номера компании</v-subheader>
                                <v-row>
                                    <v-col cols="12" lg="8">
                                        <v-text-field
                                            v-mask="phoneMask"
                                            :error-messages="errors.collect('company.phone')"
                                            v-model="company.phone"
                                            color="yellow darken-3"
                                            label="Телефон"
                                            name="phone"
                                            v-validate="company.rules.phone"
                                            outlined
                                            dense
                                            data-vv-as="телефон"
                                        />
                                    </v-col>
                                    <v-col cols="12" lg="2">
                                        <v-btn color="yellow darken-3" icon @click="addPhone">
                                            <v-icon v-text="'mdi-plus'" />
                                        </v-btn>
                                    </v-col>
                                </v-row>
                                <v-row v-for="(item, index) in company.additional_phones" :key="index">
                                    <v-col cols="12" lg="8">
                                        <v-text-field
                                            v-mask="phoneMask"
                                            :error-messages="errors.collect('company.phone' + index)"
                                            v-model="company.additional_phones[index]"
                                            color="yellow darken-3"
                                            label="Дополнительный телефон"
                                            :name="'phone' + index"
                                            v-validate="company.rules.phone"
                                            outlined
                                            dense
                                            data-vv-as="дополнительный телефон"
                                        />
                                    </v-col>
                                    <v-col cols="12" lg="2">
                                        <v-btn icon color="error" @click="removePhone(index)">
                                            <v-icon>mdi-close</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                                <v-subheader>Контракт компании</v-subheader>
                                <v-row>
                                    <v-col cols="12" lg="5">
                                        <v-card>
                                            <v-img
                                                contain
                                                :src="company.contract_scan ? company.contract_scan : lazyImage"
                                                max-height="300"
                                                @click="
                                                    company.contract_scan ? showImgDialog(company.contract_scan) : null
                                                "
                                            >
                                            </v-img>
                                            <v-card-actions>
                                                <v-file-input
                                                    :clearable="false"
                                                    @change="previewImage($event, 'contract_scan')"
                                                    :error-messages="errors.collect('company.contract_scan_file')"
                                                    :prepend-icon="null"
                                                    append-icon="mdi-camera"
                                                    label="Скан контракта"
                                                    name="contract_scan_file"
                                                    data-vv-as="скан контракта"
                                                    type="file"
                                                    v-model="company.contract_scan_file"
                                                    v-validate="companyObj ? null : company.rules.contract_scan_file"
                                                />
                                            </v-card-actions>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" lg="7">
                                        <date-picker
                                            prepend-inner-icon="mdi-calendar"
                                            v-model="company.contract_start"
                                            v-validate="company.rules.contract_start"
                                            label="Дата начала контракта"
                                            name="contract_start"
                                            :error-messages="errors.collect('contract_start')"
                                            data-vv-as="дата начала контракта"
                                            @blur="date = parseDate(company.contract_start)"
                                        />

                                        <date-picker
                                            prepend-inner-icon="mdi-calendar"
                                            v-model="company.contract_end"
                                            v-validate="company.rules.contract_end"
                                            label="Дата окончания контракта"
                                            name="contract_end"
                                            :error-messages="errors.collect('contract_end')"
                                            data-vv-as="дата окончания контракта"
                                            @blur="date = parseDate(company.contract_end)"
                                        />
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-container>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer />
                <v-btn :loading="companyLoading" color="yellow darken-2" tile @click="companyObj ? update() : create()">
                    {{ companyObj ? "Обновить информацию" : "Создать компанию" }}
                </v-btn>
            </v-card-actions>
        </v-card>

        <!--Admin dialog-->
        <v-dialog v-model="adminDialog" max-width="600" width="100%" v-if="!adminsCount">
            <v-card>
                <v-card-title>
                    <span>Добавление администратора</span>
                    <v-spacer></v-spacer>
                    <v-btn icon color="error" small @click="adminDialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-form data-vv-scope="adminCorporate" autocomplete="off">
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    :error-messages="errors.collect('adminCorporate.surname')"
                                    v-model="adminCorporate.surname"
                                    color="yellow darken-3"
                                    label="Фамилия"
                                    name="surname"
                                    v-validate="adminCorporate.rules.surname"
                                    outlined
                                    dense
                                    data-vv-as="фамилия"
                                />
                                <v-text-field
                                    :error-messages="errors.collect('adminCorporate.name')"
                                    v-model="adminCorporate.name"
                                    color="yellow darken-3"
                                    label="Имя"
                                    name="name"
                                    v-validate="adminCorporate.rules.name"
                                    outlined
                                    dense
                                    data-vv-as="имя"
                                />
                                <v-text-field
                                    :error-messages="errors.collect('adminCorporate.patronymic')"
                                    v-model="adminCorporate.patronymic"
                                    color="yellow darken-3"
                                    label="Отчество"
                                    name="patronymic"
                                    v-validate="adminCorporate.rules.patronymic"
                                    outlined
                                    dense
                                    data-vv-as="отчество"
                                />
                                <v-text-field
                                    v-mask="phoneMask"
                                    :error-messages="errors.collect('adminCorporate.phone')"
                                    v-model="adminCorporate.phone"
                                    color="yellow darken-3"
                                    label="Телефон"
                                    name="phone"
                                    v-validate="adminCorporate.rules.phone"
                                    outlined
                                    dense
                                    data-vv-as="телефон"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    :loading="emailLoading"
                                    type="email"
                                    :error-messages="errors.collect('adminCorporate.email')"
                                    v-model="adminCorporate.email"
                                    color="yellow darken-3"
                                    label="Эл. адрес"
                                    name="email"
                                    v-validate="adminCorporate.rules.email"
                                    outlined
                                    dense
                                    data-vv-as="эл. адрес"
                                />
                                <div class="d-flex">
                                    <v-text-field
                                        class="mr-2"
                                        ref="password"
                                        label="Парлоь"
                                        v-model="adminCorporate.password"
                                        name="password"
                                        v-validate="adminCorporate.rules.password"
                                        :error-messages="errors.collect('adminCorporate.password')"
                                        outlined
                                        dense
                                        data-vv-as="пароль"
                                    >
                                    </v-text-field>
                                    <v-tooltip right>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn v-on="on" @click="generatePassword()" color="primary" fab small>
                                                <v-icon>mdi-hammer-screwdriver</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Сгенерерировать пароль</span>
                                    </v-tooltip>
                                </div>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn
                        color="error"
                        outlined
                        small
                        @click="cancelAdmin()"
                        v-text="company.admin_added ? 'удалить администратора' : 'отмена'"
                    />
                    <v-btn color="primary" small @click="acceptAdmin()" v-text="'принять'" />
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!--Image dialog-->
        <v-dialog v-model="imgDialog" max-width="600" width="100%">
            <v-card v-if="dialogImgSrc">
                <v-btn absolute dark fab right small color="error" @click="imgDialog = false" class="mt-3">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-img :src="dialogImgSrc"></v-img>
            </v-card>
        </v-dialog>
    </div>
</template>

<script lang="js" src="./CompanyForm.main.js" />
