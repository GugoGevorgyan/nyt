<!-- @format -->

<template>
    <div>
        <system-bar class="mb-5" />
        <drawer style="z-index: 9999999" v-if="drawer" />

        <v-card-title class="text-center row grey--text"><span>Предварительные заказы</span></v-card-title>
        <v-divider class="mt-2" />

        <v-expansion-panels accordion tile>
            <v-progress-circular indeterminate :size="40" color="yellow darken-3" v-if="preorder.contentLoad" />

            <v-expansion-panel v-for="(order, index) in preorders" :key="index">
                <v-expansion-panel-header>
                    <template v-slot:default="{ open }">
                        <v-row style="margin-left: -18px; margin-right: 5px">
                            <v-icon color="red" @click="openRemoveDialogue(order.order_id)" v-text="'mdi-close'" />
                        </v-row>
                        <v-row no-gutters>
                            <v-col cols="4">
                                <small>{{ order.time.start | moment }}</small>
                            </v-col>
                            <v-col cols="8" class="text--secondary">
                                <v-fade-transition leave-absolute>
                                    <span v-if="open" key="0" v-text="'Details'" />
                                    <span v-else key="1">
                                        {{ order.addresses.from }}
                                    </span>
                                </v-fade-transition>
                            </v-col>
                        </v-row>
                    </template>
                </v-expansion-panel-header>

                <v-divider />

                <v-expansion-panel-content class="mt-4">
                    <v-layout row>
                        <div>
                            <span>From : </span>
                            <small class="text-decoration-underline">{{ order.addresses.from }}</small>
                        </div>
                    </v-layout>
                    <v-layout row>
                        <div>
                            <span>To : </span>
                            <small class="text-decoration-underline">{{ order.addresses.to }}</small>
                        </div>
                    </v-layout>
                    <v-layout row>
                        <div>
                            <span>Started : </span>
                            <small class="text-decoration-underline">{{ order.time.start | momento }}</small>
                        </div>
                    </v-layout>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>

        <v-dialog max-width="300" width="100%" v-model="removeDialogue" v-if="removeDialogue" transition="dialog-bottom-transition">
            <v-card max-width="300">
                <v-card-title>DELETE Pre Order</v-card-title>
                <v-divider class="mb-2" />

                <v-card-text>
                    <v-layout fill-height align-center justify-center align-content-center>
                        <v-flex>
                            <v-btn width="100" class="mr-1" tile color="deep-orange" @click="removePreorder">YES</v-btn>
                        </v-flex>

                        <v-flex>
                            <v-btn width="150" tile outlined @click="removeDialogue = false">NO</v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import Preorder from '../models/Preorder';
import Snackbar from '../facades/Snackbar';

export default {
    name: 'Preorders',

    data() {
        return {
            preorders: Array,
            preorder: new Preorder(),
            removeDialogue: false,
            removeOrderId: null,
        };
    },

    methods: {
        openRemoveDialogue(order_id) {
            this.removeOrderId = order_id;
            this.removeDialogue = true;
        },

        removePreorder() {
            this.preorder.deletePreorder(this.removeOrderId).then(result => {
                this.removeDialogue = false;
                Snackbar.info('deleted');
                let removeIndex = this.preorders
                    .map(function (item) {
                        return item.order_id;
                    })
                    .indexOf(this.removeOrderId);
                this.preorders.splice(removeIndex, 1);
            });
        },
    },

    computed: {
        drawer: {
            get() {
                return this.$store.state.drawer;
            },
            set(val) {
                this.$store.state.drawer = val;
            },
        },
    },

    created() {
        this.preorder.contentLoad = true;
        this.preorder.preorders.then(result => {
            this.preorders = result.data.data;
            this.preorder.contentLoad = false;
        });
    },
};
</script>

<style scoped></style>
