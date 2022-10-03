<!-- @format -->

<template>
    <v-card :loading="loading" elevation="4" class="border">
        <v-card-title class="grey lighten-5">
            {{ parkData.name ? "Обновить парк: " + parkData.name : "Создать новый парк" }}
            <v-spacer />
            <v-btn outlined icon small color="grey" @click="close()"><v-icon v-text="'mdi-close'" /></v-btn>
        </v-card-title>
        <v-divider class="mb-3" />
        <v-card-text>
            <form autocomplete="off">
                <input :value="$csrf" name="_token" type="hidden" />
                <!--Park form-->
                <v-container>
                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-text-field
                                :error-messages="errors.collect('name')"
                                data-vv-validate-on="blur"
                                label="Название"
                                name="name"
                                autocomplete="disabled"
                                type="text"
                                v-model="park.name"
                                v-validate="park.rules.name"
                                outlined
                                dense
                                data-vv-as="название"
                            />
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-select
                                :items="regions"
                                item-text="name"
                                item-value="region_id"
                                :error-messages="errors.collect('region')"
                                data-vv-validate-on="blur"
                                label="Регион"
                                name="region"
                                type="text"
                                v-model="region"
                                v-validate="park.rules.region"
                                outlined
                                dense
                                data-vv-as="регион"
                            />
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-select
                                :disabled="!region"
                                :items="selectedSities"
                                item-text="name"
                                item-value="city_id"
                                :error-messages="errors.collect('city_id')"
                                data-vv-validate-on="blur"
                                label="Город"
                                name="city_id"
                                type="text"
                                v-model="park.city_id"
                                v-validate="park.rules.city_id"
                                outlined
                                dense
                                data-vv-as="город"
                            />
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-text-field
                                :error-messages="errors.collect('address')"
                                data-vv-validate-on="blur"
                                label="Адрес"
                                name="address"
                                autocomplete="disabled"
                                type="text"
                                v-model="park.address"
                                v-validate="park.rules.address"
                                outlined
                                dense
                                data-vv-as="адрес"
                            />
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-autocomplete
                                :items="entities"
                                clearable
                                data-vv-as="юридическое лицо"
                                color="yellow darken-3"
                                item-color="yellow darken-3"
                                item-text="name"
                                item-value="legal_entity_id"
                                label="Юридическое лицо"
                                open-on-clear
                                name="entity_id"
                                :error-messages="errors.collect('entity_id')"
                                v-model="park.entity_id"
                                v-validate="park.rules.entity_id"
                                outlined
                                dense
                            />
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-btn
                                small
                                color="primary"
                                target="_blank"
                                :href="
                                    $router.resolve({
                                        name: 'legal_entity_create',
                                    }).href
                                "
                                >Новое юридическое лицо</v-btn
                            >
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-autocomplete
                                :items="managers"
                                clearable
                                data-vv-as="управляющий парком"
                                color="yellow darken-3"
                                item-color="yellow darken-3"
                                item-text="name"
                                item-value="system_worker_id"
                                label="Управляющий парком"
                                open-on-clear
                                name="manager_id"
                                :error-messages="errors.collect('manager_id')"
                                v-model="park.manager_id"
                                v-validate="park.rules.manager_id"
                                outlined
                                dense
                            >
                                <template v-slot:selection="data">
                                    {{ data.item.name }} {{ data.item.patronymic }} {{ data.item.surname }}
                                </template>
                                <template v-slot:item="data">
                                    {{ data.item.name }} {{ data.item.patronymic }} {{ data.item.surname }}
                                </template>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-btn
                                target="_blank"
                                small
                                color="primary"
                                :href="
                                    $router.resolve({
                                        name: 'get_system_workers_create',
                                    }).href
                                "
                                >Новый управляющий</v-btn
                            >
                        </v-col>
                    </v-row>
                </v-container>
            </form>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <v-btn
                small
                @click="parkData.park_id ? update() : store()"
                color="primary"
                :loading="loading"
                :disabled="loading"
            >
                {{ parkData.park_id ? "Обновить" : "Создать" }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script src="./Form.main.js"/>
