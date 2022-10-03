<!-- @format -->

<template>
    <v-card elevation="6" class="border" :color="darkMode ? 'grey darken-3' : 'grey lighten-3'" :dark="darkMode">
        <v-card-title>
            <v-text-field autofocus solo :dark="darkMode" :color="darkMode ? 'black' : 'grey'" placeholder="SEARCH" />
        </v-card-title>
        <v-divider />
        <v-card-text class="mt-5">
            <v-list disabled :dark="darkMode">
                <v-list-item-group v-model="selectedItem" color="primary">
                    <v-list-item dense v-for="(item, i) in items" :key="i">
                        <v-list-item-icon>
                            <v-icon v-text="item.icon" :dark="!darkMode" :color="darkMode ? 'white' : 'black'" />
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.text" />
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-card-text>
    </v-card>
</template>

<script lang="js" src="./search.main.js" />
