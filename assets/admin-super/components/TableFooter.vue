<!-- @format -->

<template>
    <div ref="footer">
        <v-divider class="ma-0" />
        <v-row no-gutters class="py-1">
            <!--     EVENT DATA       -->
            <v-col cols="12" md="2" lg="2" class="d-flex justify-start align-center">
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
                    <v-divider vertical />
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
            <v-col cols="12" md="8" lg="8" class="d-flex justify-center align-center">
                <v-tooltip left>
                    <template v-slot:activator="{ on, attrs }">
                        <div v-bind="attrs" v-on="on">
                            <v-pagination
                                :disabled="'loading' in paginated && paginated.loading"
                                :length="paginated.last_page"
                                :total-visible="4"
                                circle
                                color="yellow darken-3"
                                v-model="paginated.current_page"
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
            <v-col cols="12" md="2" lg="2" class="d-flex justify-center align-center float-right">
                <v-menu rounded max-width="100">
                    <template v-slot:activator="{ on: menu, attrs }">
                        <v-tooltip left>
                            <template v-slot:activator="{ on: tooltip }">
                                <v-btn
                                    :disabled="'loading' in paginated && paginated.loading"
                                    fab
                                    x-small
                                    outlined
                                    depressed
                                    dark
                                    color="yellow darken-3"
                                    v-bind="attrs"
                                    v-on="{ ...tooltip, ...menu }"
                                >
                                    {{ paginated.per_page }}
                                </v-btn>
                            </template>
                            <span>строк на странице</span>
                        </v-tooltip>
                    </template>
                    <v-list>
                        <v-list-item
                            :disabled="paginated.per_page === item"
                            color="yellow darken-3"
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
