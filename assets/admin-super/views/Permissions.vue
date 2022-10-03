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
                        item-key="permission_id"
                        show-select
                        v-model="paginated.selected"
                        :height="window.height"
                    >
                        <template v-slot:top>
                            <v-toolbar color="white" flat>
                                <v-toolbar-title>Permissions</v-toolbar-title>
                                <v-divider class="mx-3" inset vertical />
                                <v-spacer />
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
                                <v-spacer/>
                                <v-scale-transition>
                                    <v-btn color="orange" depressed fab icon large @click.stop="create">
                                        <v-icon>mdi-plus-circle</v-icon>
                                    </v-btn>
                                </v-scale-transition>
                            </v-toolbar>
                        </template>

                        <template v-slot:item.role="{ item }">
                            <v-chip v-if="item.role" color="orange" dark outlined small class="mx-1 my-1">
                                {{ item.role.name }}
                            </v-chip>
                            <v-chip v-else color="error" dark outlined small class="mx-1 my-1"> No role </v-chip>
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
                            <v-divider class="ma-0" />
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
                                        />
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
                                        />
                                        <p class="caption">rows per page:</p>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </template>
                    </v-data-table>
                </v-card>

                <v-dialog v-model="dialog" max-width="500" width="100%" persistent eager>
                    <v-card v-if="permission">
                        <v-card-title>
                            <span class="title">Permission</span>
                        </v-card-title>
                        <v-card-text>
                            <v-text-field
                                v-model="permission.name"
                                v-validate="permission.rules.name"
                                :error-messages="errors.collect('name')"
                                label="Permission name*"
                                color="yellow darken-3"
                                name="name"
                                outlined
                                required
                            />

                            <v-text-field
                                v-model="permission.alias"
                                v-validate="permission.rules.alias"
                                :error-messages="errors.collect('alias')"
                                label="Permission alias*"
                                color="yellow darken-3"
                                name="alias"
                                outlined
                                required
                            />

                            <v-autocomplete
                                v-model="permission.role_id"
                                v-validate="permission.rules.role_id"
                                :error-messages="errors.collect('role_id')"
                                hint="For what role permission is provided"
                                label="Permission WorkerRole name*"
                                color="yellow darken-3"
                                item-value="role_id"
                                item-text="name"
                                data-vv-as="role"
                                name="role_id"
                                :items="roles"
                                outlined
                            />

                            <v-select
                                v-model="permission.guard_name"
                                v-validate="permission.rules.guard_name"
                                :error-messages="errors.collect('guard_name')"
                                hint="example of helper text only on focus"
                                label="Permission guard name*"
                                color="yellow darken-3"
                                data-vv-as="guard name"
                                name="guard_name"
                                :items="guards"
                                outlined
                            />

                            <v-textarea
                                v-model="permission.description"
                                v-validate="permission.rules.description"
                                :error-messages="errors.collect('description')"
                                label="Description for permission"
                                color="yellow darken-3"
                                name="description"
                                outlined
                            />

                            <small>*indicates required field</small>
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
import Snackbar from "../facades/Snackbar";
import Permission from "../models/Permission";
import PermissionsPagination from "../forms/PermissionsPagination";

export default {
    name: "Permissions",
    props: {
        roles: {
            required: true,
            type: Array,
            default: () => [],
        },
        laravelGuards: {
            required: true,
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            mode: null,
            selected: [],
            dialog: false,
            confirmation: false,
            touchable: null,
            permission: new Permission(),
            guards: this.laravelGuards.map(guard => ({
                text: _.upperCase(guard),
                value: guard,
            })),
            window: {
                width: 0,
                height: 0,
                heightDif: 270,
            },
            paginated: new PermissionsPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"])
                },
                "permissions/paginate",
            ),
        };
    },
    filters: {
        capitalize(value) {
            return _.upperCase(value);
        },
    },
    computed: {
        btnText() {
            switch (this.mode) {
                case Permission.MODE_CREATE:
                    return "Create";
                case Permission.MODE_UPDATE:
                    return "Update";
                default:
                    return "Create";
            }
        },
    },
    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "admin.super.permissions",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search
                    },
                },
                () => {
                    this.paginated.getPermissions();
                },
            );
        },
        "paginated.per_page": function () {
            this.$router.push(
                {
                    name: "admin.super.permissions",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search
                    },
                },
                () => {
                    this.paginated.getPermissions();
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "admin.super.permissions",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search
                    },
                },
                () => {
                    this.paginated.getPermissions();
                },
            );
        },
        "paginated.selected": function () {
            this.selected = [];
            this.paginated.selected.forEach(item => {
                this.selected.push(item.permission_id);
            });
        },
    },
    methods: {
        cancel() {
            this.mode = null;
            this.touchable = null;
            this.dialog = false;
            this.confirmation = false;
            this.permission = new Permission();
        },
        create() {
            this.dialog = true;
            this.mode = Permission.MODE_CREATE;
            this.permission = new Permission();
        },
        edit(permission) {
            let index = this.paginated.data.findIndex(item => {
                return permission === item;
            });
            this.dialog = true;
            this.touchable = index;
            this.mode = Permission.MODE_UPDATE;
            this.permission = new Permission(permission);
        },
        confirm(permission) {
            let index = this.paginated.data.findIndex(item => {
                return permission === item;
            });
            this.confirmation = true;
            this.touchable = index;
            this.mode = Permission.MODE_DELETE;
            this.permission = new Permission(permission);
        },
        store() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.permission
                        .store()
                        .then(response => {
                            if (response.status === 200) {
                                this.paginated.getPermissions();
                                this.cancel();

                                Snackbar.success(response.data.message);
                            } else {
                                Snackbar.error("Something went's wrong!");
                            }
                        })
                        .catch(error => {
                            Permission.errors(error.response).forEach(error => this.errors.add(error));

                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
        update() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.permission
                        .update({ permission: this.permission.permission_id })
                        .then(response => {
                            if (response.status === 200) {
                                this.paginated.getPermissions();
                                this.cancel();

                                Snackbar.success(response.data.message);
                            } else {
                                Snackbar.error("Something went's wrong!");
                            }
                        })
                        .catch(error => {
                            Permission.errors(error.response).forEach(error => this.errors.add(error));

                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
        destroy() {
            this.permission
                .delete({ permission: this.permission.permission_id })
                .then(response => {
                    if (response.status === 200) {
                        this.paginated.getPermissions();
                        this.cancel();

                        Snackbar.success(response.data.message);
                    } else {
                        Snackbar.error("Something went's wrong!");
                    }
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        destroyMultiple() {
            axios
                .delete("/admin/super/permissions/multiple", {
                    params: { ids: this.selected },
                })
                .then(response => {
                    if (response.status === 200) {
                        this.paginated.getPermissions();
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
    created() {
        this.paginated.getPermissions();
        window.addEventListener("resize", this.handleResize);
        this.handleResize();
    },
};
</script>

<style scoped></style>
