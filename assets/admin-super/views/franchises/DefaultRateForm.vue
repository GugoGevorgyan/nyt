<!-- @format -->

<template>
    <div>
        <v-card :loading="loading">
            <v-card-title>Оценка по умолчанию для типа водителей "{{ driverType.type }}"</v-card-title>

            <v-card-text>
                <v-form data-vv-scope="defaultRate" autcomplete="off">
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                color="yellow darken-3"
                                outlined
                                dense
                                :error-messages="errors.collect(`defaultRate.assessment`)"
                                label="Assessment"
                                name="assessment"
                                v-model="assessment"
                                v-validate="rules.default_rate_assessment"
                                data-vv-as="assessment"
                            />

                            <v-text-field
                                color="yellow darken-3"
                                outlined
                                dense
                                :error-messages="errors.collect(`defaultRate.waybill_max_days`)"
                                label="Waybill max days"
                                name="waybill_max_days"
                                v-model="waybill_max_days"
                                v-validate="rules.waybill_max_days"
                                data-vv-as="rating"
                            />
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field
                                color="yellow darken-3"
                                outlined
                                dense
                                :error-messages="errors.collect(`defaultRate.rating`)"
                                label="Rating"
                                name="rating"
                                v-model="rating"
                                v-validate="rules.default_rate_rating"
                                data-vv-as="rating"
                            />
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions class="justify-end">
                <v-btn color="error" text small @click="close()">отмена</v-btn>
                <v-btn color="primary" :loading="loading" small @click="store()">принять</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script lang="js" src="./DefaultRateForm.main.js" />
