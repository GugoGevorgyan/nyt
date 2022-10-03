<!-- @format -->

<template>
    <v-card tile elevation="4">
        <v-card-title ref="title" class="grey lighten-5">
            {{ workerObj ? `Работник: ${worker.name} ${worker.patronymic} ${worker.surname}` : "Новый работник" }}
        </v-card-title>
        <v-divider />
        <v-card-text style="overflow-y: auto" :style="{ height: height + 'px' }">
            <v-form autocomplete="off">
                <v-row>
                    <v-col cols="12" md="5" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                        <v-subheader>Основная информация</v-subheader>
                        <v-divider class="mb-4" />
                        <v-row no-gutters>
                            <v-col cols="12" md="8">
                                <v-row no-gutters>
                                    <v-col cols="12" md="6" class="pr-2">
                                        <v-text-field
                                            label="Фамилия"
                                            outlined
                                            dense
                                            :error-messages="errors.collect('surname')"
                                            name="surname"
                                            v-model="worker.surname"
                                            v-validate="worker.rules.surname"
                                            data-vv-as="фамилия"
                                        />
                                        <v-text-field
                                            label="Имя"
                                            outlined
                                            dense
                                            :error-messages="errors.collect('name')"
                                            name="name"
                                            v-model="worker.name"
                                            v-validate="worker.rules.name"
                                            data-vv-as="имя"
                                        />
                                        <v-text-field
                                            label="Отчество"
                                            outlined
                                            dense
                                            :error-messages="errors.collect('patronymic')"
                                            name="patronymic"
                                            v-model="worker.patronymic"
                                            v-validate="worker.rules.patronymic"
                                            data-vv-as="отчество"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6" class="pl-2">
                                        <v-text-field
                                            :loading="emailLoading"
                                            outlined
                                            dense
                                            :error-messages="errors.collect('email')"
                                            :persistent-hint="false"
                                            append-icon="mdi-email"
                                            label="Эл. почта"
                                            name="email"
                                            v-model="worker.email"
                                            v-validate="worker.rules.email"
                                            data-vv-as="эл. почта"
                                        />
                                        <v-text-field
                                            label="Телефон"
                                            outlined
                                            dense
                                            :error-messages="errors.collect('phone')"
                                            append-icon="mdi-phone"
                                            name="phone"
                                            v-mask="phoneMask"
                                            v-model="worker.phone"
                                            v-validate="worker.rules.phone"
                                            data-vv-as="телефон"
                                        />
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" md="4" class="d-flex justify-center align-center mb-4">
                                <div style="width: 200px" class="ml-2">
                                    <div
                                        style="overflow: hidden; height: 200px; width: 200px"
                                        class="d-flex justify-center align-center elevation-1 mb-2"
                                    >
                                        <v-img
                                            style="cursor: pointer"
                                            class="elevation-0"
                                            :src="worker.photo ? worker.photo : lazyImage"
                                            @click="$refs.photoInput.click()"
                                            width="200"
                                            height="200"
                                        />
                                    </div>
                                    <small class="red--text" v-if="errors.first('photo_file')">
                                        {{ errors.first("photo_file") }}
                                    </small>
                                </div>

                                <input
                                    ref="photoInput"
                                    class="d-none"
                                    type="file"
                                    accept="image/*"
                                    @change="previewImage($event, 'photo')"
                                    name="photo_file"
                                    v-validate="worker.rules.photo_file"
                                    :error-messages="errors.collect('photo_file')"
                                    data-vv-as="фотография"
                                />
                            </v-col>
                        </v-row>
                        <v-textarea
                            outlined
                            dense
                            :error-messages="errors.collect('worker.description')"
                            :persistent-hint="false"
                            auto-grow
                            filled
                            label="Примечания о работнике"
                            name="description"
                            prepend-inner-icon="mdi-notebook-multiple"
                            v-model="worker.description"
                            v-validate="worker.rules.description"
                            data-vv-as="примечания"
                        />

                        <v-subheader>Данные входа в систему</v-subheader>
                        <v-divider class="mb-4"></v-divider>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    :loading="nicknameLoading"
                                    label="Ник"
                                    outlined
                                    dense
                                    :error-messages="errors.collect('nickname')"
                                    :persistent-hint="false"
                                    name="nickname"
                                    v-model="worker.nickname"
                                    v-validate="worker.rules.nickname"
                                    data-vv-as="ник"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <template v-if="workerObj">
                                    <v-switch
                                        class="mt-0"
                                        v-model="worker.change_password"
                                        label="Изменить пароль"
                                    ></v-switch>
                                </template>
                                <template v-if="!workerObj || worker.change_password">
                                    <div class="d-flex">
                                        <v-text-field
                                            outlined
                                            dense
                                            :error-messages="errors.collect('password')"
                                            :persistent-hint="false"
                                            append-icon="mdi-lock-question"
                                            label="Пароль"
                                            name="password"
                                            v-model="worker.password"
                                            v-validate="worker.rules.password"
                                            data-vv-as="пароль"
                                            ref="password"
                                            class="mr-2"
                                        />
                                        <v-tooltip right>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn v-on="on" @click="generatePassword" color="primary" fab small>
                                                    <v-icon>mdi-hammer-screwdriver</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Сгенерерировать пароль</span>
                                        </v-tooltip>
                                    </div>
                                </template>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="12" md="7">
                        <v-subheader>Роли и возможности</v-subheader>
                        <v-divider class="mb-4"></v-divider>
                        <v-row>
                            <v-col cols="12" md="12">
                                <v-autocomplete
                                    v-model="worker.role_ids"
                                    :items="selectableRoles"
                                    label="Роли работника"
                                    item-text="text"
                                    item-value="role_id"
                                    multiple
                                    color="yellow darken-3"
                                    data-vv-as="роли"
                                    name="role_ids"
                                    outlined
                                    dense
                                    prepend-icon="mdi-package-variant"
                                    v-validate="worker.rules.role_ids"
                                    :error-messages="errors.collect(`role_ids`)"
                                >
                                    <template v-slot:prepend-item>
                                        <v-btn
                                            v-if="!worker.role_ids.length"
                                            class="ml-3"
                                            color="primary"
                                            outlined
                                            tile
                                            depressed
                                            small
                                            @click="selectAllRoles"
                                            v-text="'выбрать все'"
                                        />
                                        <v-btn
                                            v-else
                                            class="ml-3"
                                            color="secondary"
                                            outlined
                                            tile
                                            depressed
                                            small
                                            @click="deleteAllRoles"
                                            v-text="'снять все'"
                                        />
                                    </template>

                                    <!-- Template for render selected data -->
                                    <template v-slot:selection="{ item, index }">
                                        <v-chip small v-if="6 > index">
                                            <span>{{ item.text }}</span>
                                        </v-chip>
                                        <span v-if="6 === index" class="grey--text caption">
                                            (+{{ worker.role_ids.length - 6 }} других)
                                        </span>
                                        <v-icon color="grey" v-if="6 === index" v-text="'mdi-magnify'" />
                                    </template>

                                    <!-- Template for render data when the select is expanded -->
                                    <template slot="item" slot-scope="data">
                                        <!-- Normal item -->
                                        <template>
                                            <v-list-item-content>
                                                <v-list-item-title>{{ data.item.text }}</v-list-item-title>
                                                <v-list-item-subtitle>{{ data.item.group }}</v-list-item-subtitle>
                                            </v-list-item-content>
                                        </template>
                                    </template>
                                </v-autocomplete>
                                <v-row justify="end">
                                    <v-col cols="12" md="11">
                                        <v-row v-if="isOperator">
                                            <v-col cols="12" md="6">
                                                <template v-if="subPhones.length">
                                                    <v-alert
                                                        v-if="worker.operator_sub_phone_id"
                                                        type="success"
                                                        outlined
                                                        dense
                                                    >
                                                        <small>Внутренний номер оператора установлен</small>
                                                    </v-alert>
                                                    <v-alert v-else type="info" outlined dense>
                                                        <small>Выберите внутренний номер оператора</small>
                                                    </v-alert>
                                                </template>
                                                <v-alert v-else type="error" outlined dense>
                                                    <small>
                                                        Франшиза не имеет доступных внутренних телефонов! Попрасите
                                                        администратора зарегистрировать их для Вас, прежде чем
                                                        регистрировать оператора.
                                                    </small>
                                                </v-alert>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                                <v-select
                                                    :items="subPhones"
                                                    item-text="number"
                                                    item-value="franchise_sub_phone_id"
                                                    :error-messages="errors.collect('operator_sub_phone_id')"
                                                    data-vv-validate-on="blur"
                                                    label="Внутрнний номер оператор"
                                                    name="operator_sub_phone_id"
                                                    v-model="worker.operator_sub_phone_id"
                                                    v-validate="worker.rules.operator_sub_phone_id"
                                                    outlined
                                                    dense
                                                    data-vv-as="внутрнний номер оператора"
                                                />
                                            </v-col>
                                        </v-row>
                                        <v-row v-if="isDispatcher">
                                            <v-col cols="12" md="6">
                                                <template v-if="subPhones.length">
                                                    <v-alert
                                                        v-if="worker.dispatcher_sub_phone_id"
                                                        type="success"
                                                        outlined
                                                        dense
                                                    >
                                                        <small>Внутренний номер диспетчера установлен</small>
                                                    </v-alert>
                                                    <v-alert v-else type="info" outlined dense>
                                                        <small>Выберите внутренний номер диспетчера</small>
                                                    </v-alert>
                                                </template>
                                                <v-alert v-else type="error" outlined dense>
                                                    <small>
                                                        Франшиза не имеет доступных внутренних телефонов! Попрасите
                                                        администратора зарегистрировать их для Вас, прежде чем
                                                        регистрировать диспетчера.
                                                    </small>
                                                </v-alert>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                                <v-select
                                                    :items="subPhones"
                                                    item-text="number"
                                                    item-value="franchise_sub_phone_id"
                                                    :error-messages="errors.collect('dispatcher_sub_phone_id')"
                                                    data-vv-validate-on="blur"
                                                    label="Внутренний номер диспетчера"
                                                    name="dispatcher_sub_phone_id"
                                                    v-model="worker.dispatcher_sub_phone_id"
                                                    v-validate="worker.rules.dispatcher_sub_phone_id"
                                                    outlined
                                                    dense
                                                    data-vv-as="внутренний номер диспетчера"
                                                />
                                            </v-col>
                                        </v-row>
                                        <div class="mb-4">
                                            <span class="title black--text mr-2">Возможности</span>
                                        </div>

                                        <div
                                            class="pa-1"
                                            :style="{ height: '431px' }"
                                            style="height: 515px; border-radius: 4px; border: 1px dashed gray"
                                        >
                                            <div class="pa-1" style="height: 100%; overflow-y: auto">
                                                <!-- permissions -->
                                                <template v-if="worker.role_ids.length">
                                                    <worker-role-permissions
                                                        v-for="roleId in worker.role_ids"
                                                        :key="roleId"
                                                        :url="url"
                                                        :role="findModuleRole(roleId)"
                                                        :role_permission_ids="worker.role_permissions[roleId] || []"
                                                        :update-mode="preventRoles.includes(roleId)"
                                                        :validate="worker.rules.role_permissions"
                                                        @updatePermissions="updateRolePermissions"
                                                    />
                                                </template>
                                                <div
                                                    v-else
                                                    class="d-flex justify-center align-center"
                                                    style="height: 100%; width: 100%"
                                                >
                                                    <span class="title">Выберите роли работника</span>
                                                </div>
                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions ref="actions">
            <v-spacer />
            <v-btn
                tile
                :loading="loading"
                @click="submit()"
                color="yellow darken-3"
                v-text="this.workerObj ? 'обновить' : 'сохранить'"
            />
        </v-card-actions>
    </v-card>
</template>
<script>
import WorkerRolePermissions from "./WorkerRolePermissions";
import Snackbar from "../../facades/Snackbar";
import axios from "axios";
import SystemWorker from "../../models/SystemWorker";

export default {
    name: "WorkerForm",

    components: { WorkerRolePermissions },

    props: {
        workerObj: {
            required: true,
        },
        moduleRoles: {
            required: true,
            type: Array,
        },
        subPhones: {
            required: true,
            type: Array,
        },
    },

    data() {
        return {
            preventRoles: [],
            selectableRoles: [],

            worker: new SystemWorker(this.workerObj || {}),
            height: 0,

            lazyImage: "/storage/img/camera.png",
            loading: false,
            emailLoading: false,
            nicknameLoading: false,

            operator_api_id: null,
            operator_web_id: null,
            dispatcher_api_id: null,
            dispatcher_web_id: null,

            timer_nickname: null,
            nickname_cancel_token_source: axios.CancelToken.source()
        };
    },

    mounted() {
        this.height = window.innerHeight - this.$refs.title.clientHeight - this.$refs.actions.clientHeight - 83;
    },

    watch: {
        "worker.email": function () {
            this.emailLoading = true;

            let data = {
                email: this.worker.email,
                table: "system_workers",
                col: "email",
                primary: "system_worker_id",
                primary_value: this.worker.system_worker_id,
            };

            axios
                .post(this.url + "check/unique", data)
                .then(response => {
                    this.emailLoading = false;
                    if (!response.data.valid) {
                        this.errors.add({
                            field: "email",
                            msg: response.data.data.message,
                        });
                    }
                })
                .catch(error => {
                    this.emailLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
        "worker.nickname": function () {
            this.nickname_cancel_token_source.cancel();
            this.nickname_cancel_token_source = axios.CancelToken.source();

            clearTimeout(this.timer_nickname);

            this.timer_nickname = setTimeout(() => {
                this.nicknameLoading = true;

                let data = {
                    nickname: this.worker.nickname,
                    table: "system_workers",
                    col: "nickname",
                    primary: "system_worker_id",
                    primary_value: this.worker.system_worker_id,
                };

                axios
                    .post(this.url + "check/unique", data, {
                        cancelToken: this.nickname_cancel_token_source.token
                    })
                    .then(response => {
                        this.nicknameLoading = false;

                        if (!response.data.valid) {
                            this.errors.add({
                                field: "nickname",
                                msg: response.data.data.message
                            });
                        }
                    })
                    .catch(error => {
                        this.nicknameLoading = false;
                        Snackbar.error(error.response.data.message);
                    });
            }, 500);
        },
        "worker.role_ids": function () {
            for (const [key] of Object.entries(this.worker.role_permissions)) {
                if (!this.worker.role_ids.includes(Number(key))) {
                    delete this.worker.role_permissions[key];
                    this.removePreventRole(key);
                }
            }
        },

        isDispatcher() {
            if (!this.isDispatcher) {
                this.worker.dispatcher_sub_phone_id = null;
            }
        },
        isOperator() {
            if (!this.isOperator) {
                this.worker.operator_sub_phone_id = null;
            }
        },
    },

    computed: {
        url() {
            return this.$store.state.initUrl;
        },
        isOperator() {
            if (this.operator_api_id || this.operator_web_id) {
                return (
                    this.worker.role_ids.includes(this.operator_api_id) ||
                    this.worker.role_ids.includes(this.operator_web_id)
                );
            } else {
                return false;
            }
        },
        isDispatcher() {
            if (this.dispatcher_api_id || this.dispatcher_web_id) {
                return (
                    this.worker.role_ids.includes(this.dispatcher_api_id) ||
                    this.worker.role_ids.includes(this.dispatcher_web_id)
                );
            } else {
                return false;
            }
        },
        phoneMask() {
            return this.$store.state.phoneMask;
        }
    },

    methods: {
        removePreventRole(role_id) {
            let index = this.preventRoles.findIndex(item => item === Number(role_id));
            if (~index) {
                this.preventRoles.splice(index, 1);
            }
        },

        selectAllRoles() {
            this.moduleRoles.forEach(item => {
                item.franchise_roles.forEach(roles => {
                    this.worker.role_ids.push(roles.role.role_id);
                });
            });
        },

        deleteAllRoles() {
            this.worker.role_ids = [];
        },

        submit() {
            this.loading = true;

            if(this.errors.any()){
                this.loading = false;
                return;
            }

            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.workerObj ? this.update() : this.store();
                } else {
                    this.loading = false;
                }
            });
        },

        update() {
            this.worker
                .update({ worker: this.worker.system_worker_id })
                .then(response => {
                    Snackbar.info(response.data.message);
                    this.loading = false;
                    window.location = this.url + "workers";
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.loading = false;
                });
        },

        store() {
            this.worker
                .store()
                .then(response => {
                    Snackbar.info(response.data.message);
                    this.loading = false;
                    window.location = this.url + "workers";
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.loading = false;
                });
        },

        generatePassword() {
            this.worker.password =
                Math.random().toString(32).substring(2, 6) + Math.random().toString(32).substring(2, 6);
        },

        setSelectableRoles() {
            this.moduleRoles.forEach(moduleItem => {
                this.selectableRoles.push({ header: moduleItem.module.text });
                moduleItem.franchise_roles.forEach(roleItem => {
                    let { ...role } = roleItem.role;
                    role.group = moduleItem.module.text;
                    this.selectableRoles.push(role);

                    /*define ids*/
                    switch (role.name) {
                        case "operator_web":
                            this.operator_web_id = role.role_id;
                            break;
                        case "operator_api":
                            this.operator_api_id = role.role_id;
                            break;
                        case "dispatcher_web":
                            this.dispatcher_web_id = role.role_id;
                            break;
                        case "dispatcher_api":
                            this.dispatcher_api_id = role.role_id;
                            break;
                    }
                });
            });
        },

        findModuleRole(role_id) {
            return this.selectableRoles.find(item => item.role_id && item.role_id === role_id);
        },

        updateRolePermissions(roleObj) {
            this.worker.role_permissions[roleObj.role] = roleObj.permissions;
        },

        previewImage(event, key) {
            let reader = new FileReader();

            reader.onload = e => {
                this.worker[key] = e.target.result;
            };

            if (event) {
                reader.readAsDataURL(event.target.files[0]);
                this.worker.photo_file = event.target.files[0];
            } else {
                this.worker.photo = this.lazyImage;
            }

            return true;
        },

        setUpdateValues() {
            /*set selected roles*/
            this.workerObj.worker_roles.forEach(item => {
                this.worker.role_ids.push(item.role_id);
                this.preventRoles.push(item.role_id);
                let permissionIds = [];
                item.worker_permissions.forEach(permission => {
                    permissionIds.push(permission.permission_id);
                });
                this.worker.role_permissions[item.role_id] = permissionIds;
            });
        },
    },

    created() {
        if (this.workerObj) {
            this.setUpdateValues();
        }
        this.setSelectableRoles();
    },
};
</script>
