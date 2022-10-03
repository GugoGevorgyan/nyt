<!-- @format -->

<template>
    <v-container fluid>
        <v-data-table
            calculate-widths
            class="elevation-3 rounded-0"
            hide-default-footer
            dense
            fixed-header
            :height="window.height"
            :headers="paginate.headers"
            :items="paginate._payload"
            :items-per-page="Number(paginate.per_page)"
            item-key="waybill_id"
            :item-class="activeClass"
            :loading="paginate.loading"
            :loader-height="2"
            :dark="darkMode"
        >
            <!--      HEADER      -->
            <template v-slot:top>
                <v-toolbar flat height="52" :dark="darkMode" :color="darkMode ? 'black' : 'grey lighten-4'">
                    <v-toolbar-title>Путевые листы</v-toolbar-title>
                    <v-divider vertical class="mx-3" />
                    <v-row>
                        <v-col cols="12" md="3">
                            <v-text-field
                                :dark="darkMode"
                                class="rounded-2"
                                prepend-inner-icon="mdi-magnify"
                                :background-color="darkMode ? 'black' : 'white'"
                                clearable
                                color="yellow darken-3"
                                hide-details
                                label="Поиск"
                                outlined
                                dense
                                v-model="paginate.search"
                            />
                        </v-col>

                        <v-divider inset vertical :dark="darkMode" />

                        <v-col cols="12" md="3">
                            <v-autocomplete
                                :dark="darkMode"
                                clearable
                                class="rounded-2"
                                :background-color="darkMode ? 'black' : 'white'"
                                color="yellow darken-3"
                                :items="drivers"
                                item-text="driver_info.name"
                                item-value="driver_id"
                                label="Водители"
                                multiple
                                v-model="paginate.driverIds"
                                dense
                                eager
                                outlined
                                hide-details
                            >
                                <template v-slot:selection="{ item, index }">
                                    <small v-if="1 > index" class="mr-2"
                                        >{{ item.driver_info.name }} {{ item.driver_info.surname }}</small
                                    >
                                    <template v-if="1 === index">
                                        <small class="mr-1">(+{{ paginate.driverIds.length - 1 }} других)</small>
                                        <v-icon small color="grey" v-text="'mdi-magnify'" />
                                    </template>
                                </template>
                            </v-autocomplete>
                        </v-col>

                        <v-divider inset vertical :dark="darkMode" />

                        <v-col cols="12" md="2">
                            <v-autocomplete
                                :dark="darkMode"
                                class="rounded-2"
                                :background-color="darkMode ? 'black' : 'white'"
                                clearable
                                color="yellow darken-3"
                                item-color="yellow darken-3"
                                :items="parks"
                                item-text="name"
                                item-value="park_id"
                                label="Парки"
                                multiple
                                v-model="paginate.parkIds"
                                dense
                                outlined
                                hide-details
                                eager
                            >
                                <template v-slot:selection="{ item, index }">
                                    <small v-if="1 > index" class="mr-2">{{ item.name }}</small>
                                    <template v-if="1 === index">
                                        <small class="mr-1">(+{{ paginate.parkIds.length - 1 }} других)</small>
                                        <v-icon small color="grey">mdi-magnify</v-icon>
                                    </template>
                                </template>
                            </v-autocomplete>
                        </v-col>
                        <v-divider inset vertical :dark="darkMode" />

                        <v-col cols="12" md="2">
                            <el-date-picker
                                :dark="darkMode"
                                style="max-width: 100%"
                                :picker-options="paginate.pickerOptions"
                                start-placeholder="Дата от"
                                end-placeholder="Дата до"
                                type="daterange"
                                v-model="datePicker"
                            />
                        </v-col>

                        <v-divider inset vertical />

                        <v-col cols="12" md="2">
                            <v-btn
                                :dark="darkMode"
                                v-text="'Добавить лист'"
                                depressed
                                height="100%"
                                @click="paginate.showCreateWaybillDialog = true"
                            />
                        </v-col>
                    </v-row>

                    <v-tooltip left>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                :dark="darkMode"
                                v-if="
                                    paginate.dateStart ||
                                    paginate.dateEnd ||
                                    paginate.search ||
                                    paginate.driverIds.length ||
                                    paginate.parkIds.length
                                "
                                v-bind="attrs"
                                v-on="on"
                                icon
                                small
                                @click="removeFilters()"
                            >
                                <v-icon
                                    :dark="!darkMode"
                                    :color="darkMode ? 'white' : 'black'"
                                    v-text="'mdi-delete-variant'"
                                />
                            </v-btn>

                            <v-btn v-else v-bind="attrs" v-on="on" icon small disabled>
                                <v-icon color="orange" v-text="'mdi-delete-variant'" />
                            </v-btn>
                        </template>
                        <span>Clean Filters</span>
                    </v-tooltip>
                </v-toolbar>
            </template>
            <!--      END HEADER      -->

            <!--      CONTENT      -->

            <template v-slot:item.number="{ item }">
                <span>{{ item.number }}</span>
            </template>

            <template v-slot:item.driver="{ item }">
                <span>{{ item.driver.name }} {{ item.driver.surname }}</span>
            </template>

            <template v-slot:item.car="{ item }">
                <span>{{ item.car.mark }} {{ item.car.model }}</span>
            </template>

            <template v-slot:item.transaction_sum="{ item }">
                <span>{{ item.transaction_sum }} {{ paginate.currency }}</span>
            </template>

            <template v-slot:item.annulled="{ item }">
                <span v-if="item.annulled">{{ item.annulled }}</span>
                <v-icon v-else v-text="'mdi-minus'" color="grey darken-2" />
            </template>

            <template v-slot:item.start_time="{ item }">
                <span v-if="item.start_time">{{ item.start_time }}</span>
            </template>

            <template v-slot:item.end_time="{ item }">
                <span v-if="item.end_time">{{ item.end_time }}</span>
            </template>

            <!--      ADDITIONAL @todo      -->
            <template v-slot:item.additional="{ item }">
                <v-menu left nudge-left="35px" :close-on-click="true" tile>
                    <template v-slot:activator="{ on }">
                        <v-btn color="yellow darken-3" icon v-on="on">
                            <v-icon v-text="'mdi-information-outline'" />
                        </v-btn>
                    </template>
                    <v-list></v-list>
                </v-menu>
            </template>
            <!--      END ADDITIONAL      -->

            <!--      COMMENT      -->
            <template v-slot:item.comment="{ item }">
                <div v-if="item.comment">
                    <small style="color: grey">
                        {{ item.comment.substring(1, 10) }}
                    </small>
                    <v-menu transition="slide-x-transition" bottom right offset-x :close-on-content-click="false">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn small v-bind="attrs" icon color="primary" v-on="on">
                                <v-icon small v-text="'mdi-comment-account-outline'" />
                            </v-btn>
                        </template>
                        <v-list class="pa-0" max-width="350">
                            <v-list-item>
                                <v-list-item-content>
                                    {{ item.comment }}
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
                <div v-else>
                    <v-btn small icon color="primary" disabled>
                        <v-icon small v-text="'mdi-comment-account-outline'" />
                    </v-btn>
                </div>
            </template>
            <!--      END COMMENT      -->

            <template v-slot:item.check_details="{ item }">
                <v-tooltip left>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-if="item.verified" v-bind="attrs" v-on="on" icon @click="toggleInfoDialogue(item)">
                            <v-icon color="green darken-3" v-text="'mdi-comment-check-outline'" />
                        </v-btn>
                        <v-btn v-else v-bind="attrs" v-on="on" icon disabled>
                            <v-icon color="grey darken-3" v-text="'mdi-comment-check-outline'" />
                        </v-btn>
                    </template>
                    <span>Questions</span>
                </v-tooltip>

                <v-tooltip left>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" icon v-if="item.verified" @click="toggleImageDialogue(item)">
                            <v-icon color="purple darken-1" v-text="'mdi-image-outline'" />
                        </v-btn>
                        <v-btn v-bind="attrs" v-on="on" icon v-else disabled>
                            <v-icon color="grey darken-3" v-text="'mdi-image-outline'" />
                        </v-btn>
                    </template>
                    <span>Images</span>
                </v-tooltip>
            </template>

            <template v-slot:no-data>
                <div class="justify-center align-center text-center">
                    <h1 class="font-weight-medium">Путевых листов пока нет !</h1>
                </div>
            </template>
            <!--      END CONTENT      -->

            <!--      Actions      -->
            <template v-slot:item.actions="{ item }">
                <v-tooltip left>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                            class="pointer"
                            :color="item.verified && item.signed ? 'green' : 'red'"
                            v-on="on"
                            v-bind="attrs"
                            v-text="item.verified && item.signed ? 'mdi-electric-switch-closed' : 'mdi-electric-switch'"
                            @click="sendToggleCheckedWaybill(item.waybill_id, !(item.verified && item.signed))"
                        />
                    </template>
                    <span>{{ !(item.verified && item.signed) ? "Активировать" : "Деактивировать" }}</span>
                </v-tooltip>

                <v-tooltip left v-if="!item.annulled">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" small icon color="red" @click="deleteWaybill(item.waybill_id)">
                            <v-icon v-text="'mdi-delete-empty-outline'" />
                        </v-btn>
                    </template>
                    <span>Annul</span>
                </v-tooltip>

                <v-tooltip left v-else>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" small icon color="blue" @click="deleteWaybill(item.waybill_id)">
                            <v-icon v-text="'mdi-delete-restore'" />
                        </v-btn>
                    </template>
                    <span>Annul restore</span>
                </v-tooltip>

                <v-tooltip left>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-if="!item.annulled"
                            v-bind="attrs"
                            v-on="on"
                            small
                            icon
                            color="grey darken-3"
                            @click="downloadWaybill(item.waybill_id)"
                        >
                            <v-icon v-text="'mdi-cloud-download-outline'" />
                        </v-btn>

                        <v-btn v-else v-bind="attrs" v-on="on" small icon color="grey darken-1" disabled>
                            <v-icon v-text="'mdi-cloud-print-outline'" />
                        </v-btn>
                    </template>
                    <span>Download</span>
                </v-tooltip>
            </template>
            <!--      end Actions      -->

            <!--     FOOTER       -->
            <template v-slot:footer>
                <table-footer :paginated="paginate" />
            </template>
            <!--      END FOOTER      -->
        </v-data-table>

        <v-dialog max-width="700" width="100%" v-if="infoDialogue" v-model="infoDialogue" overlay-opacity="0.0">
            <info :waybill-id="infoDialogueData" @closeDialogue="infoDialogue = false" />
        </v-dialog>

        <v-dialog max-width="700" width="100%" v-if="imageDialogue" v-model="imageDialogue" overlay-opacity="0.0">
            <images :waybill-id="imageDialogueData" @closeDialogue="imageDialogue = false" />
        </v-dialog>

        <v-dialog
            max-width="400"
            width="100%"
            v-if="annulWaybillDialogue"
            v-model="annulWaybillDialogue"
            overlay-opacity="0.0"
        >
            <v-card>
                <v-container>
                    <v-form autocomplete="off">
                        <v-text-field placeholder="Подтвердите пароль" @keyup="checkPassword" />
                        <v-btn block class="grey" disabled v-if="paginate.invalidPwd">Аннулировать</v-btn>
                        <v-btn block class="red" v-else @click="annulWaybill()">Аннулировать</v-btn>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>

        <v-dialog
            overlay-opacity="0.5"
            max-width="550"
            width="100%"
            persistent
            v-if="paginate.showCreateWaybillDialog"
            v-model="paginate.showCreateWaybillDialog"
            :dark="darkMode"
        >
            <v-card class="border" :loading="createWaybillLoading" :dark="darkMode">
                <v-card-title :class="darkMode ? 'black' : 'grey lighten-5'" v-text="'Добавить путевый лист'" />

                <v-container>
                    <v-form autocomplete="off">
                        <v-row>
                            <v-col cols="12" md="8">
                                <v-autocomplete
                                    :loading="paginate.searchDriversLoading"
                                    v-model="paginate.createWaybillDriver"
                                    :search-input.sync="paginate.searchForDrivers"
                                    :items="paginate.foundDrivers"
                                    no-filter
                                    item-text="full_name"
                                    item-value="driver_id"
                                    class="mr-2 rounded-1"
                                    :background-color="darkMode ? 'black' : 'white'"
                                    :dark="darkMode"
                                    color="yellow darken-3"
                                    label="Водитель"
                                    outlined
                                    dense
                                    :no-data-text="searchDriversNoDataText"
                                />
                            </v-col>

                            <v-col cols="12" md="2" v-if="paginate.createWaybillDriver">
                                <v-text-field
                                    v-model="paginate.createWaybillDays"
                                    type="number"
                                    class="mr-2 rounded-1"
                                    :background-color="darkMode ? 'black' : 'white'"
                                    :dark="darkMode"
                                    color="yellow darken-3"
                                    label="Дни"
                                    outlined
                                    dense
                                />
                            </v-col>

                            <v-col cols="12" md="2" v-if="paginate.createWaybillDriver">
                                <v-checkbox
                                    v-model="paginate.createWaybillChecked"
                                    :dark="darkMode"
                                    color="green darken-3"
                                    hide-details
                                    dense
                                    label="Пр."
                                />
                            </v-col>
                        </v-row>
                    </v-form>

                    <template v-if="selected_driver && selected_driver.current_waybill">
                        <span v-if="!!selected_driver.current_waybill.annulled">
                            У этого водителя есть текущий аннулированный путевый лист.
                            <br />
                            Хотите его восстановить?
                        </span>
                    </template>
                </v-container>

                <v-divider />

                <template>
                    <v-card-actions>
                        <v-btn class="rounded-2" depressed @click="paginate.showCreateWaybillDialog = false">
                            Отмена
                        </v-btn>

                        <v-spacer />

                        <template v-if="selected_driver">
                            <template v-if="!selected_driver.current_waybill">
                                <v-btn depressed class="rounded-2" color="primary" @click="createWaybillDriverAdd()">
                                    Добавить
                                </v-btn>
                            </template>

                            <template v-else-if="!!selected_driver.current_waybill.annulled">
                                <v-btn class="rounded-2" color="primary" @click="createWaybillDriverRestore()">
                                    Восстановить
                                </v-btn>

                                <v-btn class="rounded-2" color="primary" @click="createWaybillDriverAdd()">
                                    Добавить новый
                                </v-btn>
                            </template>

                            <template v-else>
                                <v-btn class="rounded-2" color="primary" @click="createWaybillDriverAdd()">
                                    Добавить новый
                                </v-btn>
                            </template>
                        </template>
                    </v-card-actions>
                </template>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script lang="js" src="./Index.main.js"></script>

<style>
html {
    overflow: hidden;
}
</style>
