/** @format */

import axios from "axios";
import Schedule from "../../forms/Schedule";
import Snackbar from "../../facades/Snackbar";

export default {
    name: "Schedule",

    props: ["parks", "driverTypes", "scheduleTypes"],

    data() {
        return {
            window: {
                width: 0,
                height: 0,
            },
            heightDif: 175,
            hoverDay: null,
            dayLoading: false,
            dayMenus: {},

            searchText: this.$route.query["search"],
            paginated: new Schedule({
                current_page: Number(this.$route.query.page),
                per_page: Number(this.$route.query["per-page"]),
                search: this.$route.query.search,
                park: Number(this.$route.query.park),
                year: Number(this.$route.query.year) || new Date().getFullYear(),
                month: Number(this.$route.query.month) || new Date().getMonth() + 1,
                driver_type: Number(this.$route.query["driver-type"]) || null,
                schedule_type: Number(this.$route.query["schedule-type"]) || null,
                path: "schedule/paginate",
            }),

            months: [
                { text: "Январь", value: 1 },
                { text: "Февраль", value: 2 },
                { text: "Март", value: 3 },
                { text: "Апрель", value: 4 },
                { text: "Май", value: 5 },
                { text: "Июнь", value: 6 },
                { text: "Июль", value: 7 },
                { text: "Август", value: 8 },
                { text: "Сентябрь", value: 9 },
                { text: "Октябрь", value: 10 },
                { text: "Ноябрь", value: 11 },
                { text: "Декабрь", value: 12 },
            ],
            weekDays: [
                { text: "Воскресенье", shorted: "Вс.", value: 0, background: "#FF8A80", color: "#FFFFFF" },
                { text: "Понедельник", shorted: "Пн.", value: 1, background: "#FFFFFF", color: "#616161" },
                { text: "Вторник", shorted: "Вт.", value: 2, background: "#FFFFFF", color: "#616161" },
                { text: "Среда", shorted: "Ср.", value: 3, background: "#FFFFFF", color: "#616161" },
                { text: "Четверг", shorted: "Чт.", value: 4, background: "#FFFFFF", color: "#616161" },
                { text: "Пятница", shorted: "Пт.", value: 5, background: "#FFFFFF", color: "#616161" },
                { text: "Суббота", shorted: "Сб.", value: 6, background: "#FF8A80", color: "#FFFFFF" },
            ],
        };
    },

    watch: {
        "paginated.current_page": function () {
            this.updatePaginated();
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.updatePaginated();
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.updatePaginated();
        },
        "paginated.park": function () {
            this.paginated.current_page = 1;
            this.updatePaginated();
        },
        "paginated.year": function () {
            this.paginated.current_page = 1;
            this.updatePaginated();
        },
        "paginated.month": function () {
            this.paginated.current_page = 1;
            this.updatePaginated();
        },
        "paginated.driver_type": function () {
            this.paginated.current_page = 1;
            this.updatePaginated();
        },
        "paginated.schedule_type": function () {
            this.paginated.current_page = 1;
            this.updatePaginated();
        },

        "paginated.data": function () {
            this.setDayMenus();
        },

        days() {
            this.setDayMenus();
        },

        searchText() {
            if (!this.searchText) {
                this.search();
            }
        },
    },

    computed: {
        years() {
            let arr = [];
            for (let i = 2020; i < 2051; i++) {
                arr.push(i);
            }
            return arr;
        },

        days() {
            return new Date(this.paginated.year, this.paginated.month, 0).getDate();
        },

        month() {
            return this.months.find(item => item.value === this.paginated.month);
        },

        url() {
            return this.$store.state.initUrl;
        },
    },

    methods: {
        setDayMenus() {
            let menus = {};
            this.paginated.data.forEach(driver => {
                menus[driver.driver_id] = {};
                for (let i = 1; i <= this.days; i++) {
                    menus[driver.driver_id][i] = false;
                }
            });
            this.dayMenus = menus;
        },

        closeDayMenu(driver_id, day) {
            this.dayMenus[driver_id][day] = false;
        },

        updatePaginated() {
            this.$router.push(
                {
                    name: "get_schedule_info",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search || undefined,
                        year: this.paginated.year || undefined,
                        month: this.paginated.month || undefined,
                        park: this.paginated.park || undefined,
                        "driver-type": this.paginated.driver_type || undefined,
                        "schedule-type": this.paginated.schedule_type || undefined,
                    },
                },
                () => {
                    this.paginated.getData;
                },
            );
        },

        search() {
            this.paginated.search === this.searchText
                ? this.paginated.getData
                : (this.paginated.search = this.searchText);
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height =
                window.innerHeight -
                this.$refs.header.clientHeight -
                this.$refs.info.clientHeight -
                this.$refs.pagination.clientHeight -
                this.heightDif;
        },

        getWeekDay(day) {
            let value = new Date(`${this.paginated.year}-${this.paginated.month}-${day}`).getDay();
            return this.weekDays.find(item => item.value === value);
        },

        checkDay(driver, day) {
            let schedule = this.getDaySchedule(driver, day);
            if (schedule) {
                return schedule.working ? "active" : "de-active";
            } else {
                return "no-info";
            }
        },

        getDaySchedule(driver, day) {
            return driver.schedules.find(
                item => item.year === this.paginated.year && item.month === this.paginated.month && item.day === day,
            );
        },

        checkToday(day) {
            let date = new Date();
            return date.getDate() === day &&
                date.getMonth() + 1 === this.paginated.month &&
                date.getFullYear() === this.paginated.year
                ? "today"
                : null;
        },

        isPast(day) {
            let today = new Date();
            let yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);

            return yesterday < new Date(this.paginated.year + "-" + this.paginated.month + "-" + day);
        },

        dayMouseOver(day) {
            // this.hoverDay = day
        },

        dayMouseLeave(day) {
            // if (this.hoverDay === day){
            //     this.hoverDay = null
            // }
        },

        updateDay(driver_id, day) {
            if (10 > day) {
                day = "0" + day;
            }
            let month = 9 >= this.paginated.month ? `0${this.paginated.month}` : this.paginated.month;

            let data = {
                driver_id: driver_id,
                date: this.paginated.year + "-" + month + "-" + day,
            };
            this.dayLoading = true;
            axios
                .post(this.url + "schedule/update", data)
                .then(response => {
                    this.dayLoading = false;
                    Snackbar.info(response.data.message);
                    this.closeDayMenu(driver_id, day);
                    this.paginated.getData;
                })
                .catch(error => {
                    this.dayLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
    },

    mounted() {
        this.handleResize();
    },

    created() {
        window.addEventListener("resize", this.handleResize);
        this.paginated.getData;
    },
};
