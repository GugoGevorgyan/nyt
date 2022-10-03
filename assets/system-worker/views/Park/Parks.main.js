/** @format */

import Park from "../../models/Park";
import ParkPagination from "../../forms/ParkPagination";
import ParkForm from "./form/Form";
import Snackbar from "../../facades/Snackbar";

export default {
    name: "Parks",

    components: { ParkForm: ParkForm },

    props: {
        regions: {
            required: true,
            type: Array,
        },
        cities: {
            required: true,
            type: Array,
        },
        entities: {
            required: true,
            type: Array,
        },
        managers: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            paginated: new ParkPagination({
                current_page: Number(this.$route.query["page"]),
                per_page: this.$route.query["per-page"],
                search: this.$route.query["search"],
                path: "parks/paginate",
            }),
            dialog: false,
            url: this.$store.state.initUrl,
            parkData: this.defValues(),

            deleteDialog: false,
            deletingPark: null,
            deleteLoading: false,

            window: {
                width: 0,
                height: 0,
            },
            heightDif: 190,
        };
    },

    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "get_parks",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getParks;
                },
            );
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "get_parks",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getParks;
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "get_parks",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getParks;
                },
            );
        },
    },

    methods: {
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.heightDif;
        },

        closeDialog() {
            this.dialog = false;
            this.parkData = false;
            this.parkData = this.defValues();
        },

        createPark() {
            this.parkData = this.defValues();
            this.dialog = true;
        },

        editPark(data) {
            this.parkData = data;
            this.dialog = true;
        },

        defValues() {
            return {
                name: null,
                city_id: null,
                address: null,
                city: undefined,
                image: undefined,
                park_id: false,
            };
        },

        refreshParks() {
            this.paginated.getParks;
        },

        deletePark() {
            this.deleteLoading = true;
            let park = new Park();

            park.delete({ park: this.deletingPark.park_id })
                .then(response => {
                    this.deleteDialog = false;
                    this.deletingPark = null;
                    this.deleteLoading = false;
                    this.paginated.getParks;
                    Snackbar.info(response.data.message);
                })
                .catch(error => {
                    this.deleteLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
    },

    created() {
        this.window = {
            width: 0,
            height: window.innerHeight - this.heightDif,
        };
        window.addEventListener("resize", this.handleResize);
        this.paginated.getParks;
    },
};
