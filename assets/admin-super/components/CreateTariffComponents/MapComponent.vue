<!-- @format -->

<template>
    <v-flex style="position: relative" xs6>
        <template v-if="showMap">
            <v-alert class="ma-0" color="green" dense type="info" v-if="mode">
                {{ mapText }}
            </v-alert>
            <v-alert class="ma-0" color="blue-grey" dense type="info" v-else>
                {{ mapText }}
            </v-alert>
        </template>
        <div class="paintingDiv" v-if="showMap">
            <v-row>
                <v-col cols="4">
                    <v-card class="ml-3">
                        <v-text-field
                            clearable
                            color="yellow darken-3"
                            hide-details
                            hint="enter area name"
                            label="Name"
                            outlined
                            v-if="mode"
                            v-model="destinationArea.name"
                            outlined
                            dense
                        ></v-text-field>
                        <v-autocomplete
                            dense
                            outlined
                            :items="destinationAreas"
                            @change="editArea"
                            clearable
                            color="yellow darken-3"
                            hide-details
                            item-color="yellow darken-3"
                            item-text="name"
                            item-value="destination_area_id"
                            label="Areas"
                            outlined
                            return-object
                            v-else
                            v-model="selectedArea"
                        />
                    </v-card>
                </v-col>
                <v-dialog max-width="290" width="100%" v-model="dialog">
                    <v-card>
                        <v-card-title class="headline">Use Google's location service?</v-card-title>
                        <v-card-text>
                            Let Google help apps determine location. This means sending anonymous location data to Google, even when no apps
                            are running.
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn @click="dialog = false" color="green darken-1" text> Disagree </v-btn>
                            <v-btn @click="dialog = false" color="green darken-1" text> Agree </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-col cols="8">
                    <v-speed-dial :transition="'slide-y-transition'" absolute direction="bottom" right top v-model="fab">
                        <template v-slot:activator>
                            <v-tooltip left>
                                <template v-slot:activator="{ on }">
                                    <v-btn @click="cancel" color="grey darken-2" dark fab small v-if="fab" v-model="fab" v-on="on">
                                        <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                    <v-btn @click="createArea" color="yellow darken-3" dark fab small v-else v-model="fab" v-on="on">
                                        <v-icon>mdi-plus</v-icon>
                                    </v-btn>
                                </template>
                                <span v-if="fab">cancel</span>
                                <span v-else>add area</span>
                            </v-tooltip>
                        </template>
                        <v-tooltip left>
                            <template v-slot:activator="{ on }">
                                <v-btn @click="saveArea" color="green darken-3" dark fab small v-on="on">
                                    <v-icon>mdi-content-save-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>save</span>
                        </v-tooltip>
                        <v-tooltip left>
                            <template v-slot:activator="{ on }">
                                <v-btn @click="deleteArea" color="red" dark fab small v-if="2 === mode" v-on="on">
                                    <v-icon>mdi-delete-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>delete</span>
                        </v-tooltip>
                    </v-speed-dial>
                </v-col>
            </v-row>
        </div>
        <div id="map" style="width: 100%; height: 100%"></div>
    </v-flex>
</template>

<script>
import Destination from '../../models/Destination';
import { mapMutations, mapState } from 'vuex';

export default {
    name: 'MapComponent',

    data: () => ({
        destination: new Destination(),
    }),

    computed: {
        ...mapState([]),
    },

    methods: {
        ...mapMutations([]),
    },
};
</script>

<style></style>
