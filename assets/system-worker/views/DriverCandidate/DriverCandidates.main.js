/** @format */

import CandidatePagination from "../../forms/CandidatePagination";
import Snackbar from "../../facades/Snackbar";
import Candidate from "../../models/Candidate";
import DriverDialog from "./attach/DriverDialog";
import Driver from "../../models/Driver";
import axios from "axios";

export default {
    name: "DriverCandidates",
    components: { DriverDialog },
    props: {
        types: {
            required: true,
        },
        graphics: {
            required: true,
        },
    },
    data() {
        return {
            candidate: new Candidate(),
            paginated: new CandidatePagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: this.$route.query["per-page"],
                    search: this.$route.query["search"],
                },
                "driver-candidates/paginate",
            ),

            url: this.$store.state.initUrl,
            deletable: null,
            deleteDialog: false,
            deletesDialog: false,
            editDialog: false,

            selected: [],
            selectedIds: [],
            passwordConfirm: "",

            driverRestore: new Driver(),
            driverRestoreDialog: false,
            restoreLoading: false,
            heightDif: 198,
            window: {
                width: 0,
                height: 0,
            },
            candidateToDriver: undefined,
            driverDialog: false,
            deletesLoading: false,
            deleteLoading: false,
        };
    },
    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "get_driver_candidates",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getCandidates;
                },
            );
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "get_driver_candidates",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getCandidates;
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "get_driver_candidates",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getCandidates;
                },
            );
        },
        selected: function () {
            this.selectedIds = [];
            this.selected.forEach(item => {
                this.selectedIds.push(item.driver_candidate_id);
            });
        },
    },
    computed: {
        hasCreateCandidate() {
            return this.$store.state.permission;
        },

        getSubtypes() {
            if (this.driverRestore.type_id) {
                return this.types.find(item => {
                    return item.driver_type_id === this.driverRestore.type_id;
                }).subtypes;
            }
        },

        darkMode: {
            get() {
                return this.$store.state.dark;
            },
            set(mode) {
                this.$store.state.dark = mode;
            },
        },

        phoneMask() {
            return this.$store.state.phoneMask;
        },
    },
    methods: {
        updateInfo() {
            this.paginated.getCandidates;
        },
        confirm(item) {
            this.deleteDialog = true;
            this.deletable = new Candidate(item);
        },

        confirmDeletes(items) {
            this.deletesDialog = true;
            this.deletable = new Candidate(items, true);
        },

        destroy() {
            this.deleteLoading = true;
            this.deletable
                .delete({ "driver-candidate": this.deletable.driver_candidate_id })
                .then(response => {
                    Snackbar.success(response.data.message);
                    this.deleteLoading = false;
                    this.paginated.deleteCandidate(this.deletable.driver_candidate_id);
                    this.deleteDialog = false;
                    this.deletable = null;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.deleteLoading = false;
                    this.deleteDialog = false;
                    this.deletable = null;
                });
        },

        destroyMany() {
            this.deletesLoading = true;
            this.deletable.ids = this.selectedIds;
            this.deletable
                .deletes({ confirm: this.passwordConfirm })
                .then(response => {
                    if (200 === response.status) {
                        this.paginated.getCandidates;
                        Snackbar.success(response.data.message);
                    }
                    this.deletesLoading = false;
                    this.deletesDialog = false;
                    this.passwordConfirm = null;
                    this.deletable = null;
                    this.selected = [];
                })
                .catch(error => {
                    this.deletesLoading = false;
                    Snackbar.error(error.response.data.message);
                    Candidate.errors(error.response).forEach(error => this.errors.add(error));
                });
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.heightDif;
        },
    },

    created() {
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
        this.paginated.getCandidates;
    },
};
