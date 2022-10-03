<!-- @format -->

<template>
    <div>
        <navbar />
        <v-container class="pa-0" style="height: 100%" fluid>
            <v-card color="grey lighten-4" class="mb-7" flat>
                <v-card-title v-text="client.phone || client.name" />

                <v-tabs centered center-active height="35" background-color="grey lighten-4" v-model="tab" color="grey darken-3">
                    <v-tab :key="item.title" :to="item.url" v-for="(item, index) in items">
                        {{ item.title }}
<!--                        <v-divider v-if="index < items.length - 1" vertical class="ml-5" />-->
                    </v-tab>
                </v-tabs>

                <v-tabs-items v-model="tab">
                    <router-view />
                </v-tabs-items>
            </v-card>
        </v-container>
    </div>
</template>

<script lang="js" src="./Profile.main.js" />
<style scoped>
.border {
    border: 1px solid black;
}
</style>
