<!-- @format -->

<template>
    <div>
        <v-overlay :value="mapOverlay">
            <v-progress-circular :size="50" :width="2" color="yellow darken-3" indeterminate />
        </v-overlay>
        <div ref="buttons" class="py-3 px-2 d-flex align-center">
            <v-switch
                color="yellow darken-3"
                :disabled="placeMarkOptions.order.attached"
                class="mt-1 mr-6 pa-0"
                v-model="showPendingOrders"
                label="Показать ожидающих клиентов"
                dense
                single-line
                hide-details
            />
            <v-select
                style="max-width: 240px"
                class="ma-0 mr-6"
                color="yellow darken-3"
                :disabled="placeMarkOptions.order.attached"
                clearable
                label="Статус водителя"
                :items="driverStatuses"
                item-text="text"
                item-value="status"
                v-model="showDriversStatus"
                item-color="yellow darken-3"
                menu-props="offsetY"
                dense
                single-line
                hide-details
            />
            <v-autocomplete
                dense
                :loading="regionsLoading"
                clearable
                loader-height="2"
                style="max-width: 240px"
                class="ma-0 pointer"
                color="yellow darken-3"
                menu-props="offsetY"
                single-line
                hide-details
                label="Регион на карте"
                :items="regions"
                item-text="name"
                item-value="region_id"
                v-model="selectedMapRegion"
            />
        </div>
        <v-divider />
        <div id="map" :style="{ height: mapHeight }" />
    </div>
</template>

<script lang="js" src="./OrdersMap.main.js" />
<style lang="scss" src="./OrdersMap.style.scss" />
