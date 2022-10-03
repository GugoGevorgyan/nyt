<!-- @format -->

<template>
    <div :style="{ height: height + 'px' }">
        <div :id="'order-history-map-' + order.order_id" style="width: 100%" :style="{ height: height + 'px' }"></div>
    </div>
</template>
<script>
export default {
    name: "InfoMap",

    props: {
        height: {
            required: true,
        },
        order: {
            required: true,
        },
        history: {
            required: true,
        },

        board: {
            required: true,
        },
        orderProgress: {
            required: true,
        },
        center: {
            required: true,
        },
        inProcess: {
            required: true,
        },
    },

    data() {
        return {
            map: null,

            realProcessColor: "#00C853",
            realOnWayColor: "#1565C0",
            predictionProcessColor: "#FFD600",

            attach: [],
            shipped: [],
            process_road: null,
            way_road: null,
            stages: null,
            completed: null,

            geoObjects: null,
            myGeoObjects: [],
            oldOrderProgress: null,
        };
    },

    watch: {
        center() {
            if (this.center) {
                this.setCenter(this.center);
            }
        },
        orderProgress() {
            this.setOrderProgress();
        },
        history: {
            deep: true,
            handler() {
                this.setValues();
            },
        },
    },

    methods: {
        /*map*/
        formatCoords(coords) {
            let result = [];
            coords.forEach(item => {
                result.push([item.lat, item.lut]);
            });
            return result;
        },
        init() {
            this.map = new ymaps.Map("order-history-map-" + this.order.order_id, {
                center: [55.73, 37.75],
                zoom: 10,
                controls: [],
            });
            this.geoObjects = new ymaps.GeoObjectCollection();
            this.map.geoObjects.add(this.geoObjects);
        },

        setRoutes() {
            if (this.map) {
                if (this.way_road || this.process_road) {
                    if (this.way_road) {
                        let onWay = new ymaps.Polyline(
                            this.formatCoords(this.way_road.real_road),
                            {},
                            {
                                // The line color.
                                strokeColor: this.realOnWayColor,
                                // Line width.
                                strokeWidth: 7,
                                // The transparency coefficient.
                                strokeOpacity: 0.5,
                            },
                        );
                        this.map.geoObjects.add(onWay);
                    }

                    if (this.process_road) {
                        let real = new ymaps.Polyline(
                            this.formatCoords(this.process_road.real_road),
                            {},
                            {
                                // The line color.
                                strokeColor: this.realProcessColor,
                                // Line width.
                                strokeWidth: 7,
                                // The transparency coefficient.
                                strokeOpacity: 0.5,
                            },
                        );
                        this.map.geoObjects.add(real);

                        let prediction = new ymaps.Polyline(
                            this.formatCoords(this.process_road.route),
                            {},
                            {
                                // The line color.
                                strokeColor: this.predictionProcessColor,
                                // Line width.
                                strokeWidth: 7,
                                // The transparency coefficient.
                                strokeOpacity: 0.5,
                            },
                        );
                        this.map.geoObjects.add(prediction);
                    } else {
                        this.setPendingRoute();
                    }
                } else {
                    this.setPendingRoute();
                }
            }
        },
        setPendingRoute() {
            // Get targets
            let targets = [];
            this.order.from_coordinates
                ? targets.push(this.order.from_coordinates.lat + " " + this.order.from_coordinates.lut)
                : null;
            this.order.to_coordinates
                ? targets.push(this.order.to_coordinates.lat + " " + this.order.to_coordinates.lut)
                : null;

            // Create a route.
            let route = new ymaps.multiRouter.MultiRoute(
                {
                    referencePoints: targets,
                },
                {
                    // Appearance of waypoints.
                    wayPointStartIconColor: "#FFFFFF",
                    wayPointStartIconFillColor: this.predictionProcessColor,
                    // Appearance of the active route path.
                    routeActiveStrokeWidth: 7,
                    routeActiveStrokeStyle: "solid",
                    routeActiveStrokeColor: this.predictionProcessColor,
                    // Appearance of alternative route paths.
                    routeStrokeStyle: "dot",
                    routeStrokeWidth: targets.length,
                    boundsAutoApply: true,
                },
            );
            // // Add the route to the map.
            this.map.geoObjects.add(route);
        },
        pointHtml(text, color) {
            return `<div style="
                                color: #FFFFFF;
                                background-color: ${color};
                                border: 2px solid white;
                                box-shadow: 0 8px 11px -5px rgba(0,0,0,.2),0 17px 26px 2px rgba(0,0,0,.14),0 6px 32px 5px rgba(0,0,0,.12)!important;
                                border-radius: 50%;
                                width: 20px; height: 20px;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                transform: translate(-50%, -50%)">
                       <small>${text}</small>
                    </div>`;
        },
        setPoints() {
            if (this.stages) {
                let center = [];

                if (this.stages.accepted) {
                    center.push(this.stages.accept);
                }
                if (this.stages.ended) {
                    center.push(this.stages.end);
                }
                if (this.stages.started && !this.stages.ended) {
                    center.push(this.stages.start);
                }
                if (this.stages.started) {
                    let p = new ymaps.Placemark(
                        this.formatCoords([this.stages.start])[0],
                        {
                            balloonContentBody: `<span>Начат</span>`,
                        },
                        {
                            iconLayout: ymaps.templateLayoutFactory.createClass(
                                this.pointHtml("A", this.realProcessColor),
                            ),
                            iconShape: {
                                coordinates: [12, 12],
                                type: "Circle",
                                radius: 25,
                            },
                        },
                    );
                    this.map.geoObjects.add(p);
                }
                if (this.stages.accepted) {
                    let p = new ymaps.Placemark(
                        this.formatCoords([this.stages.accept])[0],
                        {
                            balloonContentBody: `<span>Принят</span>`,
                        },
                        {
                            iconLayout: ymaps.templateLayoutFactory.createClass(
                                this.pointHtml("✓", this.realOnWayColor),
                            ),
                            iconShape: {
                                coordinates: [12, 12],
                                type: "Circle",
                                radius: 25,
                            },
                        },
                    );
                    this.map.geoObjects.add(p);
                }
                if (this.stages.ended) {
                    let p = new ymaps.Placemark(
                        this.formatCoords([this.stages.end])[0],
                        {
                            balloonContentBody: `<span>Завершен</span>`,
                        },
                        {
                            iconLayout: ymaps.templateLayoutFactory.createClass(
                                this.pointHtml("B", this.realProcessColor),
                            ),
                            iconShape: {
                                coordinates: [12, 12],
                                type: "Circle",
                                radius: 25,
                            },
                        },
                    );
                    this.map.geoObjects.add(p);
                }
                if (this.stages.on_wayed) {
                    if (
                        this.stages.on_way.lat !== this.stages.accept.lat ||
                        this.stages.on_way.lut !== this.stages.accept.lut
                    ) {
                        let p = new ymaps.Placemark(
                            this.formatCoords([this.stages.on_way])[0],
                            {
                                balloonContentBody: `<span>В Пути</span>`,
                            },
                            {
                                preset: "islands#redDotIcon",
                            },
                        );
                        this.map.geoObjects.add(p);
                    }
                }
                if (this.stages.in_placed) {
                    if (
                        this.stages.in_place.lat !== this.stages.start.lat ||
                        this.stages.in_place.lut !== this.stages.start.lut
                    ) {
                        let p = new ymaps.Placemark(
                            this.formatCoords([this.stages.in_place])[0],
                            {
                                balloonContentBody: `<span>На месте</span>`,
                            },
                            {
                                preset: "islands#redDotIcon",
                            },
                        );
                        this.map.geoObjects.add(p);
                    }
                }
                if (this.inProcess) {
                    let layout = ymaps.templateLayoutFactory.createClass(
                        `<img style="width: 40px; transform: translate(-50%, -50%) rotate(${this.board.driver.azimuth}deg);" src="/storage/img/taxi/map-car.png">`,
                    );
                    let balloon = `<span>
                                        Водитель: ${this.board.driver.driver_info.surname} ${this.board.driver.driver_info.name} ${this.board.driver.driver_info.patronymic}
                                   </span>`;

                    let p = new ymaps.Placemark(
                        [this.board.driver.lat, this.board.driver.lut],
                        {
                            balloonContentBody: balloon,
                        },
                        {
                            iconLayout: layout,
                        },
                    );
                    this.map.geoObjects.add(p);
                }

                let b = new ymaps.Placemark(
                    this.formatCoords([this.order.to_coordinates])[0],
                    {
                        balloonContentBody: `<span>Назначенная точка завершения</span>`,
                    },
                    {
                        iconLayout: ymaps.templateLayoutFactory.createClass(
                            this.pointHtml("B", this.predictionProcessColor),
                        ),
                        iconShape: {
                            coordinates: [12, 12],
                            type: "Circle",
                            radius: 25,
                        },
                    },
                );
                this.map.geoObjects.add(b);

                if (center.length && !this.orderProgress) {
                    this.setCenter(center);
                }
            }
        },
        setOrderProgress() {
            if (null !== this.orderProgress) {
                let progress = this.process_road.real_road[this.orderProgress];
                if (this.oldOrderProgress) {
                    this.map.geoObjects.remove(this.oldOrderProgress);
                }
                let p = new ymaps.Placemark(
                    this.formatCoords([progress])[0],
                    {
                        balloonContentBody: `<span>${this.timeFormat(progress.date)}</span>`,
                    },
                    {
                        iconLayout: ymaps.templateLayoutFactory.createClass(
                            this.pointHtml(
                                `<img style="height: 20px; width: 20px;" src="/storage/img/taxi/map-car.png">`,
                                "#BDBDBD",
                            ),
                        ),
                        iconShape: {
                            coordinates: [12, 12],
                            type: "Circle",
                            radius: 25,
                        },
                    },
                );
                this.map.geoObjects.add(p);
                this.oldOrderProgress = p;
                this.setCenter([this.process_road.real_road[this.orderProgress]]);
            }
        },
        setCenter(coords) {
            if (1 === coords.length) {
                this.map.setCenter(this.formatCoords([coords[0]])[0]);
            } else {
                let center = [];
                center.push((coords[0].lat + coords[1].lat) / 2);
                center.push((coords[0].lut + coords[1].lut) / 2);
                this.map.setCenter(center);
            }
        },
        refreshGeoObjects() {
            this.map.geoObjects.removeAll();
            this.setRoutes();
            this.setPoints();
            this.setOrderProgress();
        },
        /*___________*/

        timeFormat(date) {
            return this.$moment(new Date(date)).format("HH:mm");
        },
        setValues() {
            this.attach = this.history.attach;
            this.shipped = this.history.shipped;
            this.stages = this.history.stages;
            this.completed = this.history.completed;
            this.process_road = this.history.process_road;
            this.way_road = this.history.way_road;

            this.refreshGeoObjects();
        },
    },

    mounted() {
        this.init();
        this.setValues();
    },
};
</script>
