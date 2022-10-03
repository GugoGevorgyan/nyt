<!-- @format -->

<template>
    <v-container fill-height fluid grid-list-md>
        <v-layout row no-wrap>
            <v-flex xs12>
                <v-card outlined>
                    <v-data-table
                        :headers="paginated.headers"
                        :items="paginated.data"
                        :items-per-page="paginated.per_page"
                        :loading="paginated.loading"
                        fixed-header
                        hide-default-footer
                        item-key="role_id"
                        show-select
                        v-model="paginated.selected"
                        :height="window.height"
                    >
                        <template v-slot:top>
                            <v-toolbar color="white" flat>
                                <v-toolbar-title>Roles</v-toolbar-title>
                                <v-divider class="mx-3" inset vertical></v-divider>
                                <v-spacer></v-spacer>
                                <v-text-field
                                    append-icon="mdi-magnify"
                                    clearable
                                    color="yellow darken-3"
                                    hide-details
                                    label="Search"
                                    single-line
                                    solo
                                    v-model="paginated.search"
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-scale-transition>
                                    <v-btn color="orange" depressed fab icon large @click.stop="create">
                                        <v-icon>mdi-plus-circle</v-icon>
                                    </v-btn>
                                </v-scale-transition>
                            </v-toolbar>
                        </template>

                        <template v-slot:item.module="{ item }">
                            <v-chip v-if="item.module" color="orange" dark outlined small class="mx-1 my-1">
                                {{ item.module.name }}
                            </v-chip>
                            <v-chip v-else color="error" dark outlined small class="mx-1 my-1"> No module </v-chip>
                        </template>

                        <template v-slot:item.action="{ item }">
                            <v-btn @click.stop="edit(item)" icon color="primary" depressed>
                                <v-icon>mdi-pencil-outline</v-icon>
                            </v-btn>
                            <v-btn color="error" icon depressed @click.stop="confirm(item)">
                                <v-icon>mdi-delete-outline</v-icon>
                            </v-btn>
                        </template>

                        <template v-slot:footer>
                            <v-divider />
                            <v-layout align-content-ceneter no-wrap row>
                                <v-flex align-self-center xs2>
                                    <v-btn
                                        @click="destroyMultiple()"
                                        class="ml-5"
                                        color="error"
                                        depressed
                                        fab
                                        v-if="paginated.selected.length"
                                    >
                                        <v-icon>mdi-trash-can-outline</v-icon>
                                    </v-btn>
                                </v-flex>

                                <!--PAGINATION LAST PAGE-->
                                <v-flex align-self-center xs8>
                                    <div class="text-xs-center">
                                        <v-pagination
                                            :length="paginated.last_page"
                                            :total-visible="7"
                                            circle
                                            color="yellow darken-3"
                                            v-model="paginated.current_page"
                                        ></v-pagination>
                                        <p v-if="paginated.total" class="caption mb-0 text-center">
                                            {{ `${paginated.from}-${paginated.to} of ${paginated.total}` }}
                                        </p>
                                    </div>
                                </v-flex>
                                <v-flex align-self-center xs1>
                                    <div class="text-xs-center">
                                        <v-overflow-btn
                                            :items="[10, 15, 25, 50]"
                                            color="yellow darken-3"
                                            hide-details
                                            label="Rows per page"
                                            v-model="paginated.per_page"
                                        ></v-overflow-btn>
                                        <p class="caption">rows per page:</p>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </template>
                    </v-data-table>
                </v-card>

                <v-dialog v-model="dialog" max-width="500" width="100%" persistent eager>
                    <v-card>
                        <v-card-title>
                            <span class="title">WorkerRole</span>
                        </v-card-title>

                        <v-card-text>
                            <v-text-field
                                v-model="role.name"
                                v-validate="role.rules.name"
                                :error-messages="errors.collect('name')"
                                autocomplete="off"
                                label="WorkerRole name*"
                                color="yellow darken-3"
                                name="name"
                                outlined
                            />
                            <v-text-field
                                v-model="role.alias"
                                v-validate="role.rules.alias"
                                :error-messages="errors.collect('alias')"
                                autocomplete="off"
                                label="WorkerRole alias*"
                                color="yellow darken-3"
                                name="alias"
                                outlined
                            />

                            <v-autocomplete
                                v-model="role.module_id"
                                v-validate="role.rules.module_id"
                                :error-messages="errors.collect('module_id')"
                                :items="modules"
                                item-value="module_id"
                                item-text="name"
                                data-vv-as="module"
                                name="module_id"
                                color="yellow darken-3"
                                label="WorkerRole Module*"
                                hint="Module for what WorkerRole is envisaged!"
                                persistent-hint
                                outlined
                            />

                            <v-select
                                v-model="role.guard_name"
                                v-validate="role.rules.guard_name"
                                :error-messages="errors.collect('guard_name')"
                                :items="guards"
                                hint="example of helper text only on focus"
                                color="yellow darken-3"
                                label="WorkerRole Guard name*"
                                data-vv-as="guard name"
                                name="guard_name"
                                outlined
                            />

                            <v-textarea
                                v-model="role.description"
                                v-validate="role.rules.description"
                                :error-messages="errors.collect('description')"
                                label="WorkerRole Description"
                                color="yellow darken-3"
                                name="description"
                                outlined
                            />

                            <small class="mt-2">*indicates required field</small>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn text @click="cancel">Cancel</v-btn>
                            <v-btn dark color="yellow darken-3" v-on="{ click: mode === 1 ? store : update }">
                                {{ btnText }}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-dialog v-model="confirmation" max-width="300" width="100%" persistent eager>
                    <v-card>
                        <v-card-title>Are you sure to delete?</v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn text @click="cancel">Cancel</v-btn>
                            <v-btn dark color="error" @click="destroy">Delete</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import _ from "lodash";
import axios from "axios";
import Role from "../models/Role";
import RolePagination from "../forms/RolesPagination";
import Snackbar from "../facades/Snackbar";

export default {
    name: "Roles",
    props: {
        modules: {
            required: true,
            type: Array,
        },
        laravelGuards: {
            required: true,
            type: Array,
        },
    },
    computed: {
        btnText() {
            switch (this.mode) {
                case Role.MODE_CREATE:
                    return "Create";
                case Role.MODE_UPDATE:
                    return "Update";
                default:
                    return "Create";
            }
        },
    },

    data() {
        return {
            role: new Role(),
            confirmation: false,
            dialog: false,
            mode: null,
            selected: [],
            guards: [],
            paginated: new RolePagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"])
                },
                "roles/paginate",
            ),
            window: {
                width: 0,
                height: 0,
                heightDif: 270,
            },
        };
    },
    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "admin.super.roles",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search
                    },
                },
                () => {
                    this.paginated.getRoles();
                },
            );
        },
        "paginated.per_page": function () {
            this.$router.push(
                {
                    name: "admin.super.roles",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search
                    },
                },
                () => {
                    this.paginated.getRoles();
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "admin.super.roles",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search
                    },
                },
                () => {
                    this.paginated.getRoles();
                },
            );
        },
        "paginated.selected": function () {
            this.selected = [];
            this.paginated.selected.forEach(item => {
                this.selected.push(item.role_id);
            });
        },
    },
    methods: {
        cancel() {
            this.role = new Role();
            this.dialog = false;
            this.confirmation = false;
        },
        create() {
            this.dialog = true;
            this.role = new Role();
            this.mode = Role.MODE_CREATE;
        },
        edit(role) {
            this.dialog = true;
            this.mode = Role.MODE_UPDATE;
            this.role = new Role(role);
        },
        confirm(role) {
            this.confirmation = true;
            this.mode = Role.MODE_DELETE;
            this.role = new Role(role);
        },
        store() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.role
                        .store()
                        .then(response => {
                            if (response.status === 200) {
                                this.paginated.getRoles();
                                this.cancel();
                            }

                            Snackbar.success(response.data.message);
                        })
                        .catch(error => {
                            Role.errors(error.response).forEach(error => this.errors.add(error));

                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
        update() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.role
                        .update({ role: this.role.role_id })
                        .then(response => {
                            if (response.status === 200) {
                                this.paginated.getRoles();
                                this.cancel();

                                Snackbar.success(response.data.message);
                            }
                        })
                        .catch(error => {
                            Role.errors(error.response).forEach(error => this.errors.add(error));

                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
        destroy() {
            this.role
                .delete({ role: this.role.role_id })
                .then(response => {
                    if (response.status === 200) {
                        this.paginated.getRoles();
                        this.cancel();
                    }

                    Snackbar.show(response.data.message, response.data.success ? "success" : "error");
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        destroyMultiple() {
            axios
                .delete("/admin/super/roles/multiple", {
                    params: { ids: this.selected },
                })
                .then(response => {
                    if (response.status === 200) {
                        this.paginated.getRoles();
                        this.selected = [];

                        Snackbar.success(response.data.message);
                    }
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.window.heightDif;
        },
    },
    filters: {
        capitalize(value) {
            return _.upperCase(value);
        },
    },
    created() {
        this.paginated.getRoles();
        this.guards = this.laravelGuards.map(guard => ({ text: _.upperCase(guard), value: guard }));
        window.addEventListener("resize", this.handleResize);
        this.handleResize();
    },
};
</script>
