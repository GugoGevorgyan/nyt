<!-- @format -->

<template>
    <v-container>
        <v-row>
            <v-col v-if="image" v-for="image in images" :key="image.id" cols="6" class="pa-0 ma-0">
                <v-img width="350" :src="image.path + image.name" aspect-ratio="1" class="grey lighten-2">
                    <template v-slot:placeholder>
                        <v-row class="fill-height ma-0" align="center" justify="center">
                            <v-progress-circular indeterminate color="grey lighten-5" />
                        </v-row>
                    </template>
                </v-img>
            </v-col>
            <v-alert class="align-center" colored-border color="red" v-text="'NO IMAGES'" />
        </v-row>
    </v-container>
</template>

<script>
import Waybill from "../../models/Waybill";

export default {
    name: "ImagesDialogue",

    props: ["waybillId"],

    data() {
        return {
            waybill: new Waybill(this.item),
            images: [],
        };
    },

    created() {
        this.waybill.waybillImages(this.waybillId).then(result => {
            this.images = result.data;
        });
    },
};
</script>

<style scoped></style>
