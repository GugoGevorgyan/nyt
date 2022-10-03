<!-- @format -->

<template>
    <v-container fluid>
        <v-card outlined width="100%">
            <v-card-title>
                <div>
                    <div class="headline">Dashboard</div>
                    <p class="grey--text body-2 mb-0">
                        Tell us about your first room. After entering all the necessary info, you can fill in the
                        details of your other rooms.
                    </p>
                </div>
            </v-card-title>

            <v-divider />

            <v-card-text :style="{ height: window.height + 'px' }" class="overflow-y-auto">
                <highcharts :constructor-type="'chart'" :options="lineChartOptions" />
                <highcharts :constructor-type="'stockChart'" :options="lineChartOptions" />
                <highcharts :constructor-type="'stockChart'" :options="lineChartOptions" />
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import Highcharts from "highcharts";
import stockInit from "highcharts/modules/stock";
import annotationInit from "highcharts/modules/annotations";
import timelineInit from "highcharts/modules/timeline";
export default {
    name: "Dashboard",

    data() {
        return {
            lineChartOptions: {
                series: [
                    {
                        data: [1, 2, 3], // sample data
                        type: "area",
                    },
                ],
            },

            window: {
                width: 0,
                height: window.innerHeight - 170,
            },
        };
    },

    methods: {
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 170;
        },
    },

    created() {
        window.addEventListener("resize", this.handleResize);

        stockInit(Highcharts);
        annotationInit(Highcharts);
        timelineInit(Highcharts);
    },
};
</script>

<style scoped>
html {
    overflow: hidden;
}
</style>
