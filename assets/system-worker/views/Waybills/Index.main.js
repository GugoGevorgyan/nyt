/** @format */

import WaybillPagination from "../../forms/WaybillPagination";
import InfoDialogue from "./InfoDialog";
import ImagesDialogue from "./ImagesDialogue";
import Snackbar from "../../facades/Snackbar";
import moment from "moment";

export default {
    name: "Waybills",

    components: {
        info: InfoDialogue,
        images: ImagesDialogue,
    },

    props: {
        parks: {
            required: true,
        },
        drivers: {
            required: true,
        },
    },

    data() {
        return {
            paginate: new WaybillPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per_page"]),
                    search: this.$route.query["search"],
                    drivers: this.$route.query["drivers"],
                    parks: this.$route.query["parks"],
                    dateStart: this.$route.query["date_start"],
                    dateEnd: this.$route.query["date_end"],
                },
                "waybills/paginate",
            ),

            infoDialogue: false,
            infoDialogueData: null,
            imageDialogue: false,
            imageDialogueData: null,
            rowColor: "#eee",
            newWaybill: [],

            annulWaybillDialogue: false,
            annulledWaybillId: null,
            toggleCheckedWaybill: false,

            window: {
                width: 0,
                height: 0,
                heightDif: 180,
            },

            datePicker: [this.$route.query.date_start, this.$route.query.date_end],

            searchDriversTimer: null,
            selected_driver: null,
            createWaybillLoading: false,
        };
    },

    watch: {
        "paginate.current_page": function () {
            this.getData();
        },
        "paginate.per_page": function () {
            this.paginate.current_page = 1;
            this.getData();
        },
        "paginate.search": function () {
            this.paginate.current_page = 1;
            this.getData();
        },
        "paginate.driverIds": function () {
            this.paginate.current_page = 1;
            this.getData();
        },
        "paginate.parkIds": function () {
            this.paginate.current_page = 1;
            this.getData();
        },
        datePicker: function () {
            this.paginate.dateStart = this.datePicker ? moment(this.datePicker[0]).format("YYYY-MM-DD") : undefined;
            this.paginate.dateEnd = this.datePicker ? moment(this.datePicker[1]).format("YYYY-MM-DD") : undefined;
            this.paginate.current_page = 1;
            this.getData();
        },
        "paginate.searchForDrivers": function (search) {
            clearTimeout(this.searchDriversTimer);

            this.paginate.searchDriversLoading = true;

            this.searchDriversTimer = setTimeout(() => {
                if (search && 3 <= search.length) {
                    this.paginate.searchDrivers(search, () => {
                        this.paginate.searchDriversLoading = false;
                    });
                } else {
                    this.paginate.foundDrivers = [];

                    this.paginate.searchDriversLoading = false;
                }
            }, 500);
        },

        "paginate.createWaybillDriver": function () {
            if (!this.paginate.createWaybillDriver) {
                return;
            }

            this.selected_driver = this.paginate.foundDrivers.find(driver => {
                return driver.driver_id == this.paginate.createWaybillDriver;
            });
        },
    },

    computed: {
        auth() {
            return this.$store.state.auth.user;
        },

        tableHeight() {
            return this.height - this.toolbarHeight - this.footerHeight - 45;
        },
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                this.$store.state.dark = mode;
            },
        },
        searchDriversNoDataText() {
            return this.paginate.searchDriversLoading
                ? "Поиск..."
                : !this.searchDrivers || this.searchDrivers.length < 3
                ? "Введите имя, телефон или почту водителя (минимум 3 символа)"
                : "Результатов не найдено";
        },
    },

    methods: {
        toggleInfoDialogue(item) {
            this.infoDialogueData = item.waybill_id;
            this.infoDialogue = !this.infoDialogue;
        },

        toggleImageDialogue(item) {
            this.imageDialogueData = item.waybill_id;
            this.imageDialogue = !this.imageDialogue;
        },

        getData() {
            this.$router.push(
                {
                    name: "get_waybills",
                    query: {
                        page: this.paginate.current_page || undefined,
                        per_page: this.paginate.per_page || undefined,
                        search: this.paginate.search || undefined,
                        drivers: this.paginate.driverIds || undefined,
                        parks: this.paginate.parkIds || undefined,
                        date_start: this.paginate.dateStart || undefined,
                        date_end: this.paginate.dateEnd || undefined,
                    },
                },
                () => {
                    this.paginate.waybills;
                },
            );
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },

        checkPassword(event) {
            let pwd = event.target.value;
            this.paginate.checkWorkerPwd(this.auth.system_worker_id, pwd);
        },

        deleteWaybill(waybill_id) {
            this.annulledWaybillId = waybill_id;
            this.annulWaybillDialogue = true;
        },

        activeClass(item) {
            let index = this.newWaybill.findIndex(newWaybill => {
                return newWaybill.waybill_id === item.waybill_id;
            });
            return ~index ? this.rowColor : "";
        },

        annulWaybill() {
            this.paginate
                .annulWaybill(this.annulledWaybillId)
                .then(result => {
                    Snackbar.info(result.data.message);

                    this.paginate._payload.findIndex(tableItem => {
                        if (tableItem.waybill_id === this.annulledWaybillId) {
                            if (result.data._payload.annulled) {
                                tableItem.annulled = result.data._payload.annulled;
                            } else {
                                tableItem.annulled = null;
                            }
                        }
                    });
                })
                .catch(error => {
                    Snackbar.error(error.data.message);
                });
            this.paginate.invalidPwd = true;
            this.annulWaybillDialogue = false;
        },

        downloadWaybill(waybill_id) {
            this.paginate.downloadWaybill(waybill_id);
        },

        removeFilters() {
            this.paginate.search = "";
            this.paginate.driverIds = [];
            this.paginate.parkIds = [];
            this.paginate.dateStart = "";
            this.paginate.dateEnd = "";
            this.datePicker = "";
        },

        createWaybillDriverAdd() {
            this.createWaybillLoading = true;

            this.paginate.createWaybillAdd(() => {
                this.createWaybillLoading = false;
            });
        },

        createWaybillDriverRestore() {
            this.createWaybillLoading = true;

            this.paginate.createWaybillRestore(() => {
                this.createWaybillLoading = false;
            });
        },

        sendToggleCheckedWaybill(waybill_id, checked = 0) {
            let check = checked;

            let index = this.paginate._payload.findIndex(item => {
                return item.waybill_id === waybill_id;
            });
            this.paginate._payload[index].verified = check;
            this.paginate._payload[index].signed = check;

            this.$http.put(`waybills/checked/${waybill_id}/${check}`).then().catch();
        },
    },

    mounted() {
        Echo.join(
            `${process.env.MIX_CHANNEL_WORKER_WEB}-worker.${this.auth.system_worker_id}.${this.auth.franchise_id}`,
        ).listen("WaybillCreateEvent", waybill => {
            if (1 === this.paginate.current_page) {
                this.newWaybill.push(waybill.payload);
                this.rowColor = "green lighten-5 pointer";
                this.paginate._payload.unshift(waybill.payload);
                if (this.paginate._payload.length > this.paginate.perPages) {
                    this.paginate._payload.pop();
                }
            }
        });
    },

    created() {
        this.paginate.waybills;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
