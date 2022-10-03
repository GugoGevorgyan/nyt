<!-- @format -->
<template>
    <v-container fluid>
        <v-flex class="mt-1">
            <v-data-table
                :loading="loading"
                :headers="headers"
                :calculate-widths="true"
                :items="items"
                :fixed-header="true"
                :height="window.height"
                item-key="name"
                class="elevation-1"
                dense
                multi-sort
            >
                <template v-slot:top>
                    <v-toolbar flat color="grey lighten-5" class="osxbutton">
                        <v-toolbar-title class="mr-5">Зоны</v-toolbar-title>
                        <v-spacer />
                        <v-btn tile light icon>
                            <v-icon color="primary" @click="showAreaDialog()" v-text="'mdi-plus'" />
                        </v-btn>
                    </v-toolbar>
                    <v-divider light />
                </template>
                <template v-slot:item.action="{ item }">
                    <v-btn @click="showAreaDialog(item)" icon small color="primary">
                        <v-icon small> mdi-pencil-outline </v-icon>
                    </v-btn>
                    <v-btn @click="showDelete(item)" icon small color="error">
                        <v-icon small> mdi-delete </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-flex>
        <v-dialog persistent v-model="deleteDialog" max-width="500px" width="100%">
            <v-card v-if="deleteItem">
                <v-card-title>
                    <span class="headline">Вы уверены?</span>
                </v-card-title>
                <v-divider />
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" class="d-flex justify-space-around">
                                <v-subheader>Удалить зону։</v-subheader>
                                <span class="display-1">{{ deleteItem.title }}</span>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-divider />
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="error" @click="closeDelete()">отмена</v-btn>
                    <v-btn :loading="deleteLoading" color="primary" @click="deleteArea()">удалить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div style="height: 100%; border-radius: 0">
            <v-dialog persistent v-model="areaDialog" v-if="areaDialog" max-width="1500px" width="100%">
                <area-form :area-obj="area" @saved="areaSaved()" @close="closeAreaDialog()" />
            </v-dialog>
        </div>
    </v-container>
</template>
<script>
import axios from "axios";
import Snackbar from "../../facades/Snackbar";
import AreaForm from "./AreaForm";

export default {
    name: "Area",
    components: { AreaForm },
    data: () => ({
        loading: false,
        headers: [
            { text: "id", value: "area_id", sortable: true },
            { text: "Занчине", value: "name", sortable: true },
            { text: "Название", value: "title", sortable: true },
            { text: "Описание", value: "description", sortable: true },
            { text: "Создано", value: "created_at", sortable: true },
            { text: "Действия", value: "action", sortable: false },
        ],

        items: [],
        area: null,
        areaDialog: false,

        window: {
            width: 0,
            height: window.innerHeight - 220,
        },

        deleteDialog: false,
        deleteItem: null,
        deleteLoading: false,
    }),
    methods: {
        getData() {
            this.loading = true;
            axios
                .get(`/admin/super/get/areas`)
                .then(response => {
                    this.loading = false;
                    this.items = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    console.log(error);
                });
        },

        showAreaDialog(item) {
            this.area = item;
            this.areaDialog = true;
        },

        closeAreaDialog() {
            this.area = null;
            this.areaDialog = false;
        },

        areaSaved() {
            this.closeAreaDialog();
            this.getData();
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 220;
        },

        showDelete(item) {
            this.deleteItem = item;
            this.deleteDialog = true;
        },
        closeDelete() {
            this.deleteItem = null;
            this.deleteDialog = false;
        },
        deleteArea() {
            this.deleteLoading = true;
            axios
                .delete("/admin/super/area/destroy/" + this.deleteItem.area_id)
                .then(response => {
                    this.deleteLoading = false;
                    Snackbar.success(response.data.message);
                    this.getData();
                    this.closeDelete();
                })
                .catch(error => {
                    this.deleteLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
    },
    created() {
        window.addEventListener("resize", this.handleResize);
        this.getData();
    },
};
</script>
