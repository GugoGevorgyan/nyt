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
        item-key="driver_id"
        :calculate-widths="true"
    >
        <template v-slot:top>
            <div ref="toolbar" class="px-4 py-4">
                <v-row>
                    <v-col cols="12" md='1'>
                       <v-toolbar-title>Штрафы</v-toolbar-title>
                    </v-col>

                    <v-col cols="12" md="4">
                            <v-text-field
                                append-icon="mdi-magnify"
                                clearable
                                color="yellow darken-3"
                                hide-details
                                label="Поиск по названию или номер тел."
                                background-color="white"
                                outlined
                                dense
                                v-model="paginated.search"
                            />
                    </v-col>

                    <v-col cols="12" md="2">
                            <el-date-picker
                                class="custom-input"
                                start-placeholder="Дата от"
                                end-placeholder="Дата до"
                                type="daterange"
                                v-model="datePicker"
                            />
                    </v-col>
                </v-row>
            </div>
            <v-divider/>
        </template>

        <template v-slot:item.penalty.offense_date='{item}' class='m-auto'>
            <v-menu
                v-if='item.penalty.offense_date'
                transition='slide-x-transition'
                bottom
                right
                offset-x
                max-width='250px'
                class='mr-2'
                :close-on-content-click='false'
            >
                <template v-slot:activator='{on}'>
                        <v-icon color='info' v-on='on' small>mdi-information-outline</v-icon>
                </template>
                <v-list>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Дата нарушения:</v-list-item-title>
                            <v-list-item-subtitle class='mt-2'>
                                {{ item.penalty.offense_date }}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Время нарушения:</v-list-item-title>
                            <v-list-item-subtitle class='mt-2'>
                                {{ item.penalty.offense_time }}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Полный адрес:</v-list-item-title>
                            <v-list-item-subtitle v-if='item.penalty.offense_location' class="text-wrap">
                                {{ item.penalty.offense_location }}
                            </v-list-item-subtitle>
                            <v-list-item-subtitle v-else class='red--text'>
                                Нет адреса
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-menu>
                <v-tooltip v-else bottom>
                    <template v-slot:activator='{on}'>
                        <v-icon color='red' v-on='on' small>mdi-information-outline</v-icon>
                    </template>
                    <span>Нет информации</span>
                </v-tooltip>
        </template>

        <template v-slot:item.place='{item}'>
                <v-icon color='info' small @click='showViolationPlace(item)' v-if='item.penalty.offense_location'>
                    mdi-map-marker
                </v-icon>
            <v-tooltip v-else bottom>
                <template v-slot:activator='{on}'>
                        <v-icon v-on='on' color='red' small>mdi-map-marker</v-icon>
                </template>
                <span>Нет информации</span>
            </v-tooltip>
        </template>

        <template v-slot:item.current_debt.phone='{item}'>
            <div v-if='item.current_debt'>
                <span v-if='item.current_debt.phone'>{{getUsingPhoneAccordinglyPhoneMask(item.current_debt.phone) | VMask(phoneMask)}}</span>
            </div>
        </template>

        <template v-slot:item.cost='{item}'>
            <v-chip x-small color="primary" outlined>
                {{ "₽ " + item.cost }}
            </v-chip>
        </template>

        <template v-slot:item.firm_paid='{item}'>
            <v-tooltip bottom>
                <template v-slot:activator='{ on }'>
                        <v-icon
                            v-if="item.firm_paid"
                            color="success"
                            @click='setModalValues(item)'
                            v-on='on'
                            dense
                        >mdi-check</v-icon>
                        <v-icon
                            v-else
                            v-on='on'
                            @click='setModalValues(item)'
                            color="error"
                            dense
                        >mdi-close</v-icon>
                </template>
                <span>Редактировать</span>
            </v-tooltip>
        </template>

        <template v-slot:item.penalty.status='{item}'>
            <div v-if='item.penalty'>
                <v-icon v-if='item.penalty.status' color='success' dense>mdi-check</v-icon>
                <v-icon v-else color='error' dense>mdi-close</v-icon>
            </div>
        </template>
        <template v-slot:footer>
            <table-footer
                :paginated="paginated"
            />
        </template>
    </v-data-table>
        <v-dialog v-if='firmPaidModal' v-model='firmPaidModal' max-width='550px' persistent>
            <v-card>
                <v-card-title>Редактировать информацию
                    <v-spacer />
                    <v-btn icon  @click='firmPaidModal = !firmPaidModal'>
                        <v-icon
                            color='error'
                        >mdi-close
                        </v-icon>
                    </v-btn>

                </v-card-title>
                <v-divider />

                <v-toolbar color='white'>
                    <v-toolbar-title>
                        Сумма {{ existedDebt.cost }} руб.
                    </v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-btn
                        color='success'
                        class='mr-1'
                        :disabled='existedDebt.firm_paid > 0'
                        :loading='btnSuccessLoading'
                        @click='updateFirmPaidValue(1)'>
                        <span>Оплаченный</span>
                    </v-btn>
                    <v-btn
                        @click='updateFirmPaidValue(0)'
                        color='error'
                        :disabled='existedDebt.firm_paid < 1'
                        :loading='btnCancelLoading'
                    >
                        <span>Не оплачено</span>
                    </v-btn>
                </v-toolbar>
            </v-card>
        </v-dialog>
        <v-dialog v-if='placeViolationDialog' v-model='placeViolationDialog' max-width='400px'>
            <place-violation :penalty='existedPenalty' @close='closePlaceViolationModal' />
        </v-dialog>
    </v-container>
</template>

<script>
import PenaltyPagination from '../../forms/PenaltyPagination';
import axios from 'axios';
import Snackbar from '../../facades/Snackbar';
import moment from "moment-timezone";
import PlaceViolation from '../../components/Penalty/PlaceViolation';

export default {
    name: 'Index',
    components: { PlaceViolation },
    data() {
        return {
            paginated: new PenaltyPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per_page"]),
                    search: this.$route.query["search"],
                    date_start: this.$route.query["date_start"],
                    date_end: this.$route.query["date_end"],
                },
                "penalties/pager",
            ),
            datePicker: [this.$route.query.date_start, this.$route.query.date_end],
            window: {
                width: 0,
                height: 0,
                heightDif: 200,
            },
            existedDebt: null,
            existedPenalty: null,
            firmPaidModal: false,
            btnSuccessLoading: false,
            btnCancelLoading: false,
            placeViolationDialog: false,
        }
    },
    computed: {
        phoneMask() {
            return this.$store.state.phoneMask;
        },
    },
    watch: {
        "paginated.current_page": function () {
            this.setQuery();
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        datePicker: function () {
            let format = "YYYY-MM-DD";
            this.paginated.date_start = this.datePicker ? moment(this.datePicker[0]).format(format) : undefined;
            this.paginated.date_end = this.datePicker ? moment(this.datePicker[1]).format(format) : undefined;
            this.paginated.current_page = 1;
            this.setQuery();
        },
    },
    methods: {
        setQuery() {
            this.$router.push(
                {
                    name: this.route,
                    query: {
                        page: this.paginated.current_page,
                        per_page: this.paginated.per_page,
                        search: this.paginated.search,
                        date_start: this.paginated.date_start,
                        date_end: this.paginated.date_end,
                    },
                },
                () => {
                    this.paginated.penalties;
                },
            );
        },
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },
        setModalValues(item) {
            this.placeViolationDialog = false;
            this.firmPaidModal = !this.firmPaidModal;
            this.existedDebt = item;
        },
        updateFirmPaidValue(value) {
            value ? this.btnSuccessLoading = true
                : this.btnCancelLoading = true;
            axios.get(`penalties/firm_to_paid/${this.existedDebt.debt_id}/${value}`)
                .then((res) => {
                    this.paginated.penalties
                    this.btnSuccessLoading = false;
                    this.btnCancelLoading = false;
                    this.firmPaidModal = false;
                    Snackbar.info(res.data);
                }).catch((err) => {
                    Snackbar.error(err.response.data)
            });
        },
        getUsingPhoneAccordinglyPhoneMask(phone) {
            // One element is '+' in code; Example '+374';
            return phone.substr(this.phoneMask.indexOf('(') - 1)
        },
        showViolationPlace(debtInfo) {
            this.placeViolationDialog = true;
            this.existedPenalty = debtInfo.penalty;
        },

        closePlaceViolationModal() {
            this.placeViolationDialog = false;
        }
    },
    created() {
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
        this.paginated.penalties;
    }
};
</script>

<style scoped>

</style>
