<!-- @format -->

<template>
    <div>
        <h3 class="text-center">Дата и Время</h3>

        <v-card class="mt-3" width="290" outlined style="box-shadow: none; border-color: #d7d7d7; border-radius: 5px">
            <v-card-title class="pa-3">
                <v-layout row>
                    <v-flex>
                        <!--                        <v-icon class="mr-2" v-text="'mdi-clock-outline'" />-->
                        <!--                        <span v-if="date !== now" class="font-weight-medium" style="font-size: 0.8em">{{ date }}</span>
                        <span class="font-weight-medium" style="font-size: 0.8em"> {{ time }} </span>-->

                        <v-menu open-on-hover top offset-y left rounded="6">
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    class="float-right"
                                    v-bind="attrs"
                                    small
                                    v-on="on"
                                    v-text="'mdi-information-outline'"
                                    color="red darken-3"
                                />
                            </template>

                            <v-list dense class="ma-0 pa-0">
                                <v-list-item dense class="warning">
                                    <v-list-item-title
                                        class="ma-0 pa-0"
                                        v-text="'! Этот функционал находится бета тестировании.'"
                                    />
                                </v-list-item>
                            </v-list>
                        </v-menu>
                        <v-checkbox
                            :disabled="preorder"
                            dense
                            color="yellow darken-3"
                            class="pa-0 ma-0 mt-2"
                            v-model="addMinute"
                            hide-details
                            label="Заказ через 30 минут"
                            value="31"
                        />
                        <v-checkbox
                            :disabled="preorder"
                            dense
                            color="yellow darken-3"
                            class="pa-0 ma-0"
                            v-model="addMinute"
                            hide-details
                            label="Заказ через 60 минут"
                            value="61"
                        />
                    </v-flex>
                </v-layout>
            </v-card-title>

            <v-divider />

            <v-card-actions class="pa-0">
                <v-menu
                    :close-on-click="false"
                    :close-on-content-click="false"
                    nudge-right="20"
                    nudge-top="10"
                    offset-x
                >
                    <template v-slot:activator="{ on }">
                        <v-card class="pa-0 border" elevation="0">
                            <v-layout>
                                <v-card-actions>
                                    <el-date-picker
                                        v-model="elementDate"
                                        type="datetime"
                                        placeholder="Датa и время"
                                        format="HH:mm dd-MM-yyyy"
                                        :picker-options="datePickerOptions"
                                        popper-class="orderTimePicker"
                                        :clearable="false"
                                    />

                                    <v-btn small icon class="ml-2">
                                        <v-icon color="grey darken-3" v-text="'mdi-check'" />
                                    </v-btn>
                                    <v-btn small @click="close" icon>
                                        <v-icon color="red darken-5" v-text="'mdi-window-close'" />
                                    </v-btn>
                                </v-card-actions>
                            </v-layout>
                        </v-card>
                    </template>
                </v-menu>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script src="./PreOrder.main.js" />
<style scoped>
.el-date-editor {
    width: 185px !important;
}
</style>
