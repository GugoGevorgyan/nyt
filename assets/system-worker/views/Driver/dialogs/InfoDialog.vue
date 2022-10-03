<!-- @format -->

<template>
    <v-card height="100%" width="100%" class="border">
        <v-card-title class="grey lighten-5 pa-0 pr-1">
            <v-subheader v-text="'Информация '" />
            <v-divider vertical class="mr-3" />
            <span v-text="driver.driver_info.name + ' ' + driver.driver_info.surname" />
            <v-spacer />
            <v-btn icon @click="$emit('infoEmit', { close: false })">
                <v-icon color="grey darken-3" v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider />
        <v-card-text :style="{ height: window.height + 'px' }" style="width: 100%; overflow-y: auto">
            <v-window v-model="toggleInfo">
                <v-layout class="d-flex row mt-2 ma-0" style="height: 50px">
                    <v-spacer v-if="0 === toggleInfo" />
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn v-bind="attrs" v-on="on" class="float-right" icon @click="toggleInfoEvent">
                                <v-icon
                                    color="grey darken-2"
                                    v-text="0 === toggleInfo ? 'mdi-arrow-right' : 'mdi-arrow-left'"
                                />
                            </v-btn>
                        </template>
                        <small v-text="!toggleInfo ? 'Траектория по дням' : 'Общая информация'" />
                    </v-tooltip>

                    <div v-if="0 !== toggleInfo" style="width: 33%" class="float-right d-flex align-center">
                        <v-spacer />
                        <span class="font-weight-medium">{{ distance }} <b>KM</b></span>
                    </div>

                    <div v-if="0 !== toggleInfo" style="width: 60%">
                        <v-menu
                            ref="menu1"
                            v-model="menu1"
                            :close-on-content-click="true"
                            transition="scale-transition"
                            offset-y
                            width="290px"
                            min-width="200px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-spacer />
                                <v-text-field
                                    class="float-right"
                                    v-model="roadDate"
                                    dense
                                    hide-details
                                    hint="YYYY/MM/DD format"
                                    persistent-hint
                                    prepend-icon="mdi-calendar"
                                    v-bind="attrs"
                                    v-on="on"
                                    style="width: 180px"
                                />
                            </template>
                            <v-date-picker
                                :max="maxDate"
                                :min="minDate"
                                v-model="roadDate"
                                no-title
                                @input="menu1 = false"
                            />
                        </v-menu>
                    </div>
                </v-layout>
                <v-window-item :key="0">
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-subheader
                                class="font-weight-black grey lighten-4 justify-center orange--text"
                                v-text="'ФИО'"
                            />
                            <v-img v-if="driver.driver_info.photo" :src="driver.driver_info.photo" height="220" />
                            <v-img v-else :src="noImage" height="220" />
                            <v-text-field
                                label="Псевдоним"
                                v-model="driver.nickname"
                                name='nickname'
                                v-validate="rules.driver.nickname"
                                :error-messages="errors.collect('nickname')"
                                data-vv-as='Псевдоним'
                            />
                            <v-text-field
                                label="Новый пароль"
                                v-model="password"
                                name='password'
                                v-validate="rules.driver.password"
                                :error-messages="errors.collect('password')"
                                data-vv-as='пароль'
                            />
                            <v-text-field
                                label="Имя"
                                v-model="driver.driver_info.name"
                                name='name'
                                v-validate="rules.driver_info.name"
                                :error-messages="errors.collect('name')"
                                data-vv-as='Имя'
                            />
                            <v-text-field
                                label="Фамилия"
                                v-model="driver.driver_info.surname"
                                name='surname'
                                v-validate="rules.driver_info.surname"
                                :error-messages="errors.collect('surname')"
                                data-vv-as='Фамилия'
                            />
                            <v-text-field
                                label="Отчество"
                                v-model="driver.driver_info.patronymic"
                                name='patronymic'
                                v-validate="rules.driver_info.patronymic"
                                :error-messages="errors.collect('patronymic')"
                                data-vv-as='Отчество'
                            />
                            <v-text-field
                                label="Номер"
                                v-mask="phoneMask"
                                v-model="driver.phone"
                                name='phone'
                                v-validate="rules.driver.phone"
                                :error-messages="errors.collect('phone')"
                                data-vv-as='Номер'
                            />
                            <v-text-field
                                label="Эл. адрес"
                                v-model="driver.driver_info.email"
                                name='email'
                                v-validate="rules.driver_info.email"
                                :error-messages="errors.collect('email')"
                                data-vv-as='Эл. адрес'
                            />
                            <date-picker
                                prepend-inner-icon="mdi-calendar"
                                v-model="driver.driver_info.birthday"
                                v-validate="rules.driver_info.birthday"
                                label="Дата рождения"
                                name="birthday"
                                :max="date_18_years_ago"
                                :error-messages="errors.collect('birthday')"
                                data-vv-as="Дата рождения"
                                @blur="date = parseDate(driver.driver_info.birthday)"
                            />
                            <v-text-field
                                label="Аддресс"
                                v-model="driver.driver_info.address"
                                name='address'
                                v-validate="rules.driver_info.address"
                                :error-messages="errors.collect('address')"
                                data-vv-as='Аддресс'
                            />
                            <v-text-field
                                label="Гражданство"
                                v-model="driver.driver_info.citizen"
                                name='citizen'
                                v-validate="rules.driver_info.citizen"
                                :error-messages="errors.collect('citizen')"
                                data-vv-as='Гражданство'
                            />
                            <v-text-field
                                label="Райтинг"
                                v-model="driver.rating"
                                name='rating'
                                v-validate="rules.driver.rating"
                                :error-messages="errors.collect('rating')"
                                data-vv-as='Райтинг'
                            />
                            <v-text-field
                                label="Средняя оценка"
                                v-model="mean_assessment"
                                name='mean_assessment'
                                v-validate="rules.driver.mean_assessment"
                                :error-messages="errors.collect('mean_assessment')"
                                data-vv-as='Средняя оценка'
                            />
                            <v-btn outlined class="mt-6" color="grey darken-3" @click="editDriver(driver)">
                                Обновить
                            </v-btn>
                        </v-col>
                        <v-divider vertical inset />
                        <v-col cols="12" md="6">
                            <v-subheader
                                class="font-weight-black grey lighten-4 justify-center orange--text"
                                v-text="'Автомобиль (и)'"
                            />
                            <div
                                v-if="driver.car && driver.car.images"
                                style="position: relative; height: 220px; width: 220px"
                            >
                                <div
                                    @click="carImages = driver.car.images.map(item => item)"
                                    style="
                                        cursor: pointer;
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        height: 100%;
                                        width: 100%;
                                        background-color: rgba(0, 0, 0, 0.5);
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        color: white;
                                        z-index: 9;
                                    "
                                >
                                    <span>Images: {{ driver.car.images.length }}</span>
                                </div>
                                <v-img cover width="220" height="220" :src="driver.car.images[0]" />
                            </div>

                            <div v-if="driver.car">
                                <v-text-field readonly label="Марка" :value="driver.car.mark" />
                                <v-text-field readonly label="Модель" :value="driver.car.model" />
                                <v-text-field readonly label="Модель" :value="driver.car.color" />
                                <v-text-field readonly label="Дата тех. осмотра" :value="driver.car.inspection_date" />
                                <v-text-field
                                    readonly
                                    label="Дата истечения тех. осмотра"
                                    :value="driver.car.inspection_expiration_date"
                                />
                                <v-text-field readonly label="Дата страхования" :value="driver.car.insurance_date" />
                                <v-text-field
                                    readonly
                                    label="Дата истечения страхования"
                                    :value="driver.car.insurance_expiration_date"
                                />
                                <v-text-field readonly label="Спидометр" :value="driver.car.speedometer" />
                                <v-text-field readonly label="Вин код" :value="driver.car.vin_code" />
                            </div>
                        </v-col>
                    </v-row>
                </v-window-item>
                <v-window-item :key="1" :style="{ height: window.height - 85 + 'px' }" style="width: 100%" eager>
                    <v-progress-linear
                        v-if="loadCord"
                        :active="loadCord"
                        :indeterminate="loadCord"
                        absolute
                        color="yellow darken-3"
                        height="2"
                        top
                    />
                    <v-divider />
                    <div style="width: 101.5%; height: 100%" id="driver-info-map"></div>
                </v-window-item>
            </v-window>
        </v-card-text>
        <images-dialog
            :persistent="false"
            v-if="carImages.length"
            :files="carImages"
            @close="carImages = []"
            max-width="900"
            max-height="500"
        />
    </v-card>
</template>

<script lang="js" src="./InfoDialog.main.js" />
<style scoped></style>
