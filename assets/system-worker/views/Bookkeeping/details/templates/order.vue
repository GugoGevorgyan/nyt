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
                            <v-list-item-content>Сумма:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.cost }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Изначальная сумма:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.initial_price }}
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
                            <v-list-item-content>Аддресс:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.address_from }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Направление:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.address_to }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Изначальное направление:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.to_coordinates }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Доп. опции:</v-list-item-content>
                            <v-list-item-content
                                v-for="(options, index) of details.reason.options"
                                :key="index"
                                class="align-end justify-end"
                            >
                                {{ options.option + "," }}
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
                            <v-list-item-content>Сумма водителя:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.side_cost }}
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
                    <v-divider />
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "order",

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
