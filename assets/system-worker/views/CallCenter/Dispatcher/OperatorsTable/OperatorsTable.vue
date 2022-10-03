<!-- @format -->

<template>
    <v-data-table
        :loader-height="2"
        :height="tableHeight"
        :fixed-header="true"
        :headers="paginated.headers"
        :items="paginated.data"
        :items-per-page="Number(paginated.per_page)"
        :loading="paginated.loading"
        hide-default-footer
        item-key="system_worker_id"
        :calculate-widths="true"
        dense
        disable-sort
    >
        <!--Header-->
        <template v-slot:top>
            <div ref="toolbar" class="px-2">
                <v-row>
                    <v-col cols="12" md="2">
                        <v-text-field
                            clearable
                            append-icon="mdi-magnify"
                            color="yellow darken-3"
                            hide-details
                            label="Поиск"
                            dense
                            single-line
                            v-model="paginated.search"
                        />
                    </v-col>
                </v-row>
            </div>
        </template>

        <!--Content-->
        <template v-slot:item.signed="{ item }">
            <v-chip x-small outlined :color="item.in_session ? 'success' : 'error'">
                {{ item.in_session ? "В системе" : "Не в системе" }}
            </v-chip>
        </template>
        <template v-slot:item.online="{ item }">
            <v-chip x-small outlined :color="item.worker_operator.atc_logged ? 'success' : 'error'">
                {{ item.worker_operator.atc_logged ? "На линии" : "Вне линии" }}
            </v-chip>
        </template>

        <!--Footer-->
        <template v-slot:footer>
            <table-footer :paginated="paginated" />
        </template>
    </v-data-table>
</template>

<script lang="js" src="./OperatorsTable.main.js" />
