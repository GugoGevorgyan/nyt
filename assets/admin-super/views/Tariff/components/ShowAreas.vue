<!-- @format -->

<template>
    <div id="map" style="width: 100%; height: 100%"></div>
</template>
<script>
export default {
    props: ['areas'],
    name: 'ShowAreas',
    data() {
        return {
            targets: [],
            map: undefined,
            route: undefined,
        };
    },
    watch: {
        areas: function () {
            this.targets = [];
            if (this.route) {
                this.removeRoute();
            }
            this.setAreas();
        },
        targets: function () {
            if (this.targets.length > 1) {
                this.setRoute();
            }
        },
    },
    methods: {
        init() {
            this.map = new ymaps.Map('map', {
                center: [55.73, 37.75],
                zoom: 10,
                controls: [],
            });

            this.setAreas();
        },

        setRoute() {
            let self = this;
            // Create a route.
            this.route = new ymaps.multiRouter.MultiRoute(
                {
                    referencePoints: self.targets,
                },
                {
                    // Appearance of waypoints.
                    wayPointStartIconColor: '#FFFFFF',
                    wayPointStartIconFillColor: '#B3B3B3',
                    // Appearance of the active route path.
                    routeActiveStrokeWidth: 8,
                    routeActiveStrokeStyle: 'solid',
                    routeActiveStrokeColor: '#002233',
                    // Appearance of alternative route paths.
                    routeStrokeStyle: 'dot',
                    routeStrokeWidth: self.targets.length,
                    boundsAutoApply: true,
                },
            );
            // // Add the route to the map.
            this.map.geoObjects.add(self.route);
        },

        removeRoute() {
            this.map.geoObjects.remove(this.route);
        },

        getAverageNumeral(array) {
            let sum = array.reduce((a, b) => a + b, 0);
            return sum / array.length;
        },

        getLatLongs() {
            if (this.areas.length) {
                this.areas.forEach(item => {
                    if (item.length) {
                        let lats = [];
                        let longs = [];
                        item.forEach(coords => {
                            lats.push(coords[0]);
                            longs.push(coords[1]);
                        });

                        let target = [this.getAverageNumeral(lats), this.getAverageNumeral(longs)];
                        this.targets.push(target);
                    }
                });
            }
        },

        setAreas() {
            if (this.map) {
                let geoObject = new ymaps.Polygon(this.areas);
                this.map.geoObjects.remove(this.map.geoObjects.get(0));
                this.map.geoObjects.add(geoObject);
                this.getLatLongs();
            }
        },
    },
    created() {
        ymaps.ready(this.init);
    },
};
</script>
