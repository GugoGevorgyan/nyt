<!-- @format -->

<template>
    <v-container fill-height fluid grid-list-md>
        <v-layout row no-wrap fill-height>
            <v-flex xs12>
                <v-card class="hide-overflow" outlined flat :height="window.height">
                    <v-app-bar scroll-target="#modules-bar" shrink-on-scroll absolute>
                        <v-app-bar-nav-icon v-if="!selected.length"></v-app-bar-nav-icon>

                        <v-btn v-else icon @click="selected = []">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>

                        <v-toolbar-title>
                            {{ selected.length ? `${selected.length} selected` : 'Modules' }}
                        </v-toolbar-title>

                        <v-spacer></v-spacer>

                        <v-slide-x-reverse-transition>
                            <v-text-field
                                v-if="searchable"
                                v-model="search"
                                @click:append="disableSearch"
                                append-icon="mdi-magnify"
                                color="yellow darken-3"
                                label="search"
                                class="mr-2"
                                hide-details
                                single-line
                                clearable
                                outlined
                                rounded
                            ></v-text-field>
                        </v-slide-x-reverse-transition>

                        <v-scale-transition>
                            <v-btn icon v-if="selected.length" @click="destroyMultiple">
                                <v-icon>mdi-delete-circle</v-icon>
                            </v-btn>
                        </v-scale-transition>
                        <v-scale-transition>
                            <v-btn v-if="!selected.length && !searchable" @click="enableSearch" icon>
                                <v-icon>mdi-magnify</v-icon>
                            </v-btn>
                        </v-scale-transition>
                        <v-scale-transition>
                            <v-btn v-if="!selected.length" icon @click.stop="create">
                                <v-icon>mdi-plus-circle</v-icon>
                            </v-btn>
                        </v-scale-transition>
                    </v-app-bar>

                    <v-sheet id="modules-bar" class="scroll-y" :height="window.height">
                        <v-container grid-list-lg style="margin-top: 128px">
                            <v-item-group v-if="filtered.length" v-model="selected" multiple>
                                <v-layout wrap>
                                    <v-flex v-for="(module, i) in filtered" :key="module.module_id" lg3 md3 sm4 xs6>
                                        <v-item v-slot:default="{ active, toggle }" :value="module.module_id">
                                            <v-card @click="toggle" :color="active ? 'yellow darken-2' : ''" :dark="active">
                                                <v-card-title class="text-truncate justify-space-between align-content-center">
                                                    <div class="title" style="white-space: normal">
                                                        {{ module.name | capitalize }}
                                                    </div>
                                                </v-card-title>

                                                <v-card-text class="pb-0" style="white-space: normal">
                                                    {{ module.slug_name | capitalize }}
                                                </v-card-text>

                                                <v-card-actions>
                                                    <v-checkbox
                                                        v-model="selected"
                                                        :value="module.module_id"
                                                        color="yellow darken-4"
                                                        class="mt-0 pt-0 pl-2"
                                                        hide-details
                                                        readonly
                                                    ></v-checkbox>
                                                    <v-spacer></v-spacer>
                                                    <v-btn icon @click.stop="module.showRoles = !module.showRoles">
                                                        <v-icon>{{ module.showRoles ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                                                    </v-btn>
                                                    <v-btn color="default" icon @click.stop="edit(module, i)"
                                                        ><v-icon>mdi-pencil-outline</v-icon></v-btn
                                                    >
                                                    <v-btn color="error" icon @click.stop="confirm(module, i)"
                                                        ><v-icon>mdi-delete-outline</v-icon></v-btn
                                                    >
                                                </v-card-actions>

                                                <v-expand-transition>
                                                    <v-list shaped subheader v-if="module.showRoles">
                                                        <v-subheader>Module Associated Roles</v-subheader>
                                                        <!-- Associated roles -->
                                                        <v-list-item v-for="(role, r) in module.roles" :key="role.role_id">
                                                            <v-list-item-content>
                                                                <v-list-item-title v-html="role.name"></v-list-item-title>
                                                            </v-list-item-content>
                                                            <v-list-item-action>
                                                                <v-tooltip right>
                                                                    <template v-slot:activator="{ on }">
                                                                        <v-btn
                                                                            color="pink"
                                                                            icon
                                                                            v-on="on"
                                                                            @click.stop="dissociateRole(module, role, r)"
                                                                        >
                                                                            <v-icon>mdi-link-variant-off</v-icon>
                                                                        </v-btn>
                                                                    </template>
                                                                    <span><strong>Dissociate role</strong></span>
                                                                </v-tooltip>
                                                            </v-list-item-action>
                                                        </v-list-item>

                                                        <v-divider></v-divider>

                                                        <v-subheader>Vacant Roles</v-subheader>

                                                        <!-- Roles that has belongs to any module  -->
                                                        <v-list-item v-for="(role, r) in roles" :key="role.role_id">
                                                            <v-list-item-content>
                                                                <v-list-item-title v-html="role.name"></v-list-item-title>
                                                            </v-list-item-content>
                                                            <v-list-item-action>
                                                                <v-tooltip left>
                                                                    <template v-slot:activator="{ on }">
                                                                        <v-btn
                                                                            color="purple"
                                                                            icon
                                                                            v-on="on"
                                                                            @click.stop="associateRole(module, role, r)"
                                                                        >
                                                                            <v-icon>mdi-link-variant</v-icon>
                                                                        </v-btn>
                                                                    </template>
                                                                    <span><strong>Associate role</strong></span>
                                                                </v-tooltip>
                                                            </v-list-item-action>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-expand-transition>
                                            </v-card>
                                        </v-item>
                                    </v-flex>
                                </v-layout>
                            </v-item-group>
                        </v-container>
                    </v-sheet>
                </v-card>

                <v-dialog v-model="dialog" max-width="500" width="100%" persistent eager>
                    <v-card>
                        <v-card-title>
                            <span class="title">Module</span>
                        </v-card-title>
                        <v-card-text>
                            <v-text-field
                                v-model="module.name"
                                v-validate="module.rules.name"
                                :error-messages="errors.collect('name')"
                                label="Module name*"
                                color="yellow darken-3"
                                name="name"
                                outlined
                                required
                            ></v-text-field>
                            <v-text-field
                                v-model="module.alias"
                                v-validate="module.rules.alias"
                                :error-messages="errors.collect('alias')"
                                label="Module alias*"
                                color="yellow darken-3"
                                name="alias"
                                outlined
                                required
                            ></v-text-field>
                            <v-textarea
                                v-model="module.description"
                                v-validate="module.rules.description"
                                :error-messages="errors.collect('description')"
                                label="Description for module*"
                                color="yellow darken-3"
                                name="description"
                                outlined
                            ></v-textarea>

                            <small class="mt-2">*indicates required field</small>

                            <v-switch
                                v-model="module.default"
                                v-validate="module.rules.default"
                                :error-messages="errors.collect('default')"
                                :false-value="0"
                                :true-value="1"
                                hint="Is module included by default"
                                color="yellow darken-3"
                                label="Is default"
                                name="default"
                                persistent-hint
                            ></v-switch>
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
import _ from 'lodash';
import axios from 'axios';
import WorkerRole from '../models/Role';
import Module from '../models/Module';
import Role from '../models/Role';
import Snackbar from '../facades/Snackbar';

export default {
    name: 'Modules',
    props: {
        items: {
            required: true,
            type: Array,
        },
        roleItems: {
            required: true,
            type: Array,
        },
    },
    computed: {
        btnText() {
            switch (this.mode) {
                case Module.MODE_CREATE:
                    return 'Create';
                case Module.MODE_UPDATE:
                    return 'Update';
                default:
                    return 'Create';
            }
        },
        filtered() {
            if (!this.search) return this.modules;

            return this.modules.filter(item => {
                return Object.keys(item).some(key => String(item[key]).toLowerCase().indexOf(this.search) > -1);
            });
        },
    },
    data: () => ({
        module: new Module(),
        confirmation: false,
        searchable: false,
        dialog: false,
        touchable: null,
        mode: null,
        selected: [],
        modules: [],
        roles: [],
        search: '',
        window: {
            width: 0,
            height: 0,
            heightDif: 220,
        },
    }),
    methods: {
        enableSearch() {
            this.searchable = true;
            this.search = '';
        },
        disableSearch() {
            this.searchable = false;
            this.search = '';
        },
        cancel() {
            this.touchable = null;
            this.module = new Module();
            this.dialog = false;
            this.confirmation = false;
        },
        create() {
            this.dialog = true;
            this.searchable = false;
            this.module = new Module();
            this.mode = Module.MODE_CREATE;
            this.disableSearch();
            console.log(this.module);
        },
        edit(module, index) {
            this.dialog = true;
            this.touchable = index;
            this.mode = Module.MODE_UPDATE;
            this.module = module;
            this.disableSearch();
        },
        confirm(module, index) {
            this.touchable = index;
            this.confirmation = true;
            this.mode = Module.MODE_DELETE;
            this.module = module;
        },
        store() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.module
                        .store()
                        .then(response => {
                            if (response.status === 200) {
                                this.modules.push(new Module(response.data.module));
                                this.cancel();
                            }

                            Snackbar.success(response.data.message);
                        })
                        .catch(error => {
                            Module.errors(error.response).forEach(error => this.errors.add(error));

                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
        update() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.module
                        .update({ module: this.module.module_id })
                        .then(response => {
                            if (response.status === 200) {
                                this.modules.splice(this.touchable, 1, new Module(response.data.module));
                                this.cancel();

                                Snackbar.success(response.data.message);
                            }
                        })
                        .catch(error => {
                            Module.errors(error.response).forEach(error => this.errors.add(error));

                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
        destroy() {
            this.module
                .delete({ module: this.module.module_id })
                .then(response => {
                    if (response.status === 200) {
                        this.modules.splice(this.touchable, 1);
                        this.cancel();
                    }

                    Snackbar.show(response.data.message, response.data.deleted ? 'success' : 'error');
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        destroyMultiple() {
            axios
                .delete('/admin/super/modules/multiple', {
                    params: { ids: this.selected },
                })
                .then(response => {
                    if (response.status === 200) {
                        this.modules = this.modules.filter(module => !this.selected.includes(module.module_id));
                        this.selected = [];

                        Snackbar.success(response.data.message);
                    }
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        /**
         * Associating WorkerRole to Module
         *
         * @param {Module} module
         * @param {Role} role
         * @param {Number} index
         */
        associateRole(module, role, index) {
            axios
                .options(`/admin/super/modules/${module.module_id}/associate/role/${role.role_id}`)
                .then(response => {
                    if (response.status === 200) {
                        this.roles.splice(index, 1);
                        module.addRole(role);

                        Snackbar.success(response.data.message);
                    }
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        /**
         * Dissociating WorkerRole from Module
         *
         * @param {Module} module
         * @param {Role} role
         * @param {Number} index
         */
        dissociateRole(module, role, index) {
            axios
                .options(`/admin/super/modules/dissociate/role/${role.role_id}`)
                .then(response => {
                    if (response.status === 200) {
                        module.removeRole(role, index);
                        this.roles.unshift(role);

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
        this.roles = this.roleItems.map(item => new Role(item));
        this.modules = this.items.map(item => new Module(item));
        window.addEventListener("resize", this.handleResize);
        this.handleResize();
    },
};
</script>

<style scoped></style>
