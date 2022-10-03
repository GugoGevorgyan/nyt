<!-- @format -->

<template>
    <v-container fluid>
        <v-data-table
            v-model="paginated.selected"
            :calculate-widths="true"
            :fixed-header="true"
            :headers="paginated.headers"
            :height="window.height"
            :item-class="rowClasses"
            :items="paginated.data"
            :items-per-page="Number(paginated.per_page)"
            :loading="paginated.loading"
            dense
            class="elevation-4"
            disable-sort
            hide-default-footer
            item-key="driver_id"
            loader-height="2"
            selectable-key="driver_id"
            show-select
            @click:row="showDriverInfo"
        >
            <template v-slot:no-data>
                <div class="justify-center align-center text-center">
                    <h1 class="font-weight-medium mt-10">Водителей ещё нет !</h1>
                </div>
            </template>

            <template v-slot:loading>
                <div class="justify-center align-center text-center">
                    <h1 class="font-weight-medium mt-10">Загрузка...</h1>
                </div>
            </template>

            <template v-slot:top>
                <v-container ref="header" class="pt-0 grey lighten-5" fluid>
                    <v-row no-gutters>
                        <v-col class="d-flex align-center" cols="12" md="2">
                            <v-toolbar-title>Водители</v-toolbar-title>
                        </v-col>
                        <v-col class="d-flex align-end" cols="12" md="2">
                            <form style="width: 100%" @submit.prevent="search()">
                                <v-text-field
                                    v-model="searchText"
                                    clearable
                                    color="yellow darken-3"
                                    hide-details
                                    item-color="yellow darken-3"
                                    label="Поиск"
                                    single-line
                                />
                            </form>
                            <v-btn :disabled="!searchText" color="yellow darken-3" fab x-small @click="search()">
                                <v-icon color="white">mdi-magnify</v-icon>
                            </v-btn>
                            <v-divider class="mx-4" inset vertical />
                        </v-col>
                        <v-col class="d-flex" cols="12" md="2">
                            <v-select
                                v-model="paginated.contract"
                                :items="contract"
                                clearable
                                color="yellow darken-3"
                                hide-details
                                item-color="yellow darken-3"
                                item-text="text"
                                item-value="value"
                                label="Контракт"
                                menu-props="auto"
                                single-line
                            />
                            <v-divider class="mx-4" inset vertical />
                        </v-col>
                        <v-col class="d-flex" cols="12" md="2">
                            <v-select
                                v-model="paginated.type"
                                :items="types"
                                clearable
                                color="yellow darken-3"
                                hide-details
                                item-color="yellow darken-3"
                                item-text="type"
                                item-value="driver_type_id"
                                label="Тип контракта"
                                menu-props="auto"
                                single-line
                            />
                            <v-divider class="mx-4" inset vertical />
                        </v-col>
                        <v-col class="d-flex" cols="12" md="2">
                            <v-select
                                v-model="paginated.park"
                                :items="parks"
                                clearable
                                color="yellow darken-3"
                                hide-details
                                item-color="yellow darken-3"
                                item-text="name"
                                item-value="park_id"
                                label="Парк"
                                menu-props="auto"
                                single-line
                            />
                            <v-divider class="mx-4" inset vertical />
                        </v-col>
                        <v-col class="d-flex" cols="12" md="2">
                            <v-select
                                v-model="paginated.activity"
                                :items="activity"
                                clearable
                                color="yellow darken-3"
                                hide-details
                                item-color="yellow darken-3"
                                item-text="text"
                                item-value="value"
                                label="Активность"
                                menu-props="auto"
                                single-line
                            />
                        </v-col>
                    </v-row>
                </v-container>
                <v-divider />
            </template>

            <template v-slot:item.driver="{ item }">
                <div class="d-flex align-center justify-space-between">
                    <small
                        >{{ item.driver_info.surname }} {{ item.driver_info.name }}
                        {{ item.driver_info.patronymic }}</small
                    >
                    <div>
                        <v-menu v-if="item.driver_info.photo" offset-x open-on-hover>
                            <template v-slot:activator="{ on, attrs }">
                                <v-avatar v-bind="attrs" v-on="on" size="32">
                                    <v-img :src="item.driver_info.photo" />
                                </v-avatar>
                            </template>
                            <v-img :src="item.driver_info.photo" width="200" />
                        </v-menu>
                        <v-icon v-else color="grey darken-2" dark small>mdi-camera-outline</v-icon>
                    </div>
                </div>
            </template>
            <template v-slot:item.park="{ item }">
                <small v-if="item.park">{{ item.park.name }}</small>
                <v-chip v-else-if="item.car" color="error" outlined x-small
                    >Борт водителя не прикреплен к парку
                </v-chip>
                <v-chip v-else color="error" outlined x-small>Нет автомобиля</v-chip>
            </template>
            <template v-slot:item.board="{ item }">
                <div v-if="item.car" class="d-flex justify-space-between align-center">
                    <small>
                        {{ item.car.mark }}
                        {{ item.car.model }}
                    </small>
                    <template>
                        <v-menu :close-on-content-click="false" offset-x>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn v-bind="attrs" v-on="on" color="primary" icon x-small>
                                    <v-icon>mdi-information-outline</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item>
                                    <v-list-item-content>
                                        <small>Автомобиль:</small>
                                        <v-divider />
                                        <div class="d-flex align-center">
                                            <span class="mr-2">
                                                {{ item.car.mark }}
                                                {{ item.car.model }}
                                            </span>
                                            <div
                                                :style="{ 'background-color': item.car.color }"
                                                class="elevation-7 mr-2"
                                                style="height: 13px; width: 13px; border-radius: 50%"
                                            ></div>
                                            <span>{{ item.car.color }}</span>
                                        </div>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item>
                                    <v-list-item-content>
                                        <small>Номер борта:</small>
                                        <v-divider></v-divider>
                                        <span>{{ item.car.garage_number }}</span>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item>
                                    <v-list-item-content>
                                        <small>Парк:</small>
                                        <v-divider></v-divider>
                                        <span>{{ item.park.name }}</span>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item>
                                    <v-list-item-content>
                                        <small>Государственный номерной знак:</small>
                                        <v-divider></v-divider>
                                        <span>{{ item.car.state_license_plate }}</span>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item>
                                    <v-list-item-content>
                                        <small>Дата выпуска:</small>
                                        <v-divider></v-divider>
                                        <span>{{ item.car.year }}</span>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </template>
                </div>
                <v-chip v-else color="error" outlined x-small>Нет автомобиля</v-chip>
            </template>
            <template v-slot:item.graphic="{ item }">
                <small v-if="item.graphic">{{ item.graphic.name }}</small>
                <v-chip v-else color="error" outlined x-small>Нет активного контракта</v-chip>
            </template>
            <template v-slot:item.type="{ item }">
                <small v-if="item.subtype">{{ item.subtype.name }}</small>
                <v-chip v-else color="error" outlined x-small>Нет активного контракта</v-chip>
            </template>
            <template v-slot:item.contract_type="{ item }">
                <small v-if="item.type">{{ item.type.type }}</small>
                <v-chip v-else color="error" outlined x-small>Нет активного контракта</v-chip>
            </template>
            <template v-slot:item.activity="{ item }">
                <v-chip :color="item.online ? 'success' : 'error'" outlined x-small
                    >{{ item.online ? "онлайн" : "офлайн" }}
                </v-chip>
            </template>
            <template v-slot:item.rating="{ item }">
                <v-rating
                    :background-color="ratingColor(item.mean_assessment)"
                    :color="ratingColor(item.mean_assessment)"
                    :value="item.mean_assessment"
                    dense
                    readonly
                    small
                />
            </template>
            <template v-slot:item.created_at="{ item }">
                {{ item.created_at | formatDate }}
            </template>

            <template v-slot:item.phone="{ item }">
                {{ item.phone | VMask(phoneMask) }}
            </template>

            <template v-slot:item.lockes.locked="{ item }">
                <v-icon v-if="!item.lockes" color="grey" v-text="'mdi-close'" />

                <v-menu
                    v-else-if="item.lockes"
                    :close-on-content-click="false"
                    bottom
                    left
                    offset-x
                    right
                    top
                    transition="slide-x-transition"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <div class="d-flex justify-space-between align-center" style="width: 100%">
                            <v-btn v-bind="attrs" v-on="on" color="primary" icon small>
                                <v-icon color="yellow darken-3" v-text="'mdi-information-outline'" />
                            </v-btn>
                        </div>
                    </template>
                    <v-list color="grey lighten-4" max-width="300">
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-row>
                                    <v-col cols="12" md="2">
                                        <v-btn
                                            v-if="item.lockes.locked"
                                            color="green lighten-1"
                                            small
                                            @click="unBlockDriver(item.driver_id)"
                                            v-text="'Разблокировать'"
                                        />
                                    </v-col>
                                </v-row>
                                <span>
                                    Блокирован:
                                    <v-icon v-if="item.lockes.locked" color="red darken-3" v-text="'mdi-check'" />
                                    <v-icon v-else color="grey" v-text="'mdi-close'" />
                                </span>
                                <span>Количество: {{ item.lockes.lock_count }}</span>
                                <div v-if="item.lockes.locked">
                                    <v-divider />
                                    <p class="text-center mt-1">Период</p>
                                    <p class="mb-3">От: {{ item.lockes.start }}</p>
                                    <p>До: {{ item.lockes.end }}</p>
                                </div>
                                <v-divider class="mt-3" />
                                <v-expansion-panels>
                                    <v-expansion-panel>
                                        <v-expansion-panel-header v-if="!item.lockes.locked"
                                            >Блокировать
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-header v-else>Продлить</v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-text-field
                                                v-model="driverBlock.minute"
                                                v-mask="'###'"
                                                class="rounded-0"
                                                label="Минуты"
                                                outlined
                                                type="text"
                                            />
                                            <v-btn
                                                block
                                                color="orange dark-3"
                                                @click="blockDriver(item.driver_id)"
                                                v-text="'Block'"
                                            />
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-expansion-panels>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>

            <!--FOOTER-->
            <template v-slot:footer>
                <table-footer
                    :paginated="paginated"
                    :first-event="true"
                    first-event-icon="mdi-send-outline"
                    first-event-text="Отправить оповещение"
                    @firstEvent="sendNotification"
                />
            </template>
        </v-data-table>

        <v-dialog v-model="infoDialog" max-width="580" overlay-opacity="0.7" width="100%">
            <info v-if="infoDialog" :driver="driverInfo" @infoEmit="infoDialog = $event.close" />
        </v-dialog>

        <v-dialog v-model="notifyDialog" max-width="580" overlay-opacity="0.7" width="100%">
            <notification
                v-if="notifyDialog"
                :clients="paginated.selected.map(el => el.driver_id)"
                path="call-center/send-driver-ntf"
                @closeNotify="
                    notifyDialog = false;
                    paginated.selected = [];
                "
            />
        </v-dialog>
    </v-container>
</template>

<script lang="js" src="./index.main.js" />
