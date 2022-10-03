<!-- @format -->

<template>
    <v-flex>
        <v-data-table
            v-model="paginated.selected"
            :headers="paginated.headers"
            :items="paginated.data"
            item-key="corporate_client_id"
            show-select
            dense
            :single-select="false"
            class="elevation-4"
            hide-default-footer
            calculate-width
            :height="window.height"
            :items-per-page="Number(paginated.per_page)"
        >
            <template v-slot:top>
                <v-toolbar flat color="grey lighten-5" height="50" tile>
                    <v-progress-linear
                        v-if="paginated.loading"
                        :active="paginated.loading"
                        height="2"
                        absolute
                        bottom
                        color="yellow darken-3"
                        class="mb-0"
                    />
                    <v-layout>
                        <v-flex col-6>
                            <v-text-field
                                outlined
                                dense
                                prepend-inner-icon="mdi-magnify"
                                color="yellow darken-3"
                                hide-details
                                label="Поиск по имени и телефону"
                                v-model="paginated.search"
                                background-color="white"
                            />
                        </v-flex>
                    </v-layout>
                    <v-spacer />
                    <v-btn
                        tile
                        text
                        color="yellow darken-3"
                        outlined
                        light
                        @click="showEmployeeDialog"
                        v-text="'Добавить сотрудника'"
                    />
                </v-toolbar>
            </template>

            <template v-slot:item.allow_order="{ item }">
                <v-icon v-if="item.allow_order" color="success">mdi-check</v-icon>
                <v-icon v-else color="error">mdi-close</v-icon>
            </template>

            <template v-slot:item.allow_weekends="{ item }">
                <v-icon v-if="item.allow_weekends" color="success">mdi-check</v-icon>
                <v-icon v-else color="error">mdi-close</v-icon>
            </template>

            <template v-slot:item.client.in_order="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon v-on="on" :color="item.in_order ? 'green' : 'tomato'">mdi-road</v-icon>
                    </template>
                    <span>{{ item.in_order ? "В заказе" : "Свободен" }}</span>
                </v-tooltip>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <span v-if='!item.in_order'>
                            <v-icon
                                v-if="item.allow_order"
                                color="blue darken-1"
                                v-on="on"
                                @click="showOrderDialog(item, 'orderDialog')"
                                v-text="'mdi-hail'"
                            />
                            <v-icon
                                v-else
                                color="red darken-1"
                                v-on="on"
                                @click="showOrderDialog(item, 'orderDialog')"
                                v-text="'mdi-hail'"
                            />
                        </span>

                        <v-menu left nudge-left="35px" :close-on-click="false" tile v-if="item.in_order">
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on" color="green darken-1" v-text="'mdi-reorder-vertical'" />
                            </template>
                            <v-list>
                                <v-list-item>
                                    <v-list-item-content>
                                        <small>Статус</small>
                                        <v-divider />
                                        <v-list-item-title/>
                                        <v-divider />
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </template>
                    <div v-if='item.allow_order'>
                        <span v-text="!item.in_order ? 'Создать заказ' : 'Детали заказа'" />
                    </div>
                    <div v-else>
                        <span v-text="'Заказы не разрешены'" />
                    </div>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon
                            color="grey darken-1"
                            class="mr-2"
                            v-on="on"
                            @click="(addressDialog = true), (addressEmployee = item)"
                        >
                            mdi-star-outline
                        </v-icon>
                    </template>
                    <span>Добавить аддресс</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon color="grey darken-1" class="mr-2" v-on="on" @click="showEmployeeDialog(item)">
                            mdi-pencil-outline
                        </v-icon>
                    </template>
                    <span>Редактировать </span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon color="red darken-2" v-on="on" @click="deleteEmployee(item)">
                            mdi-delete-outline
                        </v-icon>
                    </template>
                    <span>Удалить</span>
                </v-tooltip>
            </template>

            <template v-slot:item.client.phone="{ item }">
                {{ getUsingPhoneAccordinglyPhoneMask(item.client.phone) | VMask(phoneMask) }}
            </template>

            <!--FOOTER-->
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
                                            >
                                                {{ paginated.per_page }}
                                            </v-btn>
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
                                        <v-list-item-title>{{ item }}</v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </v-col>
                    </v-row>
                </div>
            </template>
        </v-data-table>

        <!--order dialog-->
        <v-dialog v-if="orderDialog" v-model="orderDialog" max-width="1000" width="100%" persistent overlay-opacity="0.7">
            <order-dialog v-if="orderDialog" :employee="orderEmployee" @cancel="closeOrderDialog('orderDialog')" />
        </v-dialog>

        <!--employee dialog-->
        <v-dialog v-if="employeeDialog" v-model="employeeDialog" max-width="750px" width="100%" persistent>
            <Employee
                :employee-obj="employeeObj"
                @close="closeEmployeeDialog()"
                @employeeCreated="employeeActionDone()"
                @employeeUpdated="employeeActionDone()"
            />
        </v-dialog>

        <!--address dialog-->
        <v-dialog v-model="addressDialog" max-width="600px" persistent width="100%">
            <v-window v-model="step">
                <v-window-item :value="1">
                    <MyAddresses
                        :employee="addressEmployee"
                        @addAddress="step = 2"
                        @editAddress="(editingAddress = $event), (step = 2)"
                        @closeDialog="addressDialog = false"
                    />
                </v-window-item>
                <v-window-item :value="2">
                    <Address
                        :employee="addressEmployee"
                        :editing-address="editingAddress"
                        @add="addAddress($event)"
                        @update="updateAddress($event)"
                        @delete="deleteAddress($event)"
                        @back="addressDialogBack()"
                    />
                </v-window-item>
            </v-window>
        </v-dialog>
    </v-flex>
</template>

<script src="./EmployeeList.main.js" />
<style scoped />
