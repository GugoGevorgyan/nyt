<!-- @format -->

<template>
    <v-container fluid>
        <v-card outlined tile>
            <v-container fluid class="mb-6" ref="header">
                <v-row no-gutters>
                    <v-col cols="12" md="2" class="d-flex align-end">
                        <form @submit.prevent="search()">
                            <v-text-field
                                v-model="searchText"
                                label="Поиск по водителю"
                                color="yellow darken-3"
                                item-color="yellow darken-3"
                                single-line
                                hide-details
                                clearable
                            />
                        </form>
                        <v-btn :disabled="!searchText" small fab @click="search()" color="yellow darken-3">
                            <v-icon color="white" v-text="'mdi-magnify'" />
                        </v-btn>
                        <v-divider class="mx-4" inset vertical />
                    </v-col>
                    <v-col cols="12" md="2" class="d-flex">
                        <v-select
                            menu-props="auto"
                            hide-details
                            v-model="paginated.month"
                            :items="months"
                            item-text="text"
                            item-value="value"
                            label="Месяц"
                            color="yellow darken-3"
                            item-color="yellow darken-3"
                            single-line
                        />
                        <v-divider class="mx-4" inset vertical />
                    </v-col>
                    <v-col cols="12" md="2" class="d-flex">
                        <v-select
                            v-model="paginated.year"
                            :items="years"
                            label="Год"
                            color="yellow darken-3"
                            item-color="yellow darken-3"
                            menu-props="auto"
                            single-line
                            hide-details
                        />
                        <v-divider class="mx-4" inset vertical />
                    </v-col>
                    <v-col cols="12" md="2" class="d-flex">
                        <v-select
                            v-model="paginated.park"
                            :items="parks"
                            item-text="name"
                            item-value="park_id"
                            label="Парк"
                            color="yellow darken-3"
                            item-color="yellow darken-3"
                            menu-props="auto"
                            clearable
                            single-line
                            hide-details
                        />
                        <v-divider class="mx-4" inset vertical />
                    </v-col>
                    <v-col cols="12" md="2" class="d-flex">
                        <v-select
                            v-model="paginated.driver_type"
                            :items="driverTypes"
                            item-text="type"
                            item-value="driver_type_id"
                            label="Тип водителя"
                            color="yellow darken-3"
                            item-color="yellow darken-3"
                            menu-props="auto"
                            clearable
                            single-line
                            hide-details
                        />
                        <v-divider class="mx-4" inset vertical />
                    </v-col>
                    <v-col cols="12" md="2" class="d-flex">
                        <v-select
                            v-model="paginated.schedule_type"
                            :items="scheduleTypes"
                            item-text="name"
                            item-value="driver_graphic_id"
                            label="График водителя"
                            color="yellow darken-3"
                            item-color="yellow darken-3"
                            menu-props="auto"
                            clearable
                            single-line
                            hide-details
                        />
                    </v-col>
                </v-row>
            </v-container>

            <div class="d-flex align-center justify-center mb-6">
                <div class="elevation-10">
                    <div ref="info" class="calendar-info-box">
                        <div class="title-box">
                            <span class="subtitle-1">Водители</span>
                        </div>
                        <div>
                            <div class="year-month-box">
                                <span class="display-1" v-text="month.text + ' ' + paginated.year" />
                            </div>
                            <div class="d-flex">
                                <div
                                    v-for="day in days"
                                    class="week-day-box"
                                    :style="{
                                        'background-color': getWeekDay(day).background,
                                        color: getWeekDay(day).color,
                                    }"
                                    :class="[checkToday(day), hoverDay === day ? 'hoverDay' : null]"
                                >
                                    <div>
                                        <span style="font-size: 70%">{{ day }}</span>
                                        <span style="font-size: 50%">{{ getWeekDay(day).shorted }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="position: relative">
                        <v-overlay :opacity="0.2" absolute :value="paginated.loading">
                            <v-progress-circular indeterminate :width="2" :size="50" color="yellow darken-3" />
                        </v-overlay>
                        <div class="calendar-box" :style="{ height: window.height + 'px' }">
                            <template v-if="paginated.data.length">
                                <div class="day-row" v-for="driver in paginated.data">
                                    <div class="title-box justify-space-between">
                                        <small style="color: grey">
                                            {{ driver.driver_info.name }} {{ driver.driver_info.patronymic }}
                                            {{ driver.driver_info.surname }}
                                        </small>
                                        <v-menu
                                            transition="slide-x-transition"
                                            bottom
                                            right
                                            offset-x
                                            :close-on-content-click="false"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn small v-bind="attrs" icon color="primary" v-on="on">
                                                    <v-icon small>mdi-information-outline</v-icon>
                                                </v-btn>
                                            </template>
                                            <div class="d-flex pa-4" style="background-color: white">
                                                <v-img width="100" height="100" :src="driver.driver_info.photo"></v-img>
                                                <v-list class="pa-0">
                                                    <v-list-item style="min-height: 0">
                                                        <v-list-item-content class="pa-0 mb-4">
                                                            <div class="d-flex justify-space-between align-center">
                                                                <small class="font-weight-bold mr-2">Фио:</small>
                                                                <small>
                                                                    {{ driver.driver_info.patronymic }}
                                                                    {{ driver.driver_info.name }}
                                                                    {{ driver.driver_info.surname }}
                                                                </small>
                                                            </div>
                                                            <v-divider />
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                    <v-list-item style="min-height: 0">
                                                        <v-list-item-content class="pa-0 mb-4">
                                                            <div class="d-flex justify-space-between align-center">
                                                                <small class="font-weight-bold mr-2">Телефон:</small>
                                                                <small>{{ driver.phone }}</small>
                                                            </div>
                                                            <v-divider />
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                    <v-list-item style="min-height: 0">
                                                        <v-list-item-content class="pa-0 mb-4">
                                                            <div class="d-flex justify-space-between align-center">
                                                                <small class="font-weight-bold mr-2"
                                                                    >Тип водителя:</small
                                                                >
                                                                <small>{{
                                                                    driver.active_contract.subtype.name.toLowerCase()
                                                                }}</small>
                                                            </div>
                                                            <v-divider />
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                    <v-list-item style="min-height: 0">
                                                        <v-list-item-content class="pa-0 mb-4">
                                                            <div class="d-flex justify-space-between align-center">
                                                                <small class="font-weight-bold mr-2"
                                                                    >Тип контракта:</small
                                                                >
                                                                <small>{{ driver.active_contract.type.type }}</small>
                                                            </div>
                                                            <v-divider />
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                    <v-list-item style="min-height: 0">
                                                        <v-list-item-content class="pa-0 mb-4">
                                                            <div class="d-flex justify-space-between align-center">
                                                                <small class="font-weight-bold mr-2">График:</small>
                                                                <small>{{ driver.active_contract.graphic.name }}</small>
                                                            </div>
                                                            <v-divider />
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                    <v-list-item two-line>
                                                        <v-list-item-content>
                                                            <v-list-item-subtitle>Контракт:</v-list-item-subtitle>
                                                            <v-list-item-title>
                                                                с {{ driver.active_contract.signing_day }} по
                                                                {{ driver.active_contract.expiration_day }}
                                                            </v-list-item-title>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                </v-list>
                                            </div>
                                        </v-menu>
                                    </div>
                                    <div
                                        @mouseover="dayMouseOver(day)"
                                        @mouseleave="dayMouseLeave(day)"
                                        class="day-box"
                                        v-for="day in days"
                                        :class="[
                                            checkDay(driver, day),
                                            checkToday(day),
                                            hoverDay === day ? 'hoverDay' : null,
                                        ]"
                                    >
                                        <v-menu
                                            transition="slide-x-transition"
                                            bottom
                                            right
                                            offset-x
                                            :close-on-content-click="false"
                                            v-model="dayMenus[driver.driver_id][day]"
                                        >
                                            <template v-slot:activator="{ on, value }">
                                                <div
                                                    :class="value ? 'active-day-box' : null"
                                                    style="height: 100%; width: 100%"
                                                    v-on="on"
                                                ></div>
                                            </template>
                                            <v-list class="position-relative">
                                                <v-btn
                                                    class="close-menu-btn"
                                                    @click="closeDayMenu(driver.driver_id, day)"
                                                    icon
                                                    x-small
                                                    color="error"
                                                >
                                                    <v-icon x-small>mdi-close</v-icon>
                                                </v-btn>
                                                <v-list-item>
                                                    <v-list-item-content>
                                                        <v-list-item-subtitle>Водитель:</v-list-item-subtitle>
                                                        <v-list-item-title>
                                                            {{ driver.driver_info.patronymic }}
                                                            {{ driver.driver_info.name }}
                                                            {{ driver.driver_info.surname }}
                                                        </v-list-item-title>
                                                    </v-list-item-content>
                                                </v-list-item>
                                                <v-list-item>
                                                    <v-list-item-content>
                                                        <v-list-item-subtitle>Дата:</v-list-item-subtitle>
                                                        <v-list-item-title>
                                                            {{ day }} {{ month.text }} {{ paginated.year }}
                                                            <small>{{ getWeekDay(day).text }}</small>
                                                        </v-list-item-title>
                                                    </v-list-item-content>
                                                </v-list-item>
                                                <v-list-item>
                                                    <v-list-item-content>
                                                        <v-list-item-subtitle>Статус:</v-list-item-subtitle>
                                                        <v-list-item-title>
                                                            <template v-if="getDaySchedule(driver, day)">
                                                                <small
                                                                    class="mr-2"
                                                                    :style="{
                                                                        color: getDaySchedule(driver, day).working
                                                                            ? '#00BFA5'
                                                                            : '#FF5252',
                                                                    }"
                                                                >
                                                                    {{
                                                                        getDaySchedule(driver, day).working
                                                                            ? "Рабочий день"
                                                                            : "Не рабочий день"
                                                                    }}
                                                                </small>
                                                                <v-btn
                                                                    :loading="dayLoading"
                                                                    @click="updateDay(driver.driver_id, day)"
                                                                    v-if="
                                                                        getDaySchedule(driver, day).working &&
                                                                        isPast(day)
                                                                    "
                                                                    outlined
                                                                    small
                                                                    color="#FF5252"
                                                                    >Сделать не рабочим</v-btn
                                                                >
                                                            </template>
                                                            <small v-else>Нет подписанного контракта</small>
                                                        </v-list-item-title>
                                                    </v-list-item-content>
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </div>
                                </div>
                            </template>
                            <div
                                v-else-if="!paginated.loading"
                                class="d-flex justify-center align-center"
                                style="height: 100%; width: 100%"
                            >
                                <span>нет данных</span>
                            </div>
                        </div>
                    </div>
                    <v-row ref="pagination" no-gutters class="py-1">
                        <v-col cols="12" md="2" class="d-flex justify-center align-center"></v-col>
                        <v-col cols="12" md="8" class="d-flex justify-center align-center">
                            <v-tooltip left>
                                <template v-slot:activator="{ on, attrs }">
                                    <div v-bind="attrs" v-on="on">
                                        <v-pagination
                                            :length="paginated.last_page"
                                            :total-visible="7"
                                            circle
                                            color="yellow darken-3"
                                            v-model="paginated.current_page"
                                        />
                                    </div>
                                </template>
                                <span>
                                    {{
                                        Number(paginated.total)
                                            ? `${paginated.from}-${paginated.to} из ${paginated.total}`
                                            : "Нет данных"
                                    }}
                                </span>
                            </v-tooltip>
                        </v-col>
                        <v-col cols="12" md="2" class="d-flex justify-center align-center">
                            <v-menu offset-y max-width="100">
                                <template v-slot:activator="{ on: menu, attrs }">
                                    <v-tooltip left>
                                        <template v-slot:activator="{ on: tooltip }">
                                            <v-btn
                                                fab
                                                small
                                                dark
                                                color="yellow darken-3"
                                                class="mb-1"
                                                v-bind="attrs"
                                                v-on="{ ...tooltip, ...menu }"
                                                v-text="paginated.per_page"
                                            />
                                        </template>
                                        <span>строк на странице</span>
                                    </v-tooltip>
                                </template>
                                <v-list>
                                    <v-list-item
                                        :disabled="paginated.per_page === item"
                                        color="yellow darken-3"
                                        v-for="(item, index) in paginated.perPages"
                                        :key="index"
                                        @click="paginated.per_page = item"
                                    >
                                        <v-list-item-title v-text="item" />
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </v-col>
                    </v-row>
                </div>
            </div>
        </v-card>
    </v-container>
</template>

<script lang="js" src="./index.main.js" />
<style scoped lang="scss" src="./index.style.scss" />
