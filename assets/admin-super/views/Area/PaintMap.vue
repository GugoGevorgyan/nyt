<!-- @format -->

<template>
    <div>
        <v-row no-gutters>
            <v-col cols="12" md="10">
                <v-alert dense outlined type="info" height='36px'>
                    Чтобы включить режим рисования, нужно нажать левую кнопку мыши, зажав клавишу "alt".
                </v-alert>
            </v-col>
            <v-col cols="12" md="2" class="pl-2">
                <v-btn @click="clearArea()" outlined tile :disabled="!coordinates.length" style="width: 100%" color="error">очистить</v-btn>
            </v-col>
        </v-row>

        <div id="paint-map" style="width: 100%; height: 600px"></div>
    </div>
</template>
<script>
export default {
    name: 'PaintMap',

    props: ['areaCoords'],

    data() {
        return {
            map: undefined,
            coordinates: this.areaCoords,
        };
    },

    watch: {
        coordinates() {
            this.$emit('updateCoordinates', this.coordinates);
        },

        areaCoords() {
            if (this.areaCoords !== this.coordinates) {
                this.coordinates = this.areaCoords;
                this.setArea();
            }
        },
    },

    methods: {
        init() {
            this.map = new ymaps.Map('paint-map', {
                center: [55.73, 37.75],
                zoom: 10,
                controls: [],
            });
            this.setArea();
            this.enablePainting();
        },

        getCenter() {
            let lats = [];
            let longs = [];
            this.coordinates.forEach(coords => {
                lats.push(Number(coords[0]));
                longs.push(Number(coords[1]));
            });

            return [this.getAverageNumeral(lats), this.getAverageNumeral(longs)];
        },

        getAverageNumeral(array) {
            let sum = array.reduce((a, b) => a + b, 0);
            return sum / array.length;
        },

        setArea() {
            let geoObject = new ymaps.Polygon([this.coordinates]);
            this.map.geoObjects.remove(this.map.geoObjects.get(0));
            this.map.geoObjects.add(geoObject);
            this.coordinates.length ? this.map.setCenter(this.getCenter()) : null;
        },

        clearArea() {
            this.map.geoObjects.remove(this.map.geoObjects.get(0));
            this.coordinates = [];
        },

        enablePainting() {
            const self = this;
            ymaps
                .ready(['ext.paintOnMap'])
                .then(function () {
                    let paintProcess;

                    let styles = [
                        {
                            strokeColor: '#1371FE',
                            strokeOpacity: 0.7,
                            strokeWidth: 2,
                            fillColor: '#1371FE',
                            fillOpacity: 0.4,
                        },
                    ];

                    let currentIndex = 0;

                    self.map.events.add('mousedown', function (e) {
                        if (e.get('altKey')) {
                            if (currentIndex === styles.length - 1) {
                                currentIndex = 0;
                            } else {
                                currentIndex += 1;
                            }
                            paintProcess = ymaps.ext.paintOnMap(self.map, e, { style: styles[currentIndex] });
                        }
                    });

                    self.map.events.add('mouseup', function (e) {
                        if (paintProcess) {
                            let coordinates = paintProcess.finishPaintingAt(e);
                            paintProcess = null;
                            self.coordinates = coordinates;
                            self.setArea();
                        }
                    });
                })
                .catch(console.error);
        },
    },

    mounted() {
        ymaps.modules.define(
            'ext.paintOnMap',
            ['meta', 'util.extend', 'pane.EventsPane', 'Event'],
            function (provide, meta, extend, EventsPane, Event) {
                'use strict';
                let EVENTS_PANE_ZINDEX = 500;

                let DEFAULT_UNWANTED_BEHAVIORS = ['drag', 'scrollZoom'];
                let DEFAULT_STYLE = { strokeColor: '#0000ff', strokeWidth: 1, strokeOpacity: 1 };
                let DEFAULT_TOLERANCE = 16;

                let badFinishPaintingCall = function () {
                    throw new Error('(ymaps.ext.paintOnMap) некорректный вызов PaintingProcess#finishPaintingAt. Рисование уже завершено.');
                };

                function paintOnMap(map, positionOrEvent, config) {
                    config = config || {};
                    let style = extend(DEFAULT_STYLE, config.style || {});

                    let unwantedBehaviors = config.unwantedBehaviors === undefined ? DEFAULT_UNWANTED_BEHAVIORS : config.unwantedBehaviors;

                    let pane = new EventsPane(map, {
                        css: { position: 'absolute', width: '100%', height: '100%' },
                        zIndex: EVENTS_PANE_ZINDEX + 50,
                        transparent: true,
                    });

                    map.panes.append('ext-paint-on-map', pane);

                    if (unwantedBehaviors) {
                        map.behaviors.disable(unwantedBehaviors);
                    }

                    let canvas = document.createElement('canvas');
                    let ctx2d = canvas.getContext('2d');
                    let rect = map.container.getParentElement().getBoundingClientRect();
                    canvas.width = rect.width;
                    canvas.height = rect.height;

                    ctx2d.globalAlpha = style.strokeOpacity;
                    ctx2d.strokeStyle = style.strokeColor;
                    ctx2d.lineWidth = style.strokeWidth;

                    canvas.style.width = '100%';
                    canvas.style.height = '100%';

                    pane.getElement().appendChild(canvas);

                    let firstPosition = positionOrEvent ? toPosition(positionOrEvent) : null;
                    let coordinates = firstPosition ? [firstPosition] : [];

                    let bounds = map.getBounds();
                    let latDiff = bounds[1][0] - bounds[0][0];
                    let lonDiff = bounds[1][1] - bounds[0][1];

                    canvas.onmousemove = function (e) {
                        coordinates.push([e.offsetX, e.offsetY]);

                        ctx2d.clearRect(0, 0, canvas.width, canvas.height);
                        ctx2d.beginPath();

                        ctx2d.moveTo(coordinates[0][0], coordinates[0][1]);
                        for (let i = 1; i < coordinates.length; i++) {
                            ctx2d.lineTo(coordinates[i][0], coordinates[i][1]);
                        }

                        ctx2d.stroke();
                    }.bind(this);

                    let paintingProcess = {
                        finishPaintingAt: function (positionOrEvent) {
                            paintingProcess.finishPaintingAt = badFinishPaintingCall;

                            if (positionOrEvent) {
                                coordinates.push(toPosition(positionOrEvent));
                            }

                            map.panes.remove(pane);
                            if (unwantedBehaviors) {
                                map.behaviors.enable(unwantedBehaviors);
                            }

                            let tolerance = config.tolerance === undefined ? DEFAULT_TOLERANCE : Number(config.tolerance);
                            if (tolerance) {
                                coordinates = simplify(coordinates, tolerance);
                            }
                            return coordinates.map(function (x) {
                                let lon = bounds[0][1] + (x[0] / canvas.width) * lonDiff;
                                let lat = bounds[0][0] + (1 - x[1] / canvas.height) * latDiff;

                                return 'latlong' === meta.coordinatesOrder ? [lat, lon] : [lon, lat];
                            });
                        },
                    };

                    return paintingProcess;
                }

                function toPosition(positionOrEvent) {
                    return positionOrEvent instanceof Event
                        ? [positionOrEvent.get('offsetX'), positionOrEvent.get('offsetY')]
                        : positionOrEvent;
                }

                function simplify(coordinates, tolerance) {
                    let toleranceSquared = tolerance * tolerance;
                    let simplified = [coordinates[0]];

                    let prev = coordinates[0];
                    for (let i = 1; i < coordinates.length; i++) {
                        let curr = coordinates[i];
                        if (Math.pow(prev[0] - curr[0], 2) + Math.pow(prev[1] - curr[1], 2) > toleranceSquared) {
                            simplified.push(curr);
                            prev = curr;
                        }
                    }

                    return simplified;
                }

                provide(paintOnMap);
            },
        );
    },

    created() {
        ymaps.ready(this.init);
    },
};
</script>
