<!-- @format -->

<template>
    <v-container id='container'>
        <v-layout column align-center>
            <v-flex style='width: 550px'>
                <template>
                    <v-toolbar flat color='white'>
                        <v-progress-linear
                            :active='loading'
                            :indeterminate='loading'
                            color='yellow darken-3'
                            absolute
                            bottom
                        />
                        <v-toolbar-title>Компании</v-toolbar-title>
                    </v-toolbar>
                </template>
            </v-flex>
            <v-flex>


                <v-card max-width='550' min-width='550' style='overflow-y: scroll' :height='window.height'
                        class='border' elevation='1'>
                    <template v-for='item in companies'>
                        <div class='address_template'>
                            <v-card-actions>
                                <v-card-subtitle class='pl-0'>
                                <span class='font-weight-bold'>
                                     {{ item.address }}
                                </span>
                                </v-card-subtitle>
                                <v-spacer />

                            </v-card-actions>
                            <v-divider />
                            <v-card-actions>
                                <span class='font-italic'>Имя: </span>
                                <span class='font-weight-medium pl-2'>
                                        {{ item.name }}
                                </span>
                            </v-card-actions>
                            <v-card-actions>
                                <span class='font-italic'>Лимит: </span>
                                <span class='pl-2'>
                            <v-chip dark outlined small color='orange'>
                            {{ item.limit }} руб.
                        </v-chip>
                        </span>
                            </v-card-actions>
                            <v-card-actions>
                                <span class='font-italic'>Телефоны: </span>
                                <span class='pl-2'>
                                    <v-text-field
                                        v-mask='item.phone_mask'
                                        v-model='item.phones[0].number'
                                        readonly
                                    />
                                </span>
                                <span class='pl-2'>
                                    <v-text-field
                                        v-mask='item.phone_mask'
                                        v-model='item.phones[1].number'
                                        readonly
                                    />
                                </span>
                            </v-card-actions>
                        </div>
                    </template>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import axios from "axios";

export default {
    name: "Companies",
    data: () => ({
        companies: [],
        headers: [
            { text: "Name", value: "name", sortable: false },
            { text: "Address", value: "address", sortable: false },
            { text: "Limit", value: "limit", sortable: false },
        ],
        loading: false,
        window: {
            width: 0,
            height: window.innerHeight - 250,
        },
    }),
    methods: {
        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - 250;
        },
    },
    created() {
        window.addEventListener("resize", this.handleResize);
        this.loading = true;
        axios
            .get(`/profile/client/companies`)
            .then(res => {
                this.companies = res.data.corporate_companies;
                this.loading = false;
            })
            .catch(() => {});
    },
};
</script>
<style>
.address_template {
    margin: 7px;
    border: 0.7px solid lightgray;
    border-radius: 8px;
    box-shadow: 0px 0px 3px -1px;
}
</style>
