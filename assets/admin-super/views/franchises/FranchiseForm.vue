<!-- @format -->

<template>
    <div>
        <v-card :loading="loading" outlined elevation="6" tile>
            <div class="pa-2 grey lighten-5">
                <span class="display-1 font-weight-light">{{
                    franchise.franchise_id ? "Обновление франшизы" : "Новая франшиза"
                }}</span>
            </div>
            <v-divider />
            <v-card-text :style="{ height: height + 'px' }" style="overflow: auto">
                <v-form :data-vv-scope="franchise.scope" autocomplete="off">
                    <input name="_token" :value="$csrf" type="hidden" />
                    <v-layout row wrap>
                        <!--information-->
                        <v-flex xs6 class="pr-4">
                            <div class="mb-4 text-center">
                                <span class="title black--text font-weight-light">Основная информация</span>
                            </div>
                            <v-layout row wrap class="mb-4">
                                <!--main information-->
                                <v-flex xs7>
                                    <!-- franchise name -->
                                    <v-text-field
                                        :loading="franchise.nameLoading"
                                        :error-messages="errors.collect(`${franchise.scope}.name`)"
                                        clearable
                                        color="yellow darken-3"
                                        label="Название компании"
                                        name="name"
                                        dense
                                        outlined
                                        prepend-icon="mdi-account-arrow-right-outline"
                                        v-model="franchise.name"
                                        v-validate="franchise.rules.name"
                                        data-vv-as="название"
                                    />

                                    <!-- franchise email -->
                                    <v-text-field
                                        :error-messages="errors.collect(`${franchise.scope}.email`)"
                                        color="yellow darken-3"
                                        label="Эл. адрес"
                                        name="email"
                                        outlined
                                        dense
                                        prepend-icon="mdi-email-outline"
                                        v-model="franchise.email"
                                        v-validate="franchise.rules.email"
                                        data-vv-as="эл. адрес"
                                    />

                                    <!-- franchise address -->
                                    <v-text-field
                                        id="franchiseAddress"
                                        :error-messages="errors.collect(`${franchise.scope}.address`)"
                                        color="yellow darken-3"
                                        label="Адрес головной офиса"
                                        name="address"
                                        outlined
                                        dense
                                        prepend-icon="mdi-map-marker"
                                        v-model="franchise.address"
                                        v-validate="franchise.rules.address"
                                        data-vv-as="адрес"
                                    />

                                    <!-- franchise zip_code -->
                                    <v-text-field
                                        :error-messages="errors.collect(`${franchise.scope}.zip_code`)"
                                        color="yellow darken-3"
                                        label="Почтовый индекс"
                                        name="zip_code"
                                        outlined
                                        dense
                                        prepend-icon="mdi-numeric"
                                        v-model="franchise.zip_code"
                                        v-validate="franchise.rules.zip_code"
                                        data-vv-as="почтовый индекс"
                                    />

                                    <!-- franchise text -->
                                    <v-textarea
                                        :error-messages="errors.collect(`${franchise.scope}.text`)"
                                        color="yellow darken-3"
                                        label="Примечания"
                                        name="text"
                                        outlined
                                        dense
                                        prepend-icon="mdi-text-long"
                                        v-model="franchise.text"
                                        v-validate="franchise.rules.text"
                                        data-vv-as="текст"
                                    />

                                    <!--legal entity-->
                                    <div class="d-flex">
                                        <v-autocomplete
                                            clearable
                                            color="yellow darken-3"
                                            data-vv-as="юридическое лицо"
                                            outlined
                                            dense
                                            prepend-icon="mdi-gavel"
                                            :error-messages="errors.collect(`${franchise.scope}.entity_id`)"
                                            label="Юридическое лицо"
                                            name="entity_id"
                                            v-model="franchise.entity_id"
                                            v-validate="franchise.rules.entity_id"
                                            :items="entitiesAll"
                                            item-text="name"
                                            item-value="legal_entity_id"
                                        />
                                        <v-btn class="ml-4" color="primary" fab small @click="entityDialog = true">
                                            <v-icon v-text="'mdi-plus'" />
                                        </v-btn>
                                    </div>
                                </v-flex>

                                <!-- logo -->
                                <v-flex xs5 class="pl-6">
                                    <div style="width: 220px" class="ml-2">
                                        <div
                                            style="overflow: hidden; height: 200px; width: 220px"
                                            class="d-flex justify-center align-center elevation-1 mb-2"
                                        >
                                            <v-img
                                                style="cursor: pointer"
                                                class="elevation-0"
                                                :src="franchise.logo ? franchise.logo : lazyImage"
                                                @click="$refs.photoInput.click()"
                                                width="220"
                                                height="200"
                                            />
                                        </div>
                                        <small
                                            class="red--text"
                                            v-if="errors.first('file')"
                                            v-text="errors.first('file')"
                                        />
                                    </div>
                                    <input
                                        ref="photoInput"
                                        class="d-none"
                                        type="file"
                                        accept="image/*"
                                        @change="previewImage($event, 'logo')"
                                        name="file"
                                        v-validate="franchise.rules.file"
                                        data-vv-as="фотография"
                                    />
                                </v-flex>
                            </v-layout>

                            <div class="mb-4 d-flex align-center justify-center">
                                <span class="title black--text font-weight-light">Администраторы</span>
                                <v-btn color="primary" fab @click="addAdmin()" x-small class="ml-4">
                                    <v-icon v-text="'mdi-plus'" small />
                                </v-btn>
                            </div>

                            <div class="pa-1" style="height: 221px; border-radius: 4px; border: 1px dashed gray">
                                <div class="pa-1" style="height: 100%; overflow-y: auto">
                                    <!-- admins -->
                                    <v-list class="pa-0" dense v-if="admins.length">
                                        <v-list-item class="elevation-3 mb-1" v-for="(item, i) in admins" :key="i">
                                            <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-space-between align-center">
                                                    <span
                                                        >{{ item.name }} {{ item.patronymic }} {{ item.surname }}</span
                                                    >
                                                    <div>
                                                        <v-btn small icon color="primary" @click="updateAdmin(item, i)">
                                                            <v-icon small>mdi-pencil</v-icon>
                                                        </v-btn>
                                                        <v-btn
                                                            small
                                                            icon
                                                            color="error"
                                                            @click="
                                                                item.system_worker_id
                                                                    ? adminDeleteConfirmation(item)
                                                                    : removeAdmin(i)
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
                                        <v-alert type="error" outlined dense>
                                            <small>Необходимо добавить админстратора</small>
                                        </v-alert>
                                    </div>
                                </div>
                            </div>
                        </v-flex>

                        <!--region-->
                        <v-flex xs3 class="px-4">
                            <div class="mb-4 text-center">
                                <span class="title black--text font-weight-light">Зона деятельности</span>
                            </div>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-alert
                                        v-model="regionAlert"
                                        close-icon="mdi-close"
                                        dismissible
                                        dense
                                        type="error"
                                        v-if="franchise.franchise_id"
                                    >
                                        <small>
                                            При удалении города или региона из списка, так же будут удалены парки
                                            зарегистрированные в данном городе или регионе
                                        </small>
                                    </v-alert>

                                    <!-- franchise country -->
                                    <v-autocomplete
                                        color="yellow darken-3"
                                        outlined
                                        :error-messages="errors.collect(`${franchise.scope}.country_id`)"
                                        :items="countries"
                                        label="Страна деятельности"
                                        name="country_id"
                                        dense
                                        v-model="franchise.country_id"
                                        v-validate="franchise.rules.country_id"
                                        data-vv-as="страна"
                                        item-text="name"
                                        item-value="country_id"
                                        prepend-icon="mdi-earth"
                                    >
                                    </v-autocomplete>

                                    <!-- franchise regions -->
                                    <v-autocomplete
                                        :error-messages="errors.collect(`${franchise.scope}.region_ids`)"
                                        :items="regions"
                                        :loading="regionsLoading"
                                        :disabled="!regions.length"
                                        chips
                                        clearable
                                        color="yellow darken-3"
                                        data-vv-as="регионы"
                                        deletable-chips
                                        item-text="name"
                                        dense
                                        item-value="region_id"
                                        label="Регионы деятельности"
                                        multiple
                                        name="region_ids"
                                        outlined
                                        prepend-icon="mdi-map-legend"
                                        small-chips
                                        v-model="franchise.region_ids"
                                        v-validate="franchise.rules.region_ids"
                                    >
                                        <template v-slot:selection="{ item, index }">
                                            <v-chip small v-if="1 > index">
                                                <span>{{ item.name }}</span>
                                            </v-chip>
                                            <span v-if="1 === index" class="grey--text caption"
                                                >(+{{ franchise.region_ids.length - 1 }} других)
                                            </span>
                                            <v-icon color="grey" v-if="1 === index" v-text="'mdi-magnify'" />
                                        </template>
                                    </v-autocomplete>

                                    <!-- franchise phone -->
                                    <v-text-field
                                        color="yellow darken-3"
                                        label="Телефон компании"
                                        name="phone"
                                        outlined
                                        dense
                                        v-mask="mask"
                                        :disabled='!visiblePhone'
                                        prepend-icon="mdi-phone-outline"
                                        v-model="franchise.phone"
                                        v-validate="franchise.rules.phone"
                                        data-vv-as="телефон"
                                    />
                                    <div class="mb-4">
                                        <span class="title black--text mr-2">Города</span>
                                    </div>
                                    <div
                                        class="pa-1"
                                        :style="{ height: regionAlert ? '380px' : '505px' }"
                                        style="border-radius: 4px; border: 1px dashed gray"
                                    >
                                        <div class="pa-1" style="height: 100%; overflow-y: auto">
                                            <!-- franchise cities -->
                                            <template v-if="franchise.region_ids.length">
                                                <regions-cities
                                                    v-if="getSelectedRegion(region_id)"
                                                    v-for="region_id in franchise.region_ids"
                                                    :key="region_id"
                                                    :region="getSelectedRegion(region_id)"
                                                    :region_city_ids="franchise.regions_cities[region_id] || []"
                                                    :update-mode="preventRegions.includes(region_id)"
                                                    @updateCities="updateRegionCities"
                                                />
                                            </template>

                                            <div
                                                v-else
                                                class="d-flex justify-center align-center"
                                                style="height: 100%; width: 100%"
                                            >
                                                <span class="title">Выберите страну и регионы</span>
                                            </div>
                                        </div>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-flex>

                        <!--modules-->
                        <v-flex xs3 class="pl-4">
                            <div class="mb-4 text-center">
                                <span class="title black--text font-weight-light">Модули</span>
                            </div>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-alert
                                        v-model="moduleAlert"
                                        dense
                                        type="error"
                                        v-if="franchise.franchise_id"
                                        dismissible
                                        close-icon="mdi-close"
                                    >
                                        <small>
                                            При удалении модуля или роли из списка, работники франшизы потеряют
                                            восзможности предусмотренные данными модулями и ролями
                                        </small>
                                    </v-alert>

                                    <div style="height: 120px; margin-bottom: 20px">
                                        <div class="d-flex justify-center mb-2">
                                            <span class="subtitle-1">Телефонны кол-центра</span>
                                            <v-btn
                                                :disabled="!callCenterPhones"
                                                color="primary"
                                                fab
                                                @click="phonesDialog = true"
                                                x-small
                                                class="ml-4"
                                            >
                                                <v-icon small>mdi-pencil</v-icon>
                                            </v-btn>
                                        </div>
                                        <div v-if="callCenterPhones">
                                            <div v-if="franchise.call_center_phones.length" class="mb-1 d-flex">
                                                <strong class="mr-2" style="font-size: 14px">Номера:</strong>
                                                <div>
                                                    <div
                                                        v-for="(item, index) in franchise.call_center_phones"
                                                        :key="index"
                                                    >
                                                        <small class="blue--text">
                                                            {{ item.number }} {{ item.sub_phones.length ? ": " : null }}
                                                        </small>
                                                        <small
                                                            v-if="item.sub_phones.length"
                                                            v-for="subPhone in item.sub_phones"
                                                            class="blue--text"
                                                        >
                                                            {{ subPhone.number }}
                                                        </small>
                                                        <small v-else class="red--text"
                                                            >нет дополнитльных номеров</small
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <v-alert v-else type="error" outlined dense>
                                                <small>Необходимо указать номера колл-центра</small>
                                            </v-alert>
                                        </div>
                                        <v-alert v-else outlined dense type="info">
                                            <small>Колл-центер не включен в список доступных модулей</small>
                                        </v-alert>
                                    </div>

                                    <!-- modules -->
                                    <v-autocomplete
                                        :items="modules"
                                        chips
                                        clearable
                                        color="yellow darken-3"
                                        data-vv-as="модули"
                                        deletable-chips
                                        item-text="text"
                                        key="module_id"
                                        item-value="module_id"
                                        label="Дополнительные модули"
                                        multiple
                                        name="module_ids"
                                        outlined
                                        dense
                                        prepend-icon="mdi-package-variant"
                                        small-chips
                                        v-model="franchise.module_ids"
                                        v-validate="franchise.rules.module_ids"
                                        :error-messages="errors.collect(`${franchise.scope}.module_ids`)"
                                    >
                                        <template v-slot:prepend-item>
                                            <v-btn
                                                v-if="!franchise.module_ids.length"
                                                class="ml-3 mb-3"
                                                color="primary"
                                                outlined
                                                tile
                                                depressed
                                                small
                                                @click="selectAllModules"
                                                v-text="'выбрать все'"
                                            />
                                            <v-btn
                                                v-else
                                                class="ml-3 mb-3"
                                                color="secondary"
                                                outlined
                                                tile
                                                depressed
                                                small
                                                @click="deleteAllModules"
                                                v-text="'снять все'"
                                            />
                                        </template>

                                        <template v-slot:selection="{ item, index }">
                                            <v-chip small v-if="index < 1">
                                                <span>{{ item.text }}</span>
                                            </v-chip>
                                            <span v-if="index === 1" class="grey--text caption"
                                                >(+{{ franchise.module_ids.length - 1 }} других)
                                            </span>
                                            <v-icon color="grey" v-if="index === 1">mdi-magnify</v-icon>
                                        </template>
                                    </v-autocomplete>

                                    <div class="mb-4">
                                        <span class="title black--text mr-2">Роли</span>
                                    </div>
                                    <div
                                        class="pa-1"
                                        :style="{ height: moduleAlert ? '307px' : '431px' }"
                                        style="height: 515px; border-radius: 4px; border: 1px dashed gray"
                                    >
                                        <div class="pa-1" style="height: 100%; overflow-y: auto">
                                            <!-- roles -->
                                            <template v-if="franchise.module_ids.length">
                                                <module-roles
                                                    v-for="module_id in franchise.module_ids"
                                                    :key="module_id"
                                                    :module="getModule(module_id)"
                                                    :module_role_ids="franchise.module_roles[module_id] || []"
                                                    :update-mode="preventModules.includes(module_id)"
                                                    @updateRoles="updateModuleRoles"
                                                />
                                            </template>
                                            <div
                                                v-else
                                                class="d-flex justify-center align-center"
                                                style="height: 100%; width: 100%"
                                            >
                                                <span class="title">Выберите модули</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <span class="title black--text mr-2">Дополнительные опции</span>
                                    </div>
                                    <div
                                        class="pa-1"
                                        style="height: 200px; border-radius: 3px; border: 1px dashed gray"
                                    >
                                        <div class="pa-1" style="height: 100%; overflow-y: auto">
                                            <v-layout row wrap>
                                                <v-flex
                                                    v-for="(driverType, i) in driverTypes"
                                                    :key="driverType.driver_type_id"
                                                >
                                                    <v-chip @click="openDefaultRateDialog(i)">
                                                        {{ driverType.type }}
                                                    </v-chip>
                                                </v-flex>
                                            </v-layout>
                                            <v-text-field
                                                class="mt-3"
                                                outlined
                                                dense
                                                type="number"
                                                name="dispatching_minute"
                                                data-vv-as="время автораспределения"
                                                v-model="franchise.dispatching_minute"
                                                v-validate="franchise.rules.dispatching_minute"
                                                :error-messages="
                                                        errors.collect(`${franchise.scope}.dispatching_minute`)
                                                    "
                                                label="минимальное время автораспределения (по ум. 30 минут)"
                                            />
                                        </div>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-form>
            </v-card-text>
            <v-divider />
            <v-card-actions>
                <v-spacer />
                <v-btn
                    :disabled="callCenterPhoneErr || !admins.length || defaultRatesErr()"
                    :loading="loading"
                    width="120px"
                    depressed
                    @click="franchise.franchise_id ? update() : store()"
                    color="yellow darken-1"
                >
                    {{ franchise.franchise_id ? "обновить" : "создать" }}
                </v-btn>
            </v-card-actions>
        </v-card>

        <!--call center phones-->
        <v-dialog v-model="phonesDialog" width="800" persistent>
            <call-center-phones
                :franchise="franchise"
                :discharge="!callCenterPhones"
                @close="phonesDialog = false"
                @update="updateCallCenterPhones($event)"
                @delete="removeCallCenterPhone($event)"
                @deleteSub="removeCallCenterSubPhone($event)"
            />
        </v-dialog>

        <!--admin dialog-->
        <v-dialog v-model="adminDialog" width="800" persistent>
            <v-card :loading="adminDialogLoading">
                <v-card-title>
                    {{
                        ~updateAdminIndex
                            ? "Обновление данных админстратора: " +
                              admins[updateAdminIndex].name +
                              " " +
                              admins[updateAdminIndex].patronymic +
                              " " +
                              admins[updateAdminIndex].surname
                            : "Новый администратор"
                    }}
                </v-card-title>
                <v-card-text>
                    <v-form :data-vv-scope="admin.scope" autocomplete="off">
                        <v-row>
                            <v-col cols="12" md="6">
                                <!-- admin surname -->
                                <v-text-field
                                    dense
                                    :error-messages="errors.collect(`${admin.scope}.surname`)"
                                    label="Фамилия"
                                    name="surname"
                                    outlined
                                    prepend-inner-icon="mdi-account-circle-outline"
                                    v-model="admin.surname"
                                    v-validate="admin.rules.surname"
                                    data-vv-as="фамилия"
                                />

                                <!-- admin name -->
                                <v-text-field
                                    dense
                                    :error-messages="errors.collect(`${admin.scope}.name`)"
                                    label="Имя"
                                    name="name"
                                    outlined
                                    prepend-inner-icon="mdi-account-circle-outline"
                                    v-model="admin.name"
                                    v-validate="admin.rules.name"
                                    data-vv-as="имя"
                                />

                                <!-- admin patronymic -->
                                <v-text-field
                                    dense
                                    :error-messages="errors.collect(`${admin.scope}.patronymic`)"
                                    label="Отчество"
                                    name="patronymic"
                                    outlined
                                    prepend-inner-icon="mdi-account-circle-outline"
                                    v-model="admin.patronymic"
                                    v-validate="admin.rules.patronymic"
                                    data-vv-as="отчество"
                                />

                                <!-- admin nickname -->
                                <v-text-field
                                    :loading="admin.nicknameLoading"
                                    dense
                                    :error-messages="errors.collect(`${admin.scope}.nickname`)"
                                    label="Ник"
                                    name="nickname"
                                    outlined
                                    prepend-inner-icon="mdi-account-search-outline"
                                    v-model="admin.nickname"
                                    v-validate="admin.rules.nickname"
                                    data-vv-as="ник"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <!-- admin email -->
                                <v-text-field
                                    :loading="admin.emailLoading"
                                    dense
                                    :error-messages="errors.collect(`${admin.scope}.email`)"
                                    label="Эл. адрес"
                                    name="email"
                                    outlined
                                    prepend-inner-icon="mdi-email-outline"
                                    v-model="admin.email"
                                    v-validate="admin.rules.email"
                                    data-vv-as="эл. адрес"
                                />

                                <!-- admin phone -->
                                <v-text-field
                                    dense
                                    :error-messages="errors.collect(`${admin.scope}.phone`)"
                                    label="Телефон"
                                    name="phone"
                                    outlined
                                    prepend-inner-icon="mdi-phone-outline"
                                    v-mask="'+7(###)-###-##-##'"
                                    v-model="admin.phone"
                                    v-validate="admin.rules.phone"
                                    data-vv-as="телефон"
                                />

                                <!-- admin change password -->
                                <v-switch
                                    v-show="admin.system_worker_id"
                                    v-model="admin.change_password"
                                    label="Изменить пароль"
                                />

                                <!-- admin password -->
                                <v-text-field
                                    :disabled="admin.system_worker_id && !admin.change_password"
                                    dense
                                    :error-messages="errors.collect(`${admin.scope}.password`)"
                                    label="Пароль"
                                    name="password"
                                    outlined
                                    prepend-inner-icon="mdi-lock-outline"
                                    ref="password"
                                    v-model="admin.password"
                                    v-validate="
                                        !admin.system_worker_id || admin.change_password ? admin.rules.password : null
                                    "
                                    data-vv-as="пароль"
                                />
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn color="error" text small @click="closeAdminDialog()">отмена</v-btn>
                    <v-btn
                        color="primary"
                        small
                        :loading="adminDialogLoading"
                        @click="
                            franchise.franchise_id
                                ? admin.system_worker_id
                                    ? updateAdminRequest()
                                    : createAdminRequest()
                                : appendAdmin()
                        "
                    >
                        {{ franchise.franchise_id ? (admin.system_worker_id ? "обновить" : "создать") : "принять" }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!--admin delete dialog-->
        <v-dialog v-model="adminDeleteDialog" width="600" persistent>
            <v-card v-if="deleteAdmin" :loading="deleteAdminLoading">
                <v-card-title>Удаление админстратора</v-card-title>
                <v-card-text>
                    <p>
                        Вы уверены что хотите удалить администратора:
                        <strong>{{ deleteAdmin.name }} {{ deleteAdmin.patronymic }} {{ deleteAdmin.surname }}?</strong>
                    </p>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn color="error" text small @click="closeAdminDeleteDialog()">нет</v-btn>
                    <v-btn color="primary" :loading="deleteAdminLoading" small @click="removeAdminRequest()">да</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!--entity dialog-->
        <v-dialog v-model="entityDialog" width="1200" persistent>
            <entity-form
                :countries="countries"
                :entity-types="entityTypes"
                @close="entityDialog = false"
                @entityCreated="entityCreated($event)"
            />
        </v-dialog>

        <!--default rate dialog-->
        <v-dialog v-model="defaultRateDialog" v-if="defaultRateDialog" width="600" persistent>
            <default-rate-form
                :driver-types="driverTypes"
                :selected-driver-type="selectedDriverTypeForDefaultRate"
                :rules="franchise.rules"
                :rates="selected_default_rates()"
                @close="defaultRateDialog = false"
                @submit="defaultRateSubmitted"
            />
        </v-dialog>
    </div>
</template>

<script lang="js" src="./FranchiseForm.main.js" />
