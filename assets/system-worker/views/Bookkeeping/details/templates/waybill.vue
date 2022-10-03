<!-- @format -->

<template>
    <div>
        <v-row>
            <v-col cols="12" sm="6" md="6" lg="6">
                <v-card>
                    <v-card-title class="subheading font-weight-bold">
                        {{ details.type.name }}
                    </v-card-title>

                    <v-divider />

                    <v-list dense>
                        <v-list-item>
                            <v-list-item-content>Номер:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.number }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Сумма:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.cost }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Сумма Компании:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.franchise_cost }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Дата начала:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.start }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Дата окончания:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.end }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Проверено:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                <v-icon v-if="details.reason.verified" v-text="'mdi-check'" color="green darken-3" />
                                <v-icon v-else v-text="'mdi-close'" color="red red-3" />
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Подписано:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                <v-icon v-if="details.reason.signed" v-text="'mdi-check'" color="green darken-3" />
                                <v-icon v-else v-text="'mdi-close'" color="red red-3" />
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="6" lg="6">
                <v-card>
                    <v-card-title class="subheading font-weight-bold"> Водитель </v-card-title>
                    <v-divider />

                    <v-list dense>
                        <v-list-item>
                            <v-list-item-content>Имя:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.side.name }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Фамилия:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.side.surname }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Отнчество:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.side.patronymic }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Телефон:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.side.phone | VMask(phoneMask) }}
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "waybill",

    props: {
        details: {
            required: true,
        },
    },

    computed: {
        phoneMask() {
            return this.$store.state.phoneMask;
        },
    },
};
</script>
<style scoped></style>
