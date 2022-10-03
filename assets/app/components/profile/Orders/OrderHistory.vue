<!-- @format -->
<template>
    <v-container>
        <v-data-table
            v-model="selected"
            :headers="headers"
            :items="orders._payload"
            :items-per-page.sync="filterSort.perpage"
            :server-items-length="orders.total"
            class="elevation-5 row_pointer"
            dense
            :single-select="false"
            hide-default-footer
            loader-height="2"
            calculate-width
            @click:row="openInfoDialog"
            :height="window.height"
            fixed-header
        >
            <template v-slot:top>
                <v-toolbar flat color="white" height="120">
                    <v-progress-linear
                        :active="loading"
                        :indeterminate="loading"
                        color="yellow darken-3"
                        height="2"
                        absolute
                        bottom
                    />
                    <order-info
                        v-if="orderInfo"
                        :data="orderData"
                        :dialog="orderInfo"
                        @dialog="orderInfo = false"
                    />
                    <v-layout>
                        <v-flex xs3>
                            <v-autocomplete
                                v-model="filterSort.type"
                                :items="orderTypes"
                                item-text="name"
                                item-value="order_type_id"
                                label="Фильтровать по типу заказа"
                                color="yellow darken-3"
                                item-color="yellow darken-3"
                                outlined
                                clearable
                                open-on-clear
                                hide-details
                            />
                        </v-flex>
                        <v-divider class="mx-4 mt-0" inset vertical></v-divider>
                        <v-flex xs3>
                            <el-date-picker
                                v-model="datePicker"
                                type="daterange"
                                align="right"
                                class="v-el-date-picker"
                                range
                                start-placeholder="Дата от"
                                end-placeholder="Дата до"
                                :picker-options="pickerOptions"
                                clear-icon="el-icon-close"
                                hide-details
                            />
                        </v-flex>
                    </v-layout>
                </v-toolbar>
            </template>
            <template v-slot:item.price='{item}'>
                <v-chip x-small color="success" outlined>
                    {{ "₽ " + item.price }}
                </v-chip>
            </template>
            <template v-slot:footer>
                <div ref="footer">
                    <v-divider class="ma-0" />
                    <v-row no-gutters class="py-1">
                        <v-col cols="12" md="2" class="d-flex justify-center align-center"></v-col>
                        <v-col cols="12" md="8" class="d-flex justify-center align-center">
                            <v-tooltip left>
                                <template v-slot:activator="{ on, attrs }">
                                    <div v-bind="attrs" v-on="on">
                                        <v-pagination
                                            :length="orders.last_page"
                                            :total-visible="7"
                                            circle
                                            color="yellow darken-3"
                                            v-model="current_page"
                                        />
                                    </div>
                                </template>
                                <span>
                                    {{
                                        Number(orders.total)
                                            ? `${orders.from ?orders.from: 1}-${orders.to ? orders.to : 1} из ${orders.total}`
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
                                            >
                                                {{ per_page }}
                                            </v-btn>
                                        </template>
                                        <span>строк на странице</span>
                                    </v-tooltip>
                                </template>
                                <v-list>
                                    <v-list-item
                                        :disabled="per_page === item"
                                        color="yellow darken-3"
                                        v-for="(item, index) in perPages"
                                        :key="index"
                                        @click="per_page = item"
                                    >
                                        <v-list-item-title>{{ item }}</v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </v-col>
                    </v-row>
                </div>
            </template>
        </v-data-table>
    </v-container>
</template>

<script lang="js" src="./OrderHistory.main.js" />
<style scoped>
.border {
    border: 1px solid black;
}
.row_pointer >>> tbody tr :hover{
    cursor: pointer;
}
</style>
