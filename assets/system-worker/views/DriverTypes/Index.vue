<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <v-layout row>
            <v-flex v-for="(item, index) in types" :key="index" md6 xl6 xs12>
                <v-hover>
                    <template v-slot:default="{ hover }">
                        <v-card class="border-lg transition-swing" :elevation="hover ? 24 : 8" :dark="darkMode">
                            <v-card-title class="text-capitalize">{{ item.type }} - {{ item.percent }}%</v-card-title>
                            <v-card-text>
                                <v-layout row>
                                    <v-flex xs12 md6>
                                        <v-img :src="item.image" height="300" />
                                    </v-flex>
                                    <v-flex xs12 md6>
                                        <p>{{ item.description }}</p>
                                        <div v-for="(option, index) in item.franchise_options" :key="index">
                                            <v-chip
                                                v-if="!option.valued"
                                                outlined
                                                small
                                                color="green"
                                                style="margin: 1px"
                                                v-text="option.name"
                                            />
                                        </div>

                                        <v-btn
                                            @click="showDialog(item)"
                                            icon
                                            color="secondary"
                                            style="position: absolute; bottom: 10px; right: 10px"
                                        >
                                            <v-icon v-text="'mdi-settings'" />
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                    </template>
                </v-hover>
            </v-flex>

            <v-dialog v-if="dialog" v-model="dialog" scrollable max-width="500px" width="100%" :dark="darkMode">
                <v-card v-if="type" class="border" :dark="darkMode">
                    <v-card-title>Выбрать опции для {{ type.type }}</v-card-title>
                    <v-divider />
                    <v-card-text class="px-0 py-2">
                        <v-list-item-group v-model="type.options" multiple>
                            <v-list-item
                                v-if="!option.valued"
                                v-for="option in options"
                                :key="option.driver_type_optional_id"
                                :value="option.driver_type_optional_id"
                            >
                                <template v-slot:default="{ active, toggle }">
                                    <v-list-item-content>
                                        <v-list-item-title v-text="option.name"></v-list-item-title>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-checkbox
                                            :input-value="active"
                                            :true-value="option.driver_type_optional_id"
                                            color="secondary"
                                            @click="toggle"
                                            dense
                                        />
                                    </v-list-item-action>
                                </template>
                            </v-list-item>

                            <v-divider />
                            <v-row class="ma-0">
                                <v-col cols="12" md="12" class="ma-0">
                                    <v-form autocomplete="off">
                                        <div v-if="option.valued" v-for="option in options">
                                            <v-text-field
                                                style="max-width: 100px"
                                                label="Процент %"
                                                v-mask="'###'"
                                                type="text"
                                                v-model="type.options_value"
                                                dense
                                                class="rounded-3 mt-5 float-right"
                                                name="percent"
                                                v-validate="'max_value:100'"
                                                :error-messages="errors.collect('percent')"
                                            />
                                        </div>
                                    </v-form>
                                </v-col>
                            </v-row>
                        </v-list-item-group>
                    </v-card-text>
                    <v-divider />
                    <v-card-actions>
                        <v-flex class="justify-end d-flex">
                            <v-btn @click="closeDialog()" color="error" text v-text="'отмена'" />
                            <v-btn @click="save()" text color="primary" :loading="loading" v-text="'сохранить'" />
                        </v-flex>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </v-container>
</template>

<script src="./Index.main.js" />
