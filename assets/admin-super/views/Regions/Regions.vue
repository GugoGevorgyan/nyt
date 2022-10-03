<!-- @format -->

<template>
    <v-container fluid grid-list-md>
        <v-layout row no-wrap>
            <v-flex xs12>
                <v-card outlined>
                    <v-data-table
                        :headers="paginated.headers"
                        :items="paginated.data"
                        :items-per-page="paginated.per_page"
                        :loading="paginated.loading"
                        fixed-header
                        hide-default-footer
                        item-key="region_id"
                        v-model="paginated.selected"
                        :height="window.height"
                    >
                        <template v-slot:top>
                            <v-toolbar color="white" flat>
                                <v-toolbar-title>Regions</v-toolbar-title>
                                <v-divider class="mx-3" inset vertical/>
                                <v-spacer/>
                                <v-text-field
                                    append-icon="mdi-magnify"
                                    clearable
                                    color="yellow darken-3"
                                    hide-details
                                    label="Search"
                                    single-line
                                    solo
                                    v-model="paginated.search"
                                />
                                <v-spacer/>
                                <!--                                <v-scale-transition>-->
                                <!--                                    <v-btn color="orange" depressed fab icon large @click="setupForm(0)">-->
                                <!--                                        <v-icon>mdi-plus-circle</v-icon>-->
                                <!--                                    </v-btn>-->
                                <!--                                </v-scale-transition>-->
                            </v-toolbar>
                        </template>
                        <template v-slot:item.name="{ item }">
                            <span v-html="item.name"></span>
                        </template>
                        <template v-slot:item.country="{ item }">
                            <v-chip v-if="item.country" color="orange" dark outlined small class="mx-1 my-1">
                                {{ item.country.name }}
                            </v-chip>
                            <v-chip v-else color="error" dark outlined small class="mx-1 my-1"> No Country </v-chip>
                        </template>

                        <!--                        <template v-slot:item.action="{ item }">-->
                        <!--                            &lt;!&ndash;                            <v-btn @click.stop="setupForm(1, item)" icon color="primary" depressed>&ndash;&gt;-->
                        <!--                            &lt;!&ndash;                                <v-icon>mdi-pencil-outline</v-icon>&ndash;&gt;-->
                        <!--                            &lt;!&ndash;                            </v-btn>&ndash;&gt;-->
                        <!--                            <v-btn color="error" icon depressed @click.stop="setupForm(2, item)">-->
                        <!--                                <v-icon>mdi-delete-outline</v-icon>-->
                        <!--                            </v-btn>-->
                        <!--                        </template>-->

                        <template v-slot:footer>
                            <v-divider />
                            <v-layout align-content-ceneter no-wrap row>
                                <v-flex align-self-center xs2>
                                    <!--                                    <v-btn-->
                                    <!--                                        @click="destroyMultiple()"-->
                                    <!--                                        class="ml-5"-->
                                    <!--                                        color="error"-->
                                    <!--                                        depressed-->
                                    <!--                                        fab-->
                                    <!--                                        v-if="paginated.selected.length"-->
                                    <!--                                    >-->
                                    <!--                                        <v-icon>mdi-trash-can-outline</v-icon>-->
                                    <!--                                    </v-btn>-->
                                </v-flex>

                                <!--PAGINATION LAST PAGE-->
                                <v-flex align-self-center xs8>
                                    <div class="text-xs-center">
                                        <v-pagination
                                            :length="paginated.last_page"
                                            :total-visible="7"
                                            circle
                                            color="yellow darken-3"
                                            v-model="paginated.current_page"
                                        />
                                        <p v-if="paginated.total" class="caption mb-0 text-center">
                                            {{ `${paginated.from}-${paginated.to} of ${paginated.total}` }}
                                        </p>
                                    </div>
                                </v-flex>
                                <v-flex align-self-center xs1>
                                    <div class="text-xs-center">
                                        <v-overflow-btn
                                            :items="[10, 15, 25, 50]"
                                            color="yellow darken-3"
                                            hide-details
                                            label="Rows per page"
                                            v-model="paginated.per_page"
                                        />
                                        <p class="caption">rows per page:</p>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>

        <!--        <v-dialog v-model="dialog" v-if="region" max-width="1400" eager persistent>-->
        <!--                    <v-card flat tile height="800" color="yellow darken-3">-->
        <!--                        <v-layout row wrap>-->
        <!--                            <v-flex xs6>-->
        <!--                                <v-card-text id="map-wrapper">-->
        <!--                                    <div id="map"></div>-->

        <!--                                    <v-toolbar dense floating class="menu-activator">-->
        <!--                                        <v-autocomplete-->
        <!--                                            v-model="region.country_id"-->
        <!--                                            :items="countries"-->
        <!--                                            item-value="country_id"-->
        <!--                                            item-text="name"-->
        <!--                                            placeholder="Choose country"-->
        <!--                                            color="yellow darken-3"-->
        <!--                                            autocomplete="off"-->
        <!--                                            label="Country"-->
        <!--                                            key="country_id"-->
        <!--                                            hide-details-->
        <!--                                            single-line-->
        <!--                                            clearable-->
        <!--                                        ></v-autocomplete>-->
        <!--                                    </v-toolbar>-->
        <!--                                </v-card-text>-->
        <!--                            </v-flex>-->

        <!--                            <v-flex xs6>-->
        <!--                                <v-card flat tile height="100%">-->
        <!--                                    <v-card-text>-->
        <!--                                        &lt;!&ndash; Country &ndash;&gt;-->
        <!--                                        <v-autocomplete-->
        <!--                                            v-model="region.country_id"-->
        <!--                                            v-validate="region.rules.country_id"-->
        <!--                                            :error-messages="errors.collect('country_id')"-->
        <!--                                            :items="countries"-->
        <!--                                            item-value="country_id"-->
        <!--                                            item-text="name"-->
        <!--                                            autocomplete="off"-->
        <!--                                            placeholder="Choose country"-->
        <!--                                            color="yellow darken-3"-->
        <!--                                            data-vv-as="country"-->
        <!--                                            name="country_id"-->
        <!--                                            label="Country"-->
        <!--                                            clearable-->
        <!--                                            key="country_id"-->
        <!--                                            outlined-->
        <!--                                        ></v-autocomplete>-->
        <!--                                        &lt;!&ndash; iso_2 &ndash;&gt;-->
        <!--                                        <v-select-->
        <!--                                            v-model="region.iso_2"-->
        <!--                                            v-validate="region.rules.iso_2"-->
        <!--                                            :error-messages="errors.collect('iso_2')"-->
        <!--                                            :items="regions"-->
        <!--                                            @input="regionsManager.events.fire('click')"-->
        <!--                                            @click:clear="region.reset()"-->
        <!--                                            item-value="id"-->
        <!--                                            item-text="id"-->
        <!--                                            hint="Region iso code must be in iso-3166 format"-->
        <!--                                            placeholder="Enter region iso code"-->
        <!--                                            label="Region iso code"-->
        <!--                                            color="yellow darken-3"-->
        <!--                                            name="iso_2"-->
        <!--                                            key="iso_2"-->
        <!--                                            persistent-hint-->
        <!--                                            clearable-->
        <!--                                            outlined-->
        <!--                                        ></v-select>-->
        <!--                                        &lt;!&ndash; Name &ndash;&gt;-->
        <!--                                        <v-text-field-->
        <!--                                            v-model="region.name"-->
        <!--                                            v-validate="region.rules.name"-->
        <!--                                            :error-messages="errors.collect('name')"-->
        <!--                                            placeholder="Enter region name"-->
        <!--                                            color="yellow darken-3"-->
        <!--                                            label="Region name"-->
        <!--                                            key="name"-->
        <!--                                            name="name"-->
        <!--                                            outlined-->
        <!--                                        ></v-text-field>-->
        <!--                                    </v-card-text>-->
        <!--                                </v-card>-->
        <!--                            </v-flex>-->
        <!--                        </v-layout>-->

        <!--                        <v-card-actions>-->
        <!--                            <v-spacer></v-spacer>-->
        <!--                            <v-btn text dark @click="setupForm(3)">Cancel</v-btn>-->
        <!--                            <v-btn dark v-on="{ click: mode === 1 ? store : update }">{{ modeText }}</v-btn>-->
        <!--                        </v-card-actions>-->
        <!--                    </v-card>-->
        <!--                </v-dialog>-->
        <!--        <v-dialog v-model="confirmation" max-width="300" persistent eager>-->
        <!--            <v-card>-->
        <!--                <v-card-title>Are you sure to delete?</v-card-title>-->
        <!--                <v-card-actions>-->
        <!--                    <v-spacer></v-spacer>-->
        <!--                    <v-btn text @click="setupForm(3)">Cancel</v-btn>-->
        <!--                    <v-btn dark color="error" @click="destroy">Delete</v-btn>-->
        <!--                </v-card-actions>-->
        <!--            </v-card>-->
        <!--        </v-dialog>-->
    </v-container>
</template>

<script>
import axios from "axios";
import Region from "../../models/Region";
import Snackbar from "../../facades/Snackbar";
import RegionsPagination from "../../forms/RegionsPagination";

export default {
    name: "Regions",
    props: {
        countries: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            dialog: false,
            confirmation: false,
            mode: false,
            map: undefined,
            regionsManager: undefined,
            region: new Region(),
            selected: [],
            actions: [Region.MODE_CREATE, Region.MODE_UPDATE, Region.MODE_DELETE, Region.MODE_CANCEL],
            regions: [],
            window: {
                width: 0,
                height: 0,
                heightDif: 265,
            },
            paginated: new RegionsPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"]),
                    search: this.$route.query["search"]
                },
                "regions/paginate",
            ),
        };
    },

    computed: {
        modeText() {
            switch (this.mode) {
                case Region.MODE_CREATE:
                    return "Create";
                case Region.MODE_UPDATE:
                    return "Update";
                case Region.MODE_DELETE:
                    return "Delete";
            }
        },

        selectedCountry() {
            return this.region.country_id
                ? this.countries.find(country => country.country_id === this.region.country_id).name
                : "Countries";
        },
    },

    methods: {
        setupForm(action, region = {}) {
            this.region = new Region(region);
            this.mode = this.actions[action];
            this.dialog = this.actions[action] !== Region.MODE_CANCEL && this.actions[action] !== Region.MODE_DELETE;
            this.confirmation = this.actions[action] === Region.MODE_DELETE;
        },

        store() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.region
                        .store()
                        .then(response => {
                            if (response.status === 200) {
                                this.paginated.getRegions();
                                this.setupForm(this.actions.indexOf(Region.MODE_CANCEL));
                                Snackbar.success(response.data);
                            }
                        })
                        .catch(error => {
                            Region.errors(error.response).forEach(error => this.errors.add(error));
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        update() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.region
                        .update({ region: this.region.region_id })
                        .then(response => {
                            if (response.status === 200) {
                                this.paginated.getRegions();
                                this.setupForm(this.actions.indexOf(Region.MODE_CANCEL));
                                Snackbar.success(response.data);
                            }
                        })
                        .catch(error => {
                            Region.errors(error.response).forEach(error => this.errors.add(error));
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        destroy() {
            this.region
                .delete({ region: this.region.region_id })
                .then(response => {
                    if (response.status === 200) {
                        this.paginated.getRegions();
                        this.setupForm(this.actions.indexOf(Region.MODE_CANCEL));
                        Snackbar.success(response.data);
                    }
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },

        destroyMultiple() {
            axios
                .delete("/admin/super/regions/multiple", {
                    params: { ids: this.selected },
                })
                .then(response => {
                    if (response.status === 200) {
                        this.selected = [];
                        this.paginated.getRegions();
                        Snackbar.success(response.data);
                    }
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },
    },

    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "admin.super.regions",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search
                    },
                },
                () => {
                    this.paginated.getRegions();
                },
            );
        },
        "paginated.per_page": function () {
            this.$router.push(
                {
                    name: "admin.super.regions",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search
                    },
                },
                () => {
                    this.paginated.getRegions();
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "admin.super.regions",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getRegions();
                },
            );
        },
        "paginated.selected": function () {
            this.selected = [];
            this.paginated.selected.forEach(item => {
                this.selected.push(item.region_id);
            });
        },
        ["region.country_id"](id) {
            this.map.geoObjects.removeAll();
            this.regionsManager.removeAll();
            //this.region.reset();

            if (!id) return;

            let country = this.countries.find(country => country.country_id === id);

            let code = country ? country.iso_2.toUpperCase() : null;

            if (!code) return;

            ymaps.borders
                .load(code, { lang: "en", quality: 3 })
                .then(country => {
                    let selectedRegionId = "";

                    this.regions = country.features.map(feature => {
                        feature.id = feature.properties.iso3166;
                        feature.name = feature.properties.name;
                        return feature;
                    });

                    this.regionsManager.add(this.regions);
                    this.regionsManager.events
                        .add("mouseenter", e => {
                            this.regionsManager.objects.setObjectOptions(e.get("objectId"), { strokeWidth: 3 });
                        })
                        .add("mouseleave", e => {
                            this.regionsManager.objects.setObjectOptions(e.get("objectId"), { strokeWidth: 1 });
                        })
                        .add("click", e => {
                            let id = e.get("objectId") || this.region.code;

                            if (!id) return;

                            if (selectedRegionId !== id) {
                                this.regionsManager.objects.setObjectOptions(selectedRegionId, {
                                    strokeWidth: 1,
                                    fillColor: "#6961b0",
                                });
                            }

                            this.regionsManager.objects.setObjectOptions(id, {
                                strokeWidth: 1,
                                fillColor: "#F9A825",
                                opacity: "0.8",
                            });

                            selectedRegionId = id;
                            let selectedRegion = this.regionsManager.objects.getById(selectedRegionId);

                            this.region.iso_2 = selectedRegionId;
                            this.region.name = selectedRegion.name;

                            // ymaps
                            //     .geocode(this.region.name, { json: false })
                            //     .then(results => {
                            //         if (results.geoObjects.getLength()) {
                            //             let coords = results.geoObjects
                            //                 .get(0)
                            //                 .properties.get(
                            //                     'metaDataProperty.GeocoderMetaData.InternalToponymInfo.Point.coordinates',
                            //                 );
                            //
                            //             this.region.lat = String(coords[0]);
                            //             this.region.lon = String(coords[1]);
                            //         } else {
                            //             Snackbar.error('Cant get Region Coordinates!');
                            //         }
                            //     })
                            //     .catch(error => console.log(error));
                        });

                    this.map.geoObjects.removeAll();
                    this.map.geoObjects.add(this.regionsManager);
                    this.map.setBounds(this.regionsManager.getBounds(), { checkZoomRange: true });
                })
                .catch(error => console.log(error));
        },
    },

    mounted() {
        ymaps.ready(["util.calculateArea"]).then(() => {
            this.map = new ymaps.Map("map", {
                center: [55.76, 37.64],
                controls: [],
                zoom: 3,
            });

            this.regionsManager = new ymaps.ObjectManager();
        });
    },

    created() {
        this.paginated.getRegions();
        window.addEventListener("resize", this.handleResize);
        this.handleResize();
    },
};
</script>

<style scoped>
#map {
    height: 100%;
    width: 100%;
}
#map-wrapper {
    position: relative;
    height: 745px;
    padding: 0;
}
.menu-activator {
    position: absolute;
    left: 26px;
    top: 26px;
}
.border {
    border: 1px solid black;
}
</style>
