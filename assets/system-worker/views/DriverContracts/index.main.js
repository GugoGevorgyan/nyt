/** @format */

import ContractPagination from "../../forms/ContractPagination";
import Snackbar from "../../facades/Snackbar";
import axios from "axios";
import Form from "../../base/Form";

export default {
    props: {},

    data() {
        return {
            selectActiveItems: [
                { text: "Активные", value: "1" },
                { text: "Не активные", value: "0" },
            ],

            paginated: new ContractPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"]),
                    search: this.$route.query["search"],
                    active: this.$route.query["active"],
                },
                "driver-contracts/paginate",
            ),

            carDialog: false,
            showCar: undefined,

            terminateDialog: false,
            terminateContract: undefined,
            terminatePassword: null,
            terminateLoading: false,

            window: {
                width: 0,
                height: 0,
                heightDif: 187,
            },
            rules: {
                free_days_price: "required",
                busy_days_price: "required",
            },
        };
    },

    filters: {
        dateFormat: function (value) {
            return value ? new Date(value).toISOString().slice(0, 10) : "";
        },
    },

    watch: {
        "paginated.current_page": function () {
            this.setQuery();
        },
        "paginated.per_page": function (newVal, oldVal) {
            if (Number(newVal) !== oldVal) {
                this.paginated.current_page = 1;
                this.setQuery();
            }
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.active": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
    },

    methods: {
        setQuery() {
            this.$router.push(
                {
                    name: "show_driver_contracts",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                        active: this.paginated.active,
                    },
                },
                () => {
                    this.paginated.getContracts;
                },
            );
        },
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },
        showCarDialog(car) {
            this.showCar = car;
            this.carDialog = true;
        },
        showTerminateContractDialog(contract) {
            this.terminateContract = contract;
            this.terminateDialog = true;
        },
        closeTerminateDialog() {
            this.terminateContract = undefined;
            this.terminateDialog = false;
            this.terminatePassword = null;
        },
        terminate() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.terminateLoading = true;
                    axios
                        .post("driver-contracts/terminate", {
                            contract: this.terminateContract.driver_contract_id,
                            password: this.terminatePassword,
                        })
                        .then(response => {
                            this.terminateLoading = false;
                            Snackbar.info(response.data.message);
                            this.paginated.getContracts;
                            this.closeTerminateDialog();
                        })
                        .catch(error => {
                            this.terminateLoading = false;
                            Form.errors(error).forEach(error => this.errors.add(error));
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        downloadContract(contract_id) {
            this.paginated.downloadContract(contract_id);
        },
        editContractPrice(driver) {
            axios.post(`driver-contracts/update/contract_price`,{
                driver_contract_id: driver.driver_contract_id,
                free_days_price: driver.free_days_price,
                busy_days_price: driver.busy_days_price,
            }).then((response) => {
                Snackbar.info(response.data.message);
            });
        }
    },

    created() {
        this.paginated.getContracts;
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
