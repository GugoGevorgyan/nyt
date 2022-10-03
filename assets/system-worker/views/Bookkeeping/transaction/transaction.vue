<!-- @format -->

<template>
    <v-card class="border" elevation="6">
        <v-card-title>
            Сделать транзакцию
            <v-spacer />
            <v-btn icon @click="$emit('close')">
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>

        <v-divider class="mb-5" />
        <v-card-text>
            <v-row>
                <v-col cols="12" md="4" xs="4" sm="4" lg="4" xl="4">
                    <v-select
                        label="Тип транзакции"
                        hint="Тип транзакции"
                        background-color="grey lighten-5"
                        :items="trTypes"
                        v-model="payload.type"
                        item-text="name"
                        item-value="type"
                        class="rounded-2"
                        name="type"
                        v-validate="'required'"
                        :error-messages="errors.collect('type')"
                        outlined
                        dense
                    />
                </v-col>
                <v-col cols="12" md="5" xs="5" sm="5" lg="5" xl="5">
                    <v-autocomplete
                        eager
                        label="Сторонник"
                        hint="Сторонник"
                        background-color="grey lighten-5"
                        class="rounded-2"
                        :items="drivers"
                        item-text="full_name"
                        small-chips
                        item-value="driver_id"
                        single-line
                        v-model="payload.side"
                        name="side"
                        v-validate="'required'"
                        :error-messages="errors.collect('side')"
                        outlined
                        dense
                    >
                        <template v-slot:selection="data">
                            <v-chip v-bind="data.attrs" :input-value="data.selected" @click="data.select">
                                <v-avatar left>
                                    <v-img :src="data.item.photo"></v-img>
                                </v-avatar>
                                {{ data.item.full_name }}
                            </v-chip>
                        </template>

                        <template v-slot:item="data">
                            <template v-if="'object' !== typeof data.item">
                                <v-list-item-content v-text="data.item" />
                            </template>
                            <template v-else>
                                <v-list-item-avatar>
                                    <v-img :src="data.item.photo" />
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title v-html="data.item.full_name" />
                                </v-list-item-content>
                            </template>
                        </template>
                    </v-autocomplete>
                </v-col>
                <v-col cols="12" md="2" xs="2" sm="2" lg="2" xl="2">
                    <v-text-field
                        label="Сумма"
                        hint="Сумма"
                        background-color="grey lighten-5"
                        class="rounded-2"
                        name="transaction_sum"
                        v-model="payload.sum"
                        outlined
                        dense
                        type="text"
                    />
                </v-col>
                <v-col cols="12" md="1" xs="1" sm="1" lg="1" xl="1">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                :disabled="payload.type === TRANSACTION.CRASH || payload.type === TRANSACTION.DEBT"
                                v-bind="attrs"
                                v-on="on"
                                icon
                                @click="toggleInputOutput"
                                v-model="payload.input"
                            >
                                <v-icon v-text="inputOutputIcon" :color="true === payload.input ? 'green' : 'red'" />
                            </v-btn>
                        </template>
                        <span v-text="true === payload.input ? 'Вход' : 'Выход'" />
                    </v-tooltip>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" md="4" sm="4" xs="4" lg="4">
                    <v-textarea
                        label="Комментарии"
                        placeholder="Комментарии"
                        v-model="payload.comment"
                        single-line
                        rows="1"
                        outlined
                        filled
                        name="comment"
                        v-validate="'max:35'"
                        :error-messages="errors.collect('comment')"
                    />
                </v-col>
                <v-col class="justify-end align-content-end" v-if="payload.type === TRANSACTION.DEBT && payload.side">
                    <p class="font-weight-normal mb-1">Долг</p>
                    <div v-if="!debtLoader">
                        <p class="font-weight-bold">{{ debt }}</p>
                    </div>
                    <div v-else>
                        <v-skeleton-loader width="100" type="text" />
                    </div>
                </v-col>
            </v-row>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <v-btn
                :disabled="!payload.sum"
                :loading="sendLoader"
                depressed
                @click="sendTransaction"
                class="rounded-2"
                color="primary"
                v-text="'Отправить'"
            />
        </v-card-actions>
    </v-card>
</template>
<script lang="js" src="./transaction.main.js" />
