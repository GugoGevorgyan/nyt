<!-- @format -->

<template>
    <div>
        <v-autocomplete
            :items="permissions"
            :loading="loading"
            clearable
            color="yellow darken-3"
            deletable-chips
            item-text="text"
            dense
            item-value="permission_id"
            :label="'Возможности ' + role.text"
            multiple
            name="permission_ids"
            outlined
            prepend-icon="mdi-face-agent"
            small-chips
            v-model="permission_ids"
            v-validate="validate"
            :error-messages="errors.collect('permission_ids')"
            data-vv-as="возможности"
        >
            <template v-slot:prepend-item>
                <v-btn
                    v-if="!permission_ids.length"
                    @click="selectAllPermissions"
                    class="ml-3 mb-3"
                    color="primary"
                    outlined
                    tile
                    depressed
                    small
                    v-text="'выбрать все'"
                />
                <v-btn
                    v-else
                    @click="deleteAllPermissions"
                    class="ml-3 mb-3"
                    color="secondary"
                    outlined
                    tile
                    depressed
                    small
                    v-text="'снять все'"
                />
            </template>

            <template v-slot:selection="{ item, index }">
                <v-chip small v-if="2 > index">
                    <span>{{ item.text }}</span>
                </v-chip>
                <span v-if="2 === index" class="grey--text caption">(+{{ permission_ids.length - 2 }} других) </span>
                <v-icon color="grey" v-if="2 === index">mdi-magnify</v-icon>
            </template>
        </v-autocomplete>
    </div>
</template>

<script>
import Snackbar from "../../facades/Snackbar";

export default {
    name: "WorkerRolePermissions",

    props: {
        role: {
            required: true,
        },
        role_permission_ids: {
            required: true,
        },
        updateMode: {
            required: true,
            type: Boolean,
        },
        url: {
            required: true,
            type: String,
        },
        validate: {
            required: false,
            default: {},
        },
    },

    data() {
        return {
            permissions: [],
            permission_ids: this.role_permission_ids,
            loading: false,
        };
    },

    watch: {
        permission_ids: function () {
            this.$emit("updatePermissions", { role: this.role.role_id, permissions: this.permission_ids });
        },
    },

    methods: {
        getPermissions() {
            this.loading = true;
            this.$http
                .get(this.url + `get/permissions/` + this.role.role_id)
                .then(response => {
                    this.loading = false;
                    this.permissions = response.data.permissions;
                    this.setPermissions(response.data.permissions);
                })
                .catch(error => {
                    if (error.response && 422 === error.response.status) {
                        Snackbar.error(error.response.data.message);
                    }

                    this.loading = false;
                    this.permissions = [];
                });
        },

        setPermissions(data) {
            if (!this.updateMode) {
                data.forEach(item => {
                    this.permission_ids.push(item.permission_id);
                });
            }
        },

        selectAllPermissions() {
            this.permissions.forEach(permission => {
                this.permission_ids.push(permission.permission_id);
            });
        },

        deleteAllPermissions() {
            this.permission_ids = [];
        },
    },

    created() {
        this.getPermissions();
    },
};
</script>
