<!-- @format -->

<template>
    <v-card v-if="candidate" :loading="loading" class="border" elevation="4">
        <v-card-title class="grey lighten-5">
            {{ candidate.driver ? "Восстановить водителя" : "Создать нового водителя" }}
            <v-spacer />
            <v-btn color="grey darken-3" icon outlined small @click="$emit('close')">
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider />
        <v-card-text class="pt-5">
            <template v-if="!candidate.driver">
                <v-subheader>Данные входа</v-subheader>
                <v-divider class="mb-3 mt-0" />
                <v-row class="mt-3">
                    <v-col class="py-0" cols="12" md="6">
                        <v-text-field
                            v-model="driver.phone"
                            v-mask="phoneMask"
                            v-validate="driver.rules.phone"
                            :error-messages="errors.collect('phone')"
                            append-icon="mdi-phone"
                            color="yellow darken-3"
                            data-vv-as="телефон"
                            dense
                            item-value="driverCandidate.phone"
                            label="Телефон"
                            name="phone"
                            outlined
                        />
                    </v-col>
                    <v-col class="py-0" cols="12" md="6">
                        <v-text-field
                            v-model="driver.nickname"
                            v-validate="driver.rules.nickname"
                            :error-messages="errors.collect('nickname')"
                            color="yellow darken-3"
                            data-vv-as="ник"
                            dense
                            label="Ник"
                            name="nickname"
                            outlined
                        />
                    </v-col>
                    <v-col class="py-0" cols="12" md="6">
                        <div class="d-flex">
                            <v-text-field
                                v-model="driver.password"
                                v-validate="driver.rules.password"
                                :error-messages="errors.collect('password')"
                                class="mr-2"
                                color="yellow darken-3"
                                data-vv-as="пароль"
                                dense
                                label="Пароль"
                                name="password"
                                outlined
                            >
                                <template v-slot:append style="margin-top: 0">
                                    <v-tooltip right>
                                        <template v-slot:activator="{ on, attrs }" style="margin-top: 0">
                                            <v-btn
                                                style="margin-top: -6px; right: -10.6px"
                                                v-on="on"
                                                depressed
                                                color="yellow darken-2"
                                                @click="generatePassword()"
                                            >
                                                <v-icon v-text="'mdi-hammer-screwdriver'" />
                                            </v-btn>
                                        </template>
                                        <span>Сгенерерировать пароль</span>
                                    </v-tooltip>
                                </template>
                            </v-text-field>
                        </div>
                    </v-col>
                </v-row>
            </template>
            <v-subheader>Опции работы</v-subheader>
            <v-divider class="mb-4 mt-0" />
            <v-row class="mt-4">
                <v-col class="py-0" md="6" sm="6" xs="6">
                    <v-select
                        v-model="driver.graphic_id"
                        v-validate="driver.rules.graphic_id"
                        :error-messages="errors.collect('graphic_id')"
                        :items="graphics"
                        color="yellow darken-3"
                        data-vv-as="график"
                        dense
                        item-text="name"
                        item-value="driver_graphic_id"
                        label="График"
                        name="graphic_id"
                        outlined
                        prepend-inner-icon="mdi-calendar-clock"
                    />
                </v-col>
                <v-col class="py-0" md="6" sm="6" xs="6">
                    <v-select
                        v-model="driver.type_id"
                        v-validate="driver.rules.type_id"
                        :error-messages="errors.collect('type_id')"
                        :items="types"
                        color="yellow darken-3"
                        data-vv-as="тип работы"
                        dense
                        item-text="type"
                        item-value="driver_type_id"
                        label="Тип работы"
                        name="type_id"
                        outlined
                        prepend-inner-icon="mdi-steering"
                    />
                </v-col>
                <v-col class="py-0" md="6" sm="12" xs="12">
                    <v-text-field
                        v-model="driver.free_days_price"
                        v-validate="driver.rules.free_days_price"
                        :disabled="freeDaysDisabled"
                        :error-messages="errors.collect('free_days_price')"
                        color="yellow darken-3"
                        data-vv-as="цена свободных дней"
                        dense
                        label="Цена свободных дней"
                        name="free_days_price"
                        outlined
                        prepend-inner-icon="mdi-currency-usd"
                        type="number"
                    />
                </v-col>
                <v-col class="py-0" md="6" sm="12" xs="12">
                    <v-text-field
                        v-model="driver.busy_days_price"
                        v-validate="driver.rules.busy_days_price"
                        :error-messages="errors.collect('busy_days_price')"
                        color="yellow darken-3"
                        data-vv-as="цена свободных дней"
                        dense
                        label="Цена занятых дней"
                        name="busy_days_price"
                        outlined
                        prepend-inner-icon="mdi-currency-usd"
                        type="number"
                    />
                </v-col>
                <v-col class="py-0" md="6" sm="12" xs="12">
                    <v-select
                        v-model="driver.subtype_id"
                        v-validate="driver.rules.subtype_id"
                        :disabled="!getSubtypes"
                        :error-messages="errors.collect('subtype_id')"
                        :items="getSubtypes"
                        color="yellow darken-3"
                        data-vv-as="тип контракта"
                        dense
                        item-text="name"
                        item-value="driver_subtype_id"
                        label="Тип контракта"
                        name="subtype_id"
                        outlined
                        prepend-inner-icon="mdi-file-document-outline"
                    />
                </v-col>

                <v-col v-if="showEntities" class="py-0" md="8" sm="12" xs="12">
                    <div class="d-flex">
                        <v-autocomplete
                            v-model="driver.entity_id"
                            v-validate="showEntities ? driver.rules.entity_id : null"
                            :error-messages="errors.collect('entity_id')"
                            :items="entities"
                            clearable
                            color="yellow darken-3"
                            data-vv-as="юридическое лицо"
                            dense
                            item-color="yellow darken-3"
                            item-text="name"
                            item-value="legal_entity_id"
                            label="Юридическое лицо"
                            name="entity_id"
                            open-on-clear
                            outlined
                        />
                        <v-btn
                            :href="
                                $router.resolve({
                                    name: 'legal_entity_create',
                                }).href
                            "
                            class="mx-2"
                            color="primary"
                            fab
                            small
                            target="_blank"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                    </div>
                </v-col>

                <v-col class="py-0" lg="4" md="4" sm="4" xl="3" xs="4">
                    <v-text-field
                        v-model="driver.days_count"
                        v-validate="driver.rules.days_count"
                        :error-messages="errors.collect('days_count')"
                        color="yellow darken-3"
                        data-vv-as="количество дней"
                        dense
                        name="days_count"
                        outlined
                        placeholder="Количество дней"
                        type="number"
                    />
                </v-col>
                <v-col class="py-0" md="6" sm="12" xs="12">
                    <date-picker
                        v-model="driver.work_start_day"
                        v-validate="driver.rules.work_start_day"
                        :error-messages="errors.collect('work_start_day')"
                        data-vv-as="день начала работы"
                        label="Первый день работы"
                        name="work_start_day"
                        prepend-inner-icon="mdi-calendar"
                        @blur="date = parseDate(driver.work_start_day)"
                    />
                </v-col>
                <v-col class="py-0" md="6" sm="12" xs="12">
                    <date-picker
                        v-model="driver.expiration_day"
                        v-validate="driver.rules.expiration_day"
                        :error-messages="errors.collect('expiration_day')"
                        data-vv-as="дата окончания контракта"
                        label="Дата окончания контракта"
                        name="expiration_day"
                        prepend-inner-icon="mdi-calendar"
                        @blur="date = parseDate(driver.expiration_day)"
                    />
                </v-col>
            </v-row>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <v-btn :loading="loading" depressed class="rounded-2" color="primary" small @click="save()">
                Создать водителя
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script src="./DriverDialog.main.js" />
