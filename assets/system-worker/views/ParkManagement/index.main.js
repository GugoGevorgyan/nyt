/** @format */

import ParkManagementPagination from './../../forms/ParkManagementPagination';
import Snackbar from '../../facades/Snackbar';
import DriverDialog from './components/DriverDialog';
import axios from 'axios';

// noinspection JSUnusedGlobalSymbols
export default {
    components: { DriverDialog },

    props: {
        statuses: {
            required: true,
            type: Array,
        },
        parks: {
            required: true,
            type: Array,
        },
        classes: {
            required: true,
            type: Array,
        }
    },

    data() {
        return {
            driverDialog: false,
            driverDialogData: undefined,
            driverDialogNewCar: false,

            paginated: new ParkManagementPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"]),
                },
                "park-management/paginate",
            ),
            window: {
                width: 0,
                height: 0,
            },
            heightDif: 188,

            freeDrivers: [],
            freeDriversLoading: false,
            freeDriverSearch: null,
            freeDriversMenu: false,

            statusLoading: false,

            pagingTimer: null,
            removeDriverLoading: false
        };
    },

    computed: {
        url() {
            return this.$store.state.initUrl;
        },
        filter_classes() {
            return [{
                class_name: 'Все классы',
                car_class_id: ''
            }].concat(this.classes);
        },
        filter_statuses() {
            return [{
                text: 'Все статусы',
                car_status_id: ''
            }].concat(this.statuses);
        },
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
        },
        search: {
            get (){
                return this.freeDriverSearch;
            },
            set (newVal){
                this.freeDriverSearch = newVal;
            }
        }
    },

    watch: {
        freeDriverSearch: function (val) {
            this.getFreeDrivers(val);
            // if (null !== this.freeDriverSearch) {
            //     this.freeDriverSearch.toLowerCase();
            // }
            //
            // this.freeDrivers.map(item => {
            //     item.name.toLowerCase().indexOf(this.freeDriverSearch) > -1 ||
            //     item.surname.toLowerCase().indexOf(this.freeDriverSearch) > -1 ||
            //     null == this.freeDriverSearch
            //         ? (item.hidden = false)
            //         : (item.hidden = true);
            //     return item;
            // });
        },
        freeDriversMenu: function () {
            if (!this.freeDriversMenu) {
                this.freeDriverSearch = null;
            }
        },
        "paginated.current_page": function () {
            clearTimeout(this.pagingTimer);

            this.pagingTimer = setTimeout(() => {
                this.pagingPush();
            }, 500);
        },
        "paginated.per_page": function () {
            clearTimeout(this.pagingTimer);

            this.pagingTimer = setTimeout(() => {
                this.paginated.current_page = 1;

                this.pagingPush();
            }, 500);
        },
        "paginated.search_attribute": function () {
            clearTimeout(this.pagingTimer);

            this.pagingTimer = setTimeout(() => {
                this.paginated.current_page = 1;

                this.pagingPush();
            }, 500);
        },
        "paginated.search": function () {
            clearTimeout(this.pagingTimer);

            this.pagingTimer = setTimeout(() => {
                this.paginated.current_page = 1;

                this.pagingPush();
            }, 500);
        },
        "paginated.filter_class": function() {
            this.pagingPush();
        },
        "paginated.filter_status": function() {
            this.pagingPush();
        }
    },

    methods: {
        pagingPush(){
            this.$router.push(
                {
                    name: "park_management",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search_attribute: this.paginated.search_attribute,
                        search: this.paginated.search,
                        filter_class: this.paginated.filter_class,
                        filter_status: this.paginated.filter_status
                    },
                },
                () => {
                    this.paginated.getCars;
                },
            );
        },

        commaJoin(arr, keys) {
            if ("object" === typeof keys) {
                return arr
                    .map(item => {
                        let result = [];
                        Object.keys(keys).forEach(key => {
                            let x = [];
                            keys[key].forEach(value => {
                                x.push(item[key][value]);
                            });
                            result.push(x.join(" "));
                        });
                        return result;
                    })
                    .join(", ");
            } else {
                return keys
                    ? arr
                          .map(item => {
                              return item[keys];
                          })
                          .join(", ")
                    : arr.join(", ");
            }
        },

        changeCar(car) {
            this.paginated.data = this.paginated.data.map(function (item) {
                return item.car_id === car.car_id ? car : item;
            });
        },

        changeStatus(car_id, status_id) {
            this.statusLoading = car_id;

            let data = {
                status_id: status_id,
                car_id: car_id,
            };

            axios
                .post(this.url + "park-management/status/update", data)
                .then(response => {
                    this.statusLoading = false;
                    this.paginated.getCars;
                    Snackbar.info(response.data.message);
                })
                .catch(error => {
                    this.statusLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        driverDialogShow(driver, car) {
            this.driverDialog = true;
            this.driverDialogData = driver;
            this.driverDialogNewCar = car;
        },

        driverDialogClose() {
            this.driverDialog = false;
            this.driverDialogData = undefined;
            this.driverDialogNewCar = false;
        },

        driverUpdated() {
            this.getFreeDrivers();
            this.paginated.getCars;
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.heightDif;
        },

        getFreeDrivers(search) {
            this.freeDrivers = [];
            this.freeDriversLoading = true;
            axios
                .post("park-management/free-drivers", { search: search })
                .then(response => {
                    this.freeDrivers = response.data;
                    this.freeDriversLoading = false;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.freeDrivers = [];
                    this.freeDriversLoading = false;
                });
        },
        removeDriverOnCar(driver) {
            this.removeDriverLoading = true;
            axios.post(`park-management/remove_driver/${driver.driver_id}/${driver.car_id}`)
                .then(response => {
                    this.removeDriverLoading = false;
                    Snackbar.info(response.data.message);
                    this.driverDialog = false;
                    this.driverUpdated();
                }).catch(error => {
                Snackbar.error(error.response.data.message);
            });
        },
    },

    created() {
        this.paginated.getCars;
        this.handleResize();
        this.getFreeDrivers();
        window.addEventListener("resize", this.handleResize);
    },
};
