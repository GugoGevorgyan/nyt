<!-- @format -->

<template>
    <div ref="footer" style="background-color: whitesmoke">
        <v-divider class="ma-0" />
        <v-row no-gutters>
            <!--     EVENTS      -->
            <v-col cols="12" md="4" lg="4" class="d-flex justify-start align-center">
                <div v-if="paginated.selected && paginated.selected.length" class="ml-3">
                    <v-tooltip right>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                fab
                                x-small
                                color="error"
                                outlined
                                v-bind="attrs"
                                v-on="on"
                                @click="$emit('deletes')"
                            >
                                <v-icon v-text="'mdi-delete-outline'" />
                            </v-btn>
                        </template>
                        <span>удалить отмеченные</span>
                    </v-tooltip>
                    <v-tooltip right v-if="firstEvent">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                fab
                                x-small
                                class="ml-2"
                                outlined
                                color="primary"
                                v-bind="attrs"
                                v-on="on"
                                @click="$emit('firstEvent')"
                            >
                                <v-icon v-text="firstEventIcon" />
                            </v-btn>
                        </template>
                        <span v-text="firstEventText" />
                    </v-tooltip>
                </div>
            </v-col>

            <!--     PAGINATION       -->
            <v-col cols="12" md="4" lg="4" class="d-flex justify-center align-center">
                <v-tooltip left>
                    <template v-slot:activator="{ on, attrs }">
                        <div v-bind="attrs" v-on="on">
                            <v-pagination
                                v-model="paginated.current_page"
                                :disabled="'loading' in paginated && paginated.loading"
                                :length="paginated.last_page"
                                :total-visible="4"
                                color="secondary"
                            />
                        </div>
                    </template>
                    <span>
                        {{
                            Number(paginated.total)
                                ? `${paginated.from}-${paginated.to} из ${paginated.total}`
                                : "Нет данных"
                        }}
                    </span>
                </v-tooltip>
            </v-col>

            <!--     PER PAGE       -->
            <v-col cols="12" md="4" lg="4" style="margin-left: -25px" class="d-flex justify-end align-center">
                <v-menu rounded max-width="100">
                    <template v-slot:activator="{ on: menu, attrs }">
                        <v-tooltip left>
                            <template v-slot:activator="{ on: tooltip }">
                                <v-btn
                                    small
                                    outlined
                                    depressed
                                    dark
                                    color="#344955"
                                    v-bind="attrs"
                                    v-on="{ ...tooltip, ...menu }"
                                >
                                    {{ paginated.per_page }}
                                </v-btn>
                            </template>
                            <span>строк на странице</span>
                        </v-tooltip>
                    </template>
                    <v-list dense>
                        <v-list-item
                            dense
                            :disabled="paginated.per_page === item"
                            color="primary"
                            v-for="(item, index) in paginated.perPages"
                            :key="index"
                            @click="paginated.per_page = item"
                        >
                            <v-list-item-title v-text="item" />
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "TableFooter",

    props: {
        paginated: {
            required: true,
            type: Object,
        },
        firstEvent: {
            type: Boolean,
            default: false,
        },
        firstEventIcon: {
            type: String,
        },
        firstEventText: {
            type: String,
        },
    },
};
</script>
<style>
.v-pagination__item {
    height: 30px !important;
    min-width: 30px !important;
}
.v-pagination__navigation {
    box-shadow: none !important;
}
</style>
