/** @format */

import DriverPagination from "../../forms/DriverPagination";
import InfoDialog from "./dialogs/InfoDialog";
import DatePicker from "../../../shared/components/form/DatePicker";

export default {
    name: "Drivers",

    props: {
        parks: {
            required: true,
            type: Array,
        },
        graphics: {
            required: true,
            type: Array,
        },
        types: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            paginated: new DriverPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per_page"]),
                    search: this.$route.query["search"],
                    park: Number(this.$route.query["park"]),
                    graphic: Number(this.$route.query["graphic"]),
                    type: Number(this.$route.query["type"]),
                    status: Number(this.$route.query["status"]),
                },
                "drivers/paginate",
            ),

            activity: [
                { value: 1, text: "онлайн" },
                { value: 0, text: "офлайн" },
            ],
            contract: [
                { value: 1, text: "с активным контрактом" },
                { value: 0, text: "без контракта" },
            ],

            window: {
                width: 0,
                height: 0,
                heightDif: 185,
            },

            driverBlock: {
                minute: "",
                driver: "",
            },

            driverInfo: {},
            infoDialog: false,
            searchText: null,
            notifyDialog: false,
        };
    },

    components: {
        info: InfoDialog,
        "date-picker": DatePicker,
    },

    watch: {
        searchText() {
            if (!this.searchText) {
                this.search();
            }
        },
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
        "paginated.park": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.type": function () {
            this.paginated.current_page = 1;
            if (this.paginated.type) {
                this.paginated.contract = 1;
            }
            this.setQuery();
        },
        "paginated.activity": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.contract": function () {
            this.paginated.current_page = 1;
            if (!this.paginated.contract) {
                this.paginated.type = null;
            }
            this.setQuery();
        },
    },

    computed: {
        phoneMask() {
            return this.$store.state.phoneMask;
        },
    },

    methods: {
        search() {
            this.paginated.search === this.searchText
                ? this.paginated.getDrivers
                : (this.paginated.search = this.searchText);
        },
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },
        setQuery() {
            this.$router.push(
                {
                    name: "park_manager_drivers",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                        park: this.paginated.park,
                        type: this.paginated.type,
                        activity: this.paginated.activity,
                        contract: this.paginated.contract,
                    },
                },
                () => {
                    this.paginated.getDrivers;
                },
            );
        },
        ratingColor(value) {
            if (5 === value) {
                return "#00C853";
            } else if (3 <= value) {
                return "yellow darken-3";
            } else {
                return "#C62828";
            }
        },

        showDriverInfo(driver) {
            this.driverInfo = driver;
            this.infoDialog = true;
        },

        rowClasses(item) {
            return "pointer";
        },

        blockDriver(driver_id) {
            this.$http.post("drivers/block", { id: driver_id, minute: this.driverBlock.minute }).then(result => {
                let driverIndex = this.paginated.data.findIndex(item => {
                    return item.driver_id === driver_id;
                });
                this.paginated.data[driverIndex].lockes.locked = result.data.locked;
                this.paginated.data[driverIndex].lockes.start = result.data.start;
                this.paginated.data[driverIndex].lockes.end = result.data.end;
            });
        },

        unBlockDriver(driver_id) {
            this.$http.post("drivers/un_block", { id: driver_id }).then(result => {
                let driverIndex = this.paginated.data.findIndex(item => {
                    return item.driver_id === driver_id;
                });
                this.paginated.data[driverIndex].lockes.locked = false;
            });
        },
        sendNotification() {
            this.notifyDialog = true;
        },
    },

    created() {
        this.paginated.getDrivers;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
