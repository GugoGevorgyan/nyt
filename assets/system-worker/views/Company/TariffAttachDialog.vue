<!-- @format -->

<template>
    <v-card :loading="loading" max-width="500" style="overflow: hidden" class="border-lg" max-height="650px">
        <v-card-title class="px-4">Тарифы компании: {{ company.name }}</v-card-title>
        <v-divider />
        <v-card-text :class="tariffs.length ? 'pa-0' : null" style="height: 550px; overflow-y: auto">
            <v-skeleton-loader ref="skeleton" v-if="skeletons" type="list-item-two-line" class="mx-auto" />
            <template>
                <v-list two-line class="pa-0" v-if="tariffs.length">
                    <v-list-item-group v-model="selected" multiple active-class="grey--text">
                        <template v-for="(tariff, index) in tariffs">
                            <v-list-item :value="tariff.tariff_id" :key="tariff.tariff_id" dense>
                                <template v-slot:default="{ active, toggle }">
                                    <v-list-item-content>
                                        <v-list-item-title v-text="tariff.name" />
                                        <v-list-item-subtitle v-text="tariff.tariff_type.name" />
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-icon
                                            :color="active ? 'yellow darken-3' : 'grey lighten-1'"
                                            v-text="'mdi-check'"
                                        />
                                    </v-list-item-action>
                                </template>
                            </v-list-item>
                            <v-divider v-if="index + 1 < tariffs.length" :key="index" />
                        </template>
                    </v-list-item-group>
                </v-list>
                <v-alert class="mt-5" outlined dense type="info" v-else-if="!skeletons">
                    <small>Нет доступных тарифов</small>
                </v-alert>
            </template>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <template v-if="tariffs.length">
                <v-btn tile outlined small color="error" @click="$emit('close')">Отмена</v-btn>
                <v-btn
                    depressed
                    small
                    @click="accept()"
                    color="primary"
                    :loading="loading"
                    :disabled="loading"
                    v-text="'принять'"
                    tile
                />
            </template>
            <template v-else>
                <v-btn tile depressed text outlined small color="error" @click="$emit('close')">закрыть</v-btn>
            </template>
        </v-card-actions>
    </v-card>
</template>

<script>
import Snackbar from "../../facades/Snackbar";

export default {
    name: "TariffAttachDialog",

    props: {
        company: Object,
    },

    data() {
        return {
            loading: false,
            skeletons: false,
            tariffs: [],
            selected: [],
        };
    },

    watch: {},

    methods: {
        setSelected() {
            this.company.tariffs.forEach(item => this.selected.push(item.tariff_id));
        },

        getTariffs() {
            this.skeletons = true;
            this.loading = true;
            this.$http
                .get(`/app/worker/company/get-tariffs`)
                .then(response => {
                    this.tariffs = response.data;
                    this.loading = false;
                    this.skeletons = false;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.loading = false;
                });
        },

        accept() {
            this.loading = true;
            this.$http
                .post("/app/worker/company/set-tariffs", {
                    company_id: this.company.company_id,
                    tariff_ids: this.selected,
                })
                .then(response => {
                    Snackbar.info(response.data.message);
                    this.selected = response.data.tariff_ids;
                    this.loading = false;
                    this.$emit("close");
                    this.$emit("updated");
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.loading = false;
                });
        },
    },
    created() {
        this.setSelected();
        this.getTariffs();
    },
};
</script>
