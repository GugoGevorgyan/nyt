<!-- @format -->

<template>
    <v-container fluid>
        <v-data-table
            dense
            :headers="paginated.headers"
            v-model="paginated.selected"
            :items="paginated._payload"
            class="elevation-4"
            :loading="paginated.loading"
            loader-height="2"
            hide-default-footer
            :height="window.height"
            :fixed-header="true"
            :items-per-page="Number(paginated.per_page)"
            item-key="client.client_id"
            :calculate-widths="true"
            selectable-key="client.client_id"
            show-select
        >
            <template v-slot:top>
                <v-toolbar flat color="grey lighten-5">
                    <v-toolbar-title>Клиенти</v-toolbar-title>
                    <v-spacer />
                    <v-text-field
                        append-icon="mdi-magnify"
                        clearable
                        color="yellow darken-3"
                        hide-details
                        label="Поиск по названию или эл. адресу"
                        background-color="white"
                        outlined
                        dense
                        v-model="paginated.search"
                    />
                    <v-spacer />
                </v-toolbar>
            </template>

            <template v-slot:item.client.mean_assessment="{ item }">
                <v-rating
                    v-model="item.client.mean_assessment"
                    color="yellow darken-3"
                    background-color="grey darken-1"
                    empty-icon="$ratingFull"
                    half-increments
                    hover
                    dense
                    readonly
                    x-small
                />
            </template>

            <template v-slot:item.orders_sum="{ item }">
                <p>{{ item.orders_sum }} {{ paginated.currency }}</p>
            </template>

            <template v-slot:item.client.in_order="{ item }">
                <v-icon :color="item.in_order ? 'green' : 'grey'" v-text="'mdi-flask-round-bottom-empty-outline'" />
            </template>

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

        <v-dialog v-model="notifyDialog" max-width="580" overlay-opacity="0.7" width="100%">
            <notification
                path="call-center/send-client-ntf"
                v-if="notifyDialog"
                :clients="paginated.selected.map(el => el.client.client_id)"
                @closeNotify="
                    notifyDialog = false;
                    paginated.selected = [];
                "
            />
        </v-dialog>
    </v-container>
</template>

<script lang="js" src="./Index.main.js" />
