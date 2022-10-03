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
        item-key="client_call_id"
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
        <template v-slot:item.worker="{ item }">
            <div class="d-flex justify-space-between">
                <small v-if="item.system-worker">
                    {{ item.system - worker.name }} {{ item.system - worker.surname }}
                    {{ item.system - worker.patronymic }}
                </small>
                <small v-if="item.workerable_type === 'workerDispatcher'">( Диспетчер )</small>
                <small v-else-if="item.workerable_type === 'workerOperator'">( Оператор )</small>
            </div>
        </template>
        <template v-slot:item.phone="{ item }">
            <small>{{ item.franchise_phone.number }}: {{ item.franchise_sub_phone.number }}</small>
        </template>
        <template v-slot:item.client="{ item }">
            <v-btn x-small color="success" icon @click="$emit('call', item.client_phone)">
                <v-icon small>mdi-phone</v-icon>
            </v-btn>
            <small>{{ clientTitle(item.client) }}</small>
        </template>
        <template v-slot:item.status="{ item }">
            <v-icon
                class="mx-1"
                x-small
                :color="item.incoming && item.answered ? 'primary' : item.incoming ? 'error' : 'green'"
            >
                {{
                    item.incoming && item.answered
                        ? "mdi-call-received"
                        : item.incoming
                        ? "mdi-call-missed"
                        : "mdi-call-made"
                }}
            </v-icon>
            <v-chip class="mx-1" outlined x-small color="primary">{{
                item.incoming ? "входаящий" : "исходящий"
            }}</v-chip>
            <v-chip v-if="!item.call_start && !item.call_end" outlined x-small color="primary">
                <v-progress-circular indeterminate :size="10" :width="1" color="primary"></v-progress-circular>
                <span class="mx-1">в процессе</span>
            </v-chip>
            <v-chip v-else class="mx-1" outlined x-small :color="item.answered ? 'success' : 'error'">
                {{ item.answered ? "принят" : "пропущен" }}
            </v-chip>
        </template>
        <template v-slot:item.duration="{ item }">
            <v-chip v-if="!item.call_start && !item.call_end" outlined x-small color="primary">
                <v-progress-circular indeterminate :size="10" :width="1" color="primary"></v-progress-circular>
                <span class="mx-1">звонок</span>
            </v-chip>
            <v-chip v-else-if="!item.call_end" outlined x-small color="primary">
                <v-progress-circular indeterminate :size="10" :width="1" color="primary"></v-progress-circular>
                <span class="mx-1">разговор</span>
            </v-chip>
            <small v-else>{{ item.duration_time }}</small>
        </template>

        <!--Footer-->
        <template v-slot:footer>
            <table-footer :paginated="paginated" />
        </template>
    </v-data-table>
</template>

<script lang="js" src="./CallsTable.main.js" />
