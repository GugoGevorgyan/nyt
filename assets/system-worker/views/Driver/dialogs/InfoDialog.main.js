/** @format */

import ImagesDialog from './../../../components/Complaint/FilesShowDialog';
import moment from 'moment';
import axios from 'axios';
import Snackbar from '../../../facades/Snackbar';
import DatePicker from "../../../../shared/components/form/DatePicker";

export default {
    name: "InfoDialog",

    components: { "images-dialog": ImagesDialog, DatePicker },

    props: {
        driver: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            carImages: [],
            toggleInfo: 0,
            noImage: "/storage/img/camera.png",
            maps: null,
            distance: 0,
            roadDate: moment().format("YYYY-MM-DD"),
            menu1: false,
            loadCord: false,
            window: {
                width: 0,
                height: 0,
                heightDif: 200,
            },
            password: '',
            rules: {
                driver: {
                    rating: 'required|max_value:999',
                    mean_assessment: 'required|max_value:5',
                    phone: 'required',
                    password: 'min:6',
                    nickname: 'required'
                },
                driver_info: {
                    name: 'required|alpha',
                    surname: 'required|alpha',
                    patronymic: 'alpha',
                    citizen: 'required',
                    birthday: 'required',
                    address: 'required',
                    email: 'required|email',
                }
            },
        };
    },

    computed: {
        phoneMask() {
            return this.$store.state.phoneMask;
        },
        maxDate() {
            return new Date().toISOString();
        },
        minDate() {
            Date.prototype.subDays = function (days) {
                let date = new Date(this.valueOf());
                date.setDate(date.getDate() - days);
                return date;
            };

            return new Date().subDays(30).toISOString();
        },
        date_18_years_ago() {
            let date = new Date;
            date.setFullYear(date.getFullYear() - 18);

            return date.toISOString().slice(0,10);
        },
        mean_assessment: {
            get() {
                return this.driver.mean_assessment;
            },
            set(newValue) {
                if (newValue !== '') {
                    this.driver.mean_assessment = parseInt(newValue);
                }
            }
        },
    },

    watch: {
        roadDate(val) {
            this.getCord();
        },
    },

    methods: {
        init() {
            this.maps = new ymaps.Map(
                "driver-info-map",
                {
                    center: [40.203058, 44.499646],
                    zoom: 12,
                    controls: [],
                    behaviors: ["scrollZoom", "drag", "multiTouch"],
                },
                {
                    minZoom: 4,
                },
            );

            let zoomControl = new ymaps.control.ZoomControl({
                options: {
                    layout: "round#zoomLayout",
                    maxWidth: "large",
                    size: "large",
                    position: {
                        right: 10,
                        top: 250,
                    },
                },
            });
            let secondButton = new ymaps.control.Button({
                data: {
                    content: "Reload",
                    title: "Получить последние данные",
                },
                options: {
                    maxWidth: [28, 150, 178],
                    selectOnClick: false,
                    position: {
                        right: 10,
                        top: 10,
                    },
                },
            });

            this.maps.controls.add(zoomControl);
            this.maps.controls.add(secondButton);

            secondButton.events.add("click", () => {
                this.getCord();
            });
        },

        toggleInfoEvent() {
            if (0 === this.toggleInfo) {
                this.toggleInfo = 1;
            } else {
                this.toggleInfo = 0;
            }
        },

        formatCoords(coords) {
            let result = [];

            for (let [key, value] of Object.entries(coords)) {
                result.push([value.lat, value.lut]);
            }

            return result;
        },

        getCord() {
            this.loadCord = true;
            this.$http
                .get(`drivers/dr_road_by_date/${this.driver.driver_id}/${this.roadDate}`)
                .then(response => {
                    let coordinates = response.data.data ? this.formatCoords(response.data.data) : [];
                    if (coordinates.length) {
                        let centerCord = coordinates[Math.round((coordinates.length - 1) / 2)];
                        this.distance = response.data.distance;
                        this.maps.setCenter(centerCord);

                        let road = new ymaps.Polyline(
                            coordinates,
                            { hintContent: "Траектория" },
                            {
                                strokeColor: "#00C853",
                                strokeWidth: 5,
                                strokeOpacity: 0.5,
                            },
                        );
                        this.maps.geoObjects.add(road);
                    } else {
                        this.maps.geoObjects.removeAll();
                    }

                    this.loadCord = false;
                })
                .catch(error => {
                    this.loadCord = false;
                });
        },
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },
        editDriver(driver) {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    axios.post(`drivers/update_driver/${driver.driver_id}/${driver.driver_info.driver_info_id}`,{
                        driver: {
                            phone: driver.phone,
                            rating: driver.rating,
                            mean_assessment: driver.mean_assessment,
                            nickname: driver.nickname,
                            password: this.password,
                        },
                        driver_info: {
                            surname: driver.driver_info.surname,
                            name: driver.driver_info.name,
                            patronymic: driver.driver_info.patronymic,
                            email: driver.driver_info.email,
                            birthday: driver.driver_info.birthday,
                            address: driver.driver_info.address,
                            citizen: driver.driver_info.citizen,
                        }
                    }).then((response) => {
                        Snackbar.info(response.data.message)
                    }).catch(error => {
                        Snackbar.error(error.response.data.message)
                    })
                }
            })

        }
    },

    created() {
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
        ymaps.ready(this.init);
        this.getCord();
    },
};
