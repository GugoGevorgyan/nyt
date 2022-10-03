<!-- @format -->

<template>
    <v-container fluid>
        <v-card tile elevation="4">
            <!--Data Table-->
            <v-data-table
                loader-height="2"
                dense
                :headers="paginated.headers"
                :items="paginated.data"
                :items-per-page="Number(paginated.per_page)"
                :loading="paginated.loading"
                :search="paginated.search"
                :fixed-header="true"
                :height="window.height"
                hide-default-footer
                item-key="driver_candidate_id"
                disable-sort
                :dark="darkMode"
            >
                <template v-slot:top>
                    <v-toolbar flat :dark="darkMode" :color="darkMode ? 'black' : 'grey lighten-4'">
                        <v-toolbar-title v-text="'Кандидаты в водители'" />
                        <v-divider vertical class="ml-3" />
                        <v-row>
                            <v-col cols="12" md="6" lg="6">
                                <v-text-field
                                    prepend-inner-icon="mdi-magnify"
                                    class="rounded-2 ml-3"
                                    clearable
                                    color="yellow darken-3"
                                    hide-details
                                    label="Поиск"
                                    :dark="darkMode"
                                    v-model="paginated.search"
                                />
                            </v-col>
                        </v-row>
                        <v-spacer />
                        <v-btn
                            :href="`driver-candidates/create`"
                            v-text="'Новый кандидат'"
                            depressed
                            small
                            class="rounded-1"
                            :dark="darkMode"
                            height="100%"
                        />
                    </v-toolbar>
                    <v-divider />
                </template>

                <template v-slot:item.photo="{ item }">
                    <div class="d-flex justify-center pa-1">
                        <v-menu v-if="item.info.photo" open-on-hover offset-x>
                            <template v-slot:activator="{ on, attrs }">
                                <v-avatar v-bind="attrs" v-on="on" size="32">
                                    <v-img :src="item.info.photo" />
                                </v-avatar>
                            </template>
                            <v-img width="200" :src="item.info.photo"></v-img>
                        </v-menu>

                        <v-avatar color="grey lighten-1" size="30" v-else>
                            <v-icon small dark v-text="'mdi-camera-outline'" />
                        </v-avatar>
                    </div>
                </template>
                <template v-slot:item.license_types="{ item }">
                    {{ item.info.license_types.map(e => e.type).join(", ") }}
                </template>
                <template v-slot:item.experience="{ item }"> {{ item.info.experience }} лет </template>
                <template v-slot:item.contracts_count="{ item }">
                    <v-chip outlined small :color="item.driver && item.driver.contracts_count ? 'success' : 'error'">
                        {{
                            item.driver && item.driver.contracts_count
                                ? item.driver.contracts_count
                                : "нет подписанных контрактов"
                        }}
                    </v-chip>
                </template>
                <template v-slot:item.created_at="{ item }">
                    {{ item.created_at | formatDate }}
                </template>
                <template v-slot:item.action="{ item }">
                    <v-tooltip left v-if="3 === item.learn_status_id">
                        <template v-slot:activator="{ on }">
                            <v-btn
                                small
                                v-on="on"
                                class="mr-2"
                                icon
                                @click="(candidateToDriver = item), (driverDialog = true)"
                            >
                                <v-icon small color="success">mdi-car</v-icon>
                            </v-btn>
                        </template>
                        <span>Сделать кандидата водителем</span>
                    </v-tooltip>

                    <v-tooltip left>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                small
                                icon
                                v-on="on"
                                :href="
                                    $router.resolve({
                                        name: 'get_driver_candidates_edit',
                                        params: { candidate_id: item.driver_candidate_id },
                                    }).href
                                "
                            >
                                <v-icon small color="primary">mdi-pencil</v-icon>
                            </v-btn>
                        </template>
                        <span>Редактировать кандидата</span>
                    </v-tooltip>

                    <v-tooltip left>
                        <template v-slot:activator="{ on }">
                            <v-btn small icon @click.stop="confirm(item)" v-on="on">
                                <v-icon small color="error">mdi-close</v-icon>
                            </v-btn>
                        </template>
                        <span>Удалить кандидата</span>
                    </v-tooltip>
                </template>

                <template v-slot:item.phone="{ item }">
                    {{ item.phone | VMask(phoneMask) }}
                </template>

                <!--Footer-->
                <template v-slot:footer>
                    <table-footer :paginated="paginated" />
                </template>
            </v-data-table>

            <!--Delete dialog-->
            <v-dialog max-width="500" width="100%" v-model="deleteDialog">
                <v-card :loading="deleteLoading">
                    <v-card-title class="title">Вы уверены, что хотите удалить кандидата?</v-card-title>

                    <v-card-text>
                        <v-alert outlined type="error"> После удалеиня информация будет утерена! </v-alert>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn small @click="deleteDialog = false" text>отмена</v-btn>
                        <v-btn small :loading="deleteLoading" @click="destroy" color="error">удалить</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!--Delete multiple dialog-->
            <v-dialog max-width="400" width="100%" v-model="deletesDialog">
                <v-card :loading="deletesLoading">
                    <v-card-title>Вы уверены, что хотите удалить отмеченных кандидатов?</v-card-title>

                    <v-card-text>
                        <v-alert outlined type="error"> После удалеиня информация будет утерена! </v-alert>
                        <v-text-field
                            v-model="passwordConfirm"
                            v-validate="candidate.rules.password_confirm"
                            :error-messages="errors.collect('confirm')"
                            name="confirm"
                            type="password"
                            label="Введите пароль"
                            data-vv-as="пароль"
                        />
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer />
                        <v-btn @click="deletesDialog = false" text>отменить</v-btn>
                        <v-btn :loading="deletesLoading" @click="destroyMany" color="error" :disabled="!passwordConfirm"
                            >удалить</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!--Driver create dialog-->
            <v-dialog
                max-width="600"
                width="100%"
                overlay-opacity="0.7"
                persistent
                v-model="driverDialog"
                v-if="driverDialog"
            >
                <driver-dialog
                    @close="
                        candidateToDriver = undefined;
                        driverDialog = false;
                    "
                    @update="updateInfo()"
                    :candidate="candidateToDriver"
                    :types="types"
                    :graphics="graphics"
                />
            </v-dialog>
        </v-card>
    </v-container>
</template>

<script lang="js" src="./DriverCandidates.main.js" />
