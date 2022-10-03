<template>
    <v-container fluid>
    <v-data-table
        :calculate-widths="true"
        :fixed-header="true"
        :headers="paginated.headers"
        :height="window.height"
        :items="paginated._payload"
        :items-per-page="Number(paginated.per_page)"
        :loading="paginated.loading"
        dense
        class="elevation-4"
        hide-default-footer
        item-key="id"
        loader-height="2"
        multi-sort
    >
        <template v-slot:top>
            <v-container ref="header" class="pt-0 grey lighten-5" fluid>
                <v-row no-gutters class='pt-2'>
                    <v-col class="d-flex align-center" cols="12" md="4">
                        <v-toolbar-title>Отчет водителей</v-toolbar-title>
                    </v-col>
                    <v-col>
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
                    <v-spacer/>
                    <v-col cols='12' md='1' class='text-right'>
                        <v-tooltip>
                            <template v-slot:activator='{ on }' bottom>
                                <v-btn icon @click='download' v-on='on'>
                                    <v-icon v-text="'mdi-download'" />
                                </v-btn>
                            </template>
                            <span>Generate XLMS</span>
                        </v-tooltip>
                    </v-col>
                </v-row>
            </v-container>
            <v-divider />
        </template>
        <template v-slot:footer>
            <table-footer :paginated="paginated" />
        </template>
    </v-data-table>
    </v-container>
</template>

<script src='./index.main.js'></script>

<style scoped>

</style>
