<!-- @format -->

<template>
    <v-container fluid>
        <v-card outlined elevation="4" :dark="darkMode">
            <v-data-table
                loader-height="2"
                dense
                calculate-widths
                fixed-header
                :headers="paginated.headers"
                :height="window.height"
                :items="paginated.data"
                :items-per-page="Number(paginated.per_page)"
                :loading="paginated.loading"
                hide-default-footer
                item-key="system_worker_id"
                v-model="paginated.selected"
                show-select
                selectable-key="system_worker_id"
                :dark="darkMode"
            >
                <!--table header-->
                <template v-slot:top>
                    <v-toolbar flat :dark="darkMode" :color="darkMode ? 'black' : 'grey lighten-4'" height="55px">
                        <v-toolbar-title>Работники</v-toolbar-title>
                        <v-divider vertical class="mx-3" />
                        <v-text-field
                            style="max-width: 400px"
                            prepend-inner-icon="mdi-magnify"
                            clearable
                            color="yellow darken-3"
                            hide-details
                            label="Поиск"
                            single-line
                            outlined
                            dense
                            class="rounded-2"
                            v-model="paginated.search"
                        />
                        <v-divider vertical class="mx-3" />
                        <v-autocomplete
                            style="max-width: 400px"
                            class="rounded-2"
                            :items="roles"
                            clearable
                            color="yellow darken-3"
                            item-text="text"
                            item-value="role_id"
                            label="Роли"
                            multiple
                            v-model="paginated.role_ids"
                            dense
                            outlined
                            hide-details
                        >
                            <template v-slot:selection="{ item, index }">
                                <small v-if="1 > index" class="mr-2">{{ item.text }}</small>
                                <template v-if="1 === index">
                                    <small class="mr-1">(+{{ paginated.role_ids.length - 1 }} других)</small>
                                    <v-icon small color="grey">mdi-magnify</v-icon>
                                </template>
                            </template>
                        </v-autocomplete>
                        <v-divider vertical class="mx-3" />

                        <v-spacer />
                        <v-btn
                            v-text="'Новый работник'"
                            :href="$router.resolve({ name: 'get_system_workers_create' }).href"
                            depressed
                            class="rounded-1"
                            height="100%"
                        />
                    </v-toolbar>
                    <v-divider />
                </template>

                <template v-slot:item.worker="{ item }">
                    <div class="d-flex align-center justify-space-between">
                        <small>{{ item.surname }} {{ item.name }} {{ item.patronymic }}</small>
                        <div>
                            <v-menu v-if="item.photo" open-on-hover offset-x>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-avatar v-bind="attrs" v-on="on" size="32">
                                        <v-img :src="item.photo"></v-img>
                                    </v-avatar>
                                </template>
                                <v-img width="200" :src="item.photo"></v-img>
                            </v-menu>
                            <v-icon v-else small dark color="grey darken-2">mdi-camera-outline</v-icon>
                        </div>
                    </div>
                </template>
                <template v-slot:item.activity="{ item }">
                    <v-chip x-small outlined :color="item.logged ? 'success' : 'error'">{{
                        item.logged ? "онлайн" : "офлайн"
                    }}</v-chip>
                </template>
                <template v-slot:item.roles="{ item }">
                    <div class="d-flex align-center">
                        <div class="mr-2">
                            <v-chip
                                v-for="(role, index) in item.roles"
                                v-if="index < 3"
                                :key="role.role_id"
                                x-small
                                outlined
                                color="yellow darken-3"
                                class="mr-1"
                            >
                                {{ role.text }}
                            </v-chip>
                        </div>
                        <template v-if="item.roles.length > 3">
                            <v-menu offset-x :close-on-content-click="false">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn x-small icon color="primary" v-bind="attrs" v-on="on">
                                        <v-icon small>mdi-chevron-double-right</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item style="border-bottom: 1px solid gray">
                                        <v-list-item-title>Все роли работника</v-list-item-title>
                                    </v-list-item>
                                    <v-list-item-group style="height: 350px; overflow-y: auto">
                                        <v-list-item
                                            style="background-color: white"
                                            v-for="role in item.roles"
                                            :key="role.role_id"
                                        >
                                            <v-list-item-content>
                                                <small>{{ role.text }}</small>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </v-menu>
                        </template>
                    </div>
                </template>
                <template v-slot:item.created_at="{ item }">
                    {{ item.created_at | dateFormat }}
                </template>
                <template v-slot:item.action="{ item }">
                    <!--<v-tooltip left>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                target="_blank"
                                small
                                :href="
                                    $router.resolve({
                                        name: 'profile_view',
                                        params: { system_worker_id: item.system_worker_id },
                                    }).href
                                "
                                icon
                                v-on="on"
                                color="primary"
                            >
                                <v-icon small>mdi-eye</v-icon>
                            </v-btn>
                        </template>
                        <span>Показать профиль</span>
                    </v-tooltip>-->

                    <v-tooltip left>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                small
                                :href="
                                    $router.resolve({
                                        name: 'get_system_workers_edit',
                                        params: { system_worker_id: item.system_worker_id },
                                    }).href
                                "
                                icon
                                v-on="on"
                                color="primary"
                            >
                                <v-icon small>mdi-pencil-outline</v-icon>
                            </v-btn>
                        </template>
                        <span>Редактировать информацию работника</span>
                    </v-tooltip>

                    <v-tooltip left>
                        <template v-slot:activator="{ on }">
                            <v-btn small color="error" icon @click.stop="confirmDelete(item)" v-on="on">
                                <v-icon small v-text="'mdi-delete'" />
                            </v-btn>
                        </template>
                        <span>Удалить работника</span>
                    </v-tooltip>
                </template>

                <template v-slot:item.phone="{ item }">
                    <div v-if="item.phone">
                        {{ item.phone }}
                    </div>
                </template>

                <!--table footer-->
                <template v-slot:footer>
                    <table-footer @deletes="confirmDeletes()" :paginated="paginated" />
                </template>
            </v-data-table>
        </v-card>

        <!--delete dialog-->
        <v-dialog max-width="500" width="100%" v-model="deleteDialog" persistent>
            <v-card :loading="deleteLoading">
                <v-card-title class="title">Вы уверены, что хотите удалить работника?</v-card-title>

                <v-card-text>
                    <v-alert outlined type="error"> После удалеиня информация будет утерена! </v-alert>
                    <v-form>
                        <v-text-field
                            color="yellow darken-3"
                            outlined
                            dense
                            :error-messages="errors.collect('confirm_delete_password')"
                            autofocus
                            label="Пароль"
                            name="confirm_delete_password"
                            placeholder="Необходимо ввести пароль"
                            type="password"
                            v-model="confirmPassword"
                            v-validate="'min:5|max:32|required'"
                            data-vv-as="пароль"
                        >
                        </v-text-field>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn small @click="closeConfirmDelete()" text>отмена</v-btn>
                    <v-btn small :loading="deleteLoading" @click="destroy" color="error">удалить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!--delete multiple dialog-->
        <v-dialog max-width="400" width="100%" v-model="deletesDialog" persistent>
            <v-card :loading="deletesLoading">
                <v-card-title>Вы уверены, что хотите удалить отмеченных работников?</v-card-title>

                <v-card-text>
                    <v-alert outlined type="error"> После удалеиня информация будет утерена! </v-alert>
                    <v-form>
                        <v-text-field
                            color="yellow darken-3"
                            outlined
                            dense
                            :error-messages="errors.collect('confirm_delete_password')"
                            autofocus
                            label="Введите пароль"
                            name="confirm_delete_password"
                            placeholder="Необходимо ввести пароль"
                            type="password"
                            v-model="confirmPassword"
                            v-validate="'min:5|max:32|required'"
                            data-vv-as="пароль"
                        />
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn small @click="closeConfirmDeletes()" text>отменить</v-btn>
                    <v-btn small :loading="deletesLoading" @click="destroyMultiple()" color="error">удалить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import SystemWorkerPagination from "./../../forms/SystemWorkerPagination";
import Snackbar from "../../facades/Snackbar";
import axios from "axios";

export default {
    name: "workers-index",
    props: {
        roles: {
            required: true,
            type: Array,
        },
    },
    data: function () {
        return {
            paginated: new SystemWorkerPagination({
                current_page: Number(this.$route.query.page),
                per_page: this.$route.query.per_page,
                role_ids: this.$route.query.role_ids
                    ? this.$route.query.role_ids.split(",").map(d => Number(d) || d)
                    : [],
                path: "workers/paginate",
            }),

            deletable: null,
            deleteDialog: false,
            deleteLoading: false,
            confirmPassword: null,

            deletesDialog: false,
            deletesLoading: false,

            window: {
                width: 0,
                height: 0,
                heightDif: 182,
            },
        };
    },
    filters: {
        dateFormat: function (value) {
            return value ? new Date(value).toISOString().slice(0, 10) : "";
        },
    },
    computed: {
        url() {
            return this.$store.state.initUrl;
        },
        phoneMask() {
            return this.$store.state.phoneMask;
        },
        darkMode: {
            get() {
                return this.$store.state.dark;
            },
        },
    },
    watch: {
        "paginated.current_page": function () {
            this.getData();
        },
        "paginated.per_page": function (newVal, oldVal) {
            this.paginated.current_page = 1;
            this.getData();
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.getData();
        },
        "paginated.role_ids": function () {
            this.paginated.current_page = 1;
            this.getData();
        },
    },
    methods: {
        commaJoin(arr, keys) {
            if ("object" === typeof keys) {
                return arr
                    .map(item => {
                        let result = [];
                        Object.keys(keys).forEach(key => {
                            let x = [];
                            keys[key].forEach(value => {
                                x.push(item[key][value]);
                            });
                            result.push(x.join(" "));
                        });
                        return result;
                    })
                    .join(", ");
            } else {
                return keys
                    ? arr
                          .map(item => {
                              return item[keys];
                          })
                          .join(", ")
                    : arr.join(", ");
            }
        },

        getData() {
            let query = {
                page: this.paginated.current_page || undefined,
                per_page: this.paginated.per_page || undefined,
                role_ids: this.paginated.role_ids.length ? this.paginated.role_ids.join(",") : undefined,
                search: this.paginated.search || undefined,
            };
            this.$router.replace({ query }, () => this.paginated.getWorkers);
        },

        confirmDelete(item) {
            this.deletable = item;
            this.deleteDialog = true;
        },

        closeConfirmDelete() {
            this.deletable = null;
            this.deleteDialog = false;
            this.confirmPassword = null;
        },

        confirmDeletes() {
            this.deletesDialog = true;
        },

        closeConfirmDeletes() {
            this.deletesDialog = false;
            this.confirmPassword = null;
        },

        destroy() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    let data = { password: this.confirmPassword, worker_id: this.deletable.system_worker_id };
                    this.deleteLoading = true;
                    axios
                        .post(this.url + "workers/destroy", data)
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.deleteLoading = false;
                            this.closeConfirmDelete();
                            this.paginated.getWorkers;
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.deleteLoading = false;
                        });
                }
            });
        },

        destroyMultiple() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    let data = {
                        password: this.confirmPassword,
                        worker_ids: this.paginated.selected.map(el => el.system_worker_id),
                    };
                    this.deletesLoading = true;
                    axios
                        .post(this.url + "workers/destroy-multiple", data)
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.deletesLoading = false;
                            this.closeConfirmDeletes();
                            this.paginated.getWorkers;
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.deletesLoading = false;
                        });
                }
            });
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },
    },
    created() {
        window.addEventListener("resize", this.handleResize);
        this.handleResize();
    },
};
</script>
