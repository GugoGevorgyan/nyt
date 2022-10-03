<!-- @format -->

<template>
    <v-card elevation="4" class="border-lg">
        <v-card-title>
            Адреса клиента
            <v-spacer></v-spacer>
            <v-btn icon color="success" @click="addAddress()">
                <v-icon>mdi-plus</v-icon>
            </v-btn>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <div class="b-scroll" style="overflow-y: auto; height: 475px">
                <v-list>
                    <v-list-item
                        v-for="address in employee.client_addresses"
                        :key="address.client_address_id"
                        @click="editAddress(address)"
                    >
                        <v-list-item-content>
                            <h4>{{ address.address }}</h4>
                            <v-list-item-subtitle>{{ address.name }}</v-list-item-subtitle>
                        </v-list-item-content>

                        <v-list-item-avatar>
                            <v-icon color="primary">mdi-pencil</v-icon>
                        </v-list-item-avatar>
                    </v-list-item>
                </v-list>
            </div>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn tile text width="90" @click="$emit('closeDialog')">Закрыть</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { mapState, mapMutations } from "vuex";

export default {
    name: "MyAddresses",
    props: {
        employee: {
            required: true,
            type: Object,
        },
    },
    data: () => ({}),
    computed: {
        ...mapState([/*"employee"*/]),
    },
    methods: {
        ...mapMutations([/*"incrementStep",*/ "closeAddress", "saveAddress"]),
        addAddress() {
            this.$emit("addAddress");
        },
        editAddress(address) {
            this.$emit("editAddress", address);
        },
    },
};
</script>

<style scoped>
.border {
    border: 1px solid black;
}
</style>
