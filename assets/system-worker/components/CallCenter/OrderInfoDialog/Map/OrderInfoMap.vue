<!-- @format -->

<template>
    <div :style="{ height: height + 'px' }">
        <div :id="'order-history-map-' + order.order_id" :style="{ height: height + 'px' }" style="width: 100%"></div>
        <v-overlay :opacity="0.7" :value="loading" absolute>
            <v-progress-circular color="yellow darken-3" indeterminate size="40" width="2" />
        </v-overlay>
    </div>
</template>
<script lang="js" src="./OrderInfoMap.main.js"/>
