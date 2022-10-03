/** @format */

import OrderDialog from "../../order/form/OrderDialog";
import Employee from "../form/Employee";
import MyAddresses from "../MyAddresses";
import Address from "../Address";
import CorporateClientsPagination from "../../../forms/EmployeePagination";

export default {
    name: "EmployeesList",

    components: { Employee, MyAddresses, Address, OrderDialog },

    data() {
        return {
            /*pagination*/
            paginated: new CorporateClientsPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: this.$route.query["per-page"],
                    sort_by: this.$route.query["sort-by"],
                    sort_desc: this.setSortDesc(this.$route.query["sort-desc"]),
                },
                "/admin/corporate/company/clients",
            ),

            window: {
                width: 0,
                height: window.innerHeight - 250,
            },

            /*order*/
            orderDialog: false,
            orderEmployee: undefined,

            /*employee*/
            employeeDialog: false,
            employeeObj: undefined,

            /*address*/
            addressDialog: false,
            step: 1,
            addressEmployee: undefined,
            editingAddress: undefined,
        };
    },

    computed: {
        broadcast: {
            get() {
                return this.$store.state.broadcast;
            },
            set(val) {
                this.$store.state.broadcast = val;
            },
        },
        phoneMask() {
            return this.$store.state.phoneMask;
        },
    },

    methods: {
        /*window*/
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 250;
        },

        setQuery() {
            this.$router.push(
                {
                    name: "",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                        "sort-by": this.paginated.sort_by,
                        "sort-desc": this.paginated.sort_desc,
                        active: this.paginated.active,
                    },
                },
                () => {
                    this.paginated.getData;
                },
            );
        },

        setSortDesc(sort) {
            return Array.isArray(sort) ? sort : Boolean(sort);
        },

        employeeActionDone() {
            this.closeEmployeeDialog();
            this.paginated.getData;
        },

        deleteEmployee(employee) {
            this.paginated.loading = true;
            let index = this.paginated.data.indexOf(employee);

            confirm("Are you sure you want to delete this employee?") &&
                this.$http
                    .post("/admin/corporate/company/client/delete", {
                        ids: [this.paginated.data[index].corporate_client_id],
                        _method: "DELETE",
                    })
                    .then(response => {
                        this.paginated.loading = false;
                        this.paginated.getData;
                    })
                    .catch(error => {
                        this.paginated.loading = false;
                    });
            this.paginated.loading = false;
        },

        deleteEmployees() {
            this.paginated.loading = true;
            const ids = this.paginated.selected.map(employee => {
                return employee.corporate_client_id;
            });
            confirm(`Are you sure you want to delete ${ids.length} employees?`) &&
                this.$http
                    .post("/admin/corporate/company/client/delete", {
                        ids,
                        _method: "DELETE",
                    })
                    .then(response => {
                        this.paginated.loading = false;
                        this.paginated.getData;
                    })
                    .catch(error => {
                        this.paginated.loading = false;
                    });
            this.paginated.loading = false;
        },

        showOrderDialog(employee, dialog) {
            this.orderEmployee = employee;
            this[dialog] = employee.allow_order ? true : false;
        },

        closeOrderDialog(dialog) {
            this.orderEmployee = undefined;
            this[dialog] = false;
        },

        showEmployeeDialog(employee) {
            this.employeeDialog = true;
            this.employeeObj = employee.client_id ? employee : undefined;
        },

        closeEmployeeDialog() {
            this.employeeDialog = false;
            this.employeeObj = undefined;
        },

        updateAddress(addressObj) {
            this.paginated.data.map(item => {
                if (item.client_id === Number(addressObj.client_id)) {
                    let index = item.client_addresses.findIndex(
                        address => address.client_address_id === addressObj.client_address_id,
                    );
                    item.client_addresses.splice(index, 1, addressObj);
                    item.client_addresses.push({ ...addressObj });
                    item.client_addresses.pop();

                }
            });
        },

        addAddress(addressObj) {
            this.paginated.data.map(item => {
                if (item.client_id === Number(addressObj.client_id)) {
                    item.client_addresses.push({ ...addressObj });
                }
            });
        },

        deleteAddress(addressObj) {
            this.paginated.data.map(item => {
                if (item.client_id === Number(addressObj.client_id)) {
                    let index = item.client_addresses.findIndex(
                        address => address.client_address_id === addressObj.client_address_id,
                    );
                    item.client_addresses.splice(index, 1);
                }
            });
        },

        addressDialogBack() {
            this.editingAddress = undefined;
            this.step = 1;
        },

        getUsingPhoneAccordinglyPhoneMask(phone) {
            // One element is '+' in code; Example '+374';
            return phone.substr(this.phoneMask.indexOf('(') - 1)
        }
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
        "paginated.sort_by": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
        "paginated.sort_desc": function () {
            this.paginated.current_page = 1;
            this.setQuery();
        },
    },

    mounted() {
        this.broadcast.listen("CreateOrder", payload => {});
    },

    created() {
        this.$store.dispatch('getCompanyPhoneMask').then()
        window.addEventListener("resize", this.handleResize);
        this.paginated.getData;
    },
};
