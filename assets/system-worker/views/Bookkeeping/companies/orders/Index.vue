<!-- @format -->

<template>
    <div>
        <v-data-table
            :calculate-widths="true"
            :fixed-header="true"
            :headers="paginated.headers"
            :height="window.height"
            :items="paginated._payload"
            :items-per-page="Number(paginated.per_page)"
            :loading="paginated.loading"
            dense
            hide-default-footer
            item-key="id"
            loader-height="2"
            multi-sort
        >
            <template slot="no-data">
                <h1 class="font-weight-medium mt-10">
                    {{ !paginated.company ? "Выберите компанию, чтобы показать результаты" : "Нет результатов" }}
                </h1>
            </template>

            <template v-slot:loading>
                <div class="justify-center align-center text-center">
                    <h1 class="font-weight-medium mt-10">Загрузка...</h1>
                </div>
            </template>

            <template v-slot:top>
                <v-toolbar color="white" flat height="53px">
                    <v-row>
                        <v-col cols="12" md="2">
                            <v-autocomplete
                                v-model="paginated.company"
                                :items="companies"
                                background-color="grey lighten-4"
                                color="yellow darken-3"
                                item-color="yellow darken-3"
                                class="rounded-2"
                                dense
                                hide-details
                                item-text="title"
                                item-value="company_id"
                                label="Компании"
                                hint="Компании"
                                menu-props="auto"
                                outlined
                            />
                        </v-col>

                        <v-divider inset vertical />

                        <v-col cols="12" md="2">
                            <el-date-picker
                                v-model="datePicker"
                                :picker-options="pickerOptions"
                                end-placeholder="до"
                                range-separator="|"
                                size="large"
                                start-placeholder="от"
                                style="max-width: 100%"
                                type="daterange"
                                class="hideCloseButton"
                            />
                        </v-col>

                        <v-spacer />

                        <div class="d-flex align-center">
                            <v-btn icon @click="download">
                                <v-icon v-text="'mdi-download'" />
                            </v-btn>
                        </div>

                        <v-divider inset vertical />

                        <v-col cols="12" md="2" class="d-flex align-center">
                            Итого: {{ new Intl.NumberFormat("ru-RU").format(total_amount) }} руб.
                        </v-col>
                    </v-row>
                </v-toolbar>
                <v-divider />
            </template>

            <template v-slot:item.created_at="{ item }">
                {{ item.created_at | formatDateTime }}
            </template>

            <template v-slot:item.started="{ item }">
                {{ item.started | formatDateTime }}
            </template>

            <template v-slot:item.ended="{ item }">
                {{ item.ended | formatDateTime }}
            </template>

            <template v-slot:footer>
                <table-footer :paginated="paginated" />
            </template>
        </v-data-table>
    </div>
</template>

<script src='./Index.main.js' />
<style lang="scss" src='./index.style.scss' />
