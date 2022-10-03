<!-- @format -->

<template>
    <v-container>
        <v-layout column align-center>
            <v-flex style='width: 550px'>
                <template>
                    <v-toolbar flat color="white">
                           <v-progress-linear
                            :active="loading"
                            :indeterminate="loading"
                            color="yellow darken-3"
                            absolute
                            bottom
                            />
                        <v-toolbar-title>Адреса</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <v-btn color="yellow darken-3" dark @click="open">Добавить адрес</v-btn>

                        <v-dialog v-model="addressDialog" v-if="addressDialog" max-width="700px" width="100%" persistent>
                            <AddressComponent
                                :editing-address="editingAddress"
                                :client_id="client_id"
                                :is-editable-address="isEditableAddress"
                                @add="addAddress()"
                                @update="updateAddress()"
                                @back="addressDialogBack()"
                                @addressListUpdated="getAddresses()"
                            />
                        </v-dialog>
                    </v-toolbar>
                </template>

            </v-flex>
            <v-flex>
                <v-card max-width='550' min-width='550' style='overflow-y: scroll' :height='window.height' class='border' elevation='1' >
                    <template v-for='item in addresses'>
                        <div class='address_template'>
                            <v-card-actions>
                                <v-card-subtitle class='pl-0'>
                                <span class='font-weight-bold'>
                                     {{ item.address }}
                                </span>
                                </v-card-subtitle>
                                <v-spacer/>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon color="blue lighten-2" small class="mr-2" v-on="on" @click="openAddressInfo(item)"
                                        >mdi-eye</v-icon
                                        >
                                    </template>
                                    <span>Смотреть</span>
                                </v-tooltip>

                            </v-card-actions>
                            <v-divider/>
                            <v-card-actions>
                                <span class="font-italic">Имя: </span>
                                <span class="font-weight-medium pl-2">
                                        {{ item.name }}
                                </span>
                            </v-card-actions>
                            <v-card-actions>
                                <span class="font-italic">Короткий адрес: </span>
                                <span class="font-weight-medium pl-2">
                                        {{ item.short_address }}
                                    </span>
                            </v-card-actions>
                            <v-card-actions>
                                <v-spacer/>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            v-if="item.favorite === 0"
                                            color="yellow darken-3"
                                            class="mr-2"
                                            v-on="on"
                                            @click="makeFavourite(item)"
                                        >mdi-star-outline</v-icon
                                        >
                                        <v-icon v-else color="yellow darken-3" class="mr-2" v-on="on" @click="removeFavourite(item)"
                                        >mdi-star</v-icon
                                        >
                                    </template>
                                    <span>{{ item.favorite ? `Удалить избранное` : `Сделать избранным` }}</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon color="green darken-2" class="mr-2" v-on="on" @click="editAddress(item)"
                                        >mdi-pencil-outline</v-icon
                                        >
                                    </template>
                                    <span>Редактировать</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon color="red darken-2" v-on="on" @click="deleteAddress(item)">mdi-delete-outline</v-icon>
                                    </template>
                                    <span>Удалить</span>
                                </v-tooltip>
                            </v-card-actions>
                        <v-spacer />
                        </div>
                    </template>
                </v-card>
            </v-flex>
        </v-layout>
        <AddressInfo v-if='addressInfo' :address-info='addressInfo' :coordinates='coordinates' @close='closeAddressInfo'/>
    </v-container>
</template>

<script>
/** @format */
import axios from "axios";
import Address from "../../models/Address";
import AddressComponent from "./Address";
import ProfileClientPagination from "../../forms/AddressPagination";
import AddressInfo from './AddressInfo';

export default {
    name: "Favourite",
    components: { AddressInfo, Address, AddressComponent },
    data() {
        return {
            loadingAddresses: false,
            paginated: new ProfileClientPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: this.$route.query["per_page"],
                },
                "/profile/client/addresses",
            ),
            addresses: [] || null,
            addressDialog: false,
            editingAddress: undefined,
            isEditableAddress: false,
            window: {
                width: 0,
                height: window.innerHeight - 250,
            },
            path: "/profile/client/addresses",
            addressInfo: false,
            coordinates: [],
            loading: false,
            client_id: null
        };
    },
    methods: {
        setQuery() {
            this.$router.push(
                {
                    name: "",
                    query: {
                        page: this.paginated.current_page || 1,
                        per_page: this.paginated.per_page || 10,
                    },
                },
                () => {
                    this.paginated.getData;
                },
            );
        },
        open() {
            this.isEditableAddress = false;
            this.addressDialog = true;
        },

        close() {
            this.editingAddress = new Address({ client_id: this.paginated.client_id });
            this.addressDialog = false;
        },

        addAddress() {
            this.paginated.getData;
        },

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 250;
        },

        updateAddress() {
            this.paginated.getData;
        },

        makeFavourite(address) {
            address.favorite = 1;
            axios
                .put("client/address/favorite", {
                    address,
                })
                .then(() => {});
        },

        removeFavourite(address) {
            address.favorite = 0;
            axios
                .put("client/address/favorite", {
                    address,
                })
                .then(() => {});
        },

        editAddress(address) {
            this.setAddressFields(address);
            this.isEditableAddress = true;
            this.addressDialog = true;
        },

        setAddressFields(address) {
            this.editingAddress = new Address(address);
        },

        deleteAddress(address) {
            confirm("Are you sure you want to delete this address?") &&
                axios
                    .post(`client/address/${this.client_id}/${address.client_address_id}`)
                    .then(() => {
                        this.getAddresses();
                    })
                    .catch(() => {});
        },

        addressDialogBack() {
            this.editingAddress = undefined;
            this.addressDialog = false;
        },
        getAddresses() {
            this.loading = true;
            axios
                .get('client/addresses')
                .then(response => {
                    this.client_id = response.data.client_id;
                    if (200 === response.status) {
                        this.addresses = response.data.addresses
                    }
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        openAddressInfo(item) {
            this.coordinates = [item.lat, item.lut];
            this.addressInfo = !this.addressInfo;
        },
        closeAddressInfo() {
            this.addressInfo = !this.addressInfo;
        }
    },

    created() {
        this.getAddresses();
        window.addEventListener("resize", this.handleResize);
    },
};
</script>
<style scoped>
.address_template {
    margin: 7px;
    border: 0.7px solid lightgray;
    border-radius: 8px;
    box-shadow: 0px 0px 3px -1px;
}
</style>
