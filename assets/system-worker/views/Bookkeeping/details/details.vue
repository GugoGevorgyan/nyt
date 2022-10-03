<!-- @format -->

<template>
    <v-card :loading="loading" class="border" elevation="6" loader-height="2">
        <div v-if="Object.keys(details).length">
            <v-card-title>
                <span>
                    Информация о транзакции <b>{{ details.type.name }}</b>
                </span>
                <v-spacer />
                <v-btn icon @click="$emit('close')">
                    <v-icon v-text="'mdi-close'" />
                </v-btn>
            </v-card-title>
            <v-divider class="mb-2" />
            <v-card-text>
                <div v-if="details.type.type_id === TRANSACTION.WAYBILL">
                    <template-waybill :details="details" />
                </div>
                <div v-if="details.type.type_id === TRANSACTION.ORDER">
                    <template-order :details="details" />
                </div>
                <div v-if="details.type.type_id === TRANSACTION.CRASH">
                    <template-crash :details="details" />
                </div>
            </v-card-text>
        </div>
        <div v-else>
            <v-skeleton-loader type="article, list-item-two-line" />
        </div>
    </v-card>
</template>

<script lang="js" src="./details.main.js" />
