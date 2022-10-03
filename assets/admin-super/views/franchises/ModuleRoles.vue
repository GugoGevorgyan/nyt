<!-- @format -->

<template>
    <div>
        <v-autocomplete
            :items="roles"
            :loading="loading"
            clearable
            color="yellow darken-3"
            data-vv-as="города"
            deletable-chips
            item-text="text"
            dense
            item-value="role_id"
            :label="'Роли ' + module.text"
            multiple
            name="role_ids"
            outlined
            prepend-icon="mdi-face-agent"
            small-chips
            v-model="role_ids"
        >
            <template v-slot:selection="{ item, index }">
                <v-chip small v-if="index < 1">
                    <span>{{ item.text }}</span>
                </v-chip>
                <span v-if="index === 1" class="grey--text caption">(+{{ role_ids.length - 1 }} других) </span>
                <v-icon color="grey" v-if="index === 1">mdi-magnify</v-icon>
            </template>
        </v-autocomplete>
    </div>
</template>

<script>
import axios from 'axios';
import Snackbar from '../../facades/Snackbar';

export default {
    name: 'ModuleRoles',
    props: {
        module: {
            required: true,
        },
        module_role_ids: {
            required: true,
        },
        updateMode: {
            required: true,
            type: Boolean,
        },
    },
    data() {
        return {
            roles: [],
            role_ids: this.module_role_ids,
            loading: false,
        };
    },
    watch: {
        role_ids: function () {
            this.$emit('updateRoles', { module: this.module.module_id, roles: this.role_ids });
        },
    },
    methods: {
        getRoles() {
            this.loading = true;
            axios
                .get(`/admin/super/get/roles/` + this.module.module_id)
                .then(response => {
                    this.loading = false;
                    this.roles = response.data;
                    this.setRoles(response.data);
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                    this.loading = false;
                    this.roles = [];
                });
        },

        setRoles(data) {
            if (!this.updateMode) {
                data.forEach(item => {
                    this.role_ids.push(item.role_id);
                });
            }
        },
    },
    created() {
        this.getRoles();
    },
};
</script>
