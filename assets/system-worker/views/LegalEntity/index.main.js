/** @format */

import LegalEntityPagination from "../../forms/LegalEntityPagination";
import Entity from "../../models/Entity";
import Snackbar from "../../facades/Snackbar";

export default {
    name: "legal_entity_index",

    props: {
        types: {
            required: true,
        },
    },

    data() {
        return {
            paginated: new LegalEntityPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: this.$route.query["per-page"],
                    search: this.$route.query["search"],
                    type: Number(this.$route.query["type"]),
                },
                "legal-entity/paginate",
            ),

            window: {
                width: 0,
                height: 0,
            },
            heightDif: 182,

            deletingObj: null,
            deleteDialog: false,
            deleteLoading: false,
        };
    },

    computed: {
        phoneMask() {
            return this.$store.state.phoneMask;
        }
    },

    methods: {
        /*paginate*/
        setQuery() {
            this.$router.push(
                {
                    name: "legal_entity_index",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                        type: this.paginated.type,
                    },
                },
                () => {
                    this.paginated.getData;
                },
            );
        },

        /*window*/
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.heightDif;
        },

        /*delete*/
        showConfirm(item) {
            this.deleteDialog = true;
            this.deletingObj = new Entity(item);
        },
        closeDelete() {
            this.deleteDialog = false;
            this.deletingObj = null;
        },
        deleteObj() {
            this.deleteLoading = true;
            this.deletingObj
                .delete({ "legal-entity": this.deletingObj.legal_entity_id })
                .then(response => {
                    this.deleteLoading = false;
                    Snackbar.info(response.data.message);
                    this.closeDelete();
                    this.paginated.getData;
                })
                .catch(error => {
                    this.deleteLoading = false;
                    Snackbar.error(error.response.data.message);
                });
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
        "paginated.type": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
    },

    created() {
        this.paginated.getData;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
