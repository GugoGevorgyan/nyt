<!-- @format -->

<template>
    <div>
        <v-row>
            <v-col cols="12" sm="4" md="4" lg="4">
                <v-card>
                    <v-card-title class="subheading font-weight-bold">
                        {{ details.type.name }}
                    </v-card-title>

                    <v-divider />

                    <v-list dense>
                        <v-list-item>
                            <v-list-item-content>Дата аварии:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.date_time }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Аддресс:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.address }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Водитель виновен:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                <v-icon v-if="details.reason.driver_fault" v-text="'mdi-check'" color="red" />
                                <v-icon v-else v-text="'mdi-close'" color="green" />
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Сумма акта:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.act_sum }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Номер акта:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.act_number }}
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-col>

            <v-col cols="12" sm="4" md="4" lg="4">
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

            <v-col cols="12" sm="4" md="4" lg="4">
                <v-card>
                    <v-card-title class="subheading font-weight-bold"> Автомобиль </v-card-title>

                    <v-list>
                        <v-list-item>
                            <v-list-item-content>Отнчество:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.car.license_number }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Гос номер:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.car.license_number }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Марка:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.car.mark }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Модель:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.car.model }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Цвет:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.car.color }}
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-list-item-content>Выпуск:</v-list-item-content>
                            <v-list-item-content class="align-end justify-end">
                                {{ details.reason.car.year }}
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
    name: "crash",

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
