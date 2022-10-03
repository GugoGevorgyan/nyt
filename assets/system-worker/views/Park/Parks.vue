<!-- @format -->

<template>
    <v-container fluid>
        <v-card outlined>
            <!--Data Table-->
            <v-data-table
                dense
                :headers="paginated.headers"
                :items="paginated._payload"
                class="elevation-4"
                :loading="paginated.loading"
                loader-height="2"
                hide-default-footer
                :height="window.height"
                :fixed-header="true"
                :items-per-page="Number(paginated.per_page)"
                item-key="park_id"
                :calculate-widths="true"
            >
                <template v-slot:top>
                    <v-toolbar flat color="grey lighten-4">
                        <v-toolbar-title>Парки</v-toolbar-title>
                        <v-divider vertical class="mx-3" />
                        <v-text-field
                            style="max-width: 400px"
                            prepend-inner-icon="mdi-magnify"
                            clearable
                            color="primary"
                            background-color="white"
                            hide-details
                            label="Поиск по названию, адресу или управляющему"
                            single-line
                            outlined
                            dense
                            v-model="paginated.search"
                        />
                        <v-divider vertical class="mx-3" />
                        <v-spacer />
                        <v-btn
                            v-text="'Новый парк'"
                            depressed
                            height="100%"
                            @click="createPark()"
                        />
                    </v-toolbar>
                </template>

                <template v-slot:item.region="{ item }">
                    {{ item.region.name }}
                </template>
                <template v-slot:item.city="{ item }">
                    {{ item.city.name }}
                </template>
                <template v-slot:item.manager="{ item }">
                    <small v-if="item.manager">
                        {{ item.manager.name }} {{ item.manager.patronymic }} {{ item.manager.surname }}
                    </small>
                    <v-chip v-else outlined small color="error"> У парка нет управляющего </v-chip>
                </template>
                <template v-slot:item.entity="{ item }">
                    {{ item.entity.name }}
                </template>
                <template v-slot:item.created_at="{ item }">
                    {{ item.created_at | formatDate }}
                </template>
                <template v-slot:item.action="{ item }">
                    <v-tooltip left>
                        <template v-slot:activator="{ on }">
                            <v-btn small icon v-on="on" @click="editPark(item)">
                                <v-icon small color="primary">mdi-pencil</v-icon>
                            </v-btn>
                        </template>
                        <span>Редактировать информацию парка</span>
                    </v-tooltip>

                    <v-tooltip left>
                        <template v-slot:activator="{ on }">
                            <v-btn small icon @click="(deleteDialog = true), (deletingPark = item)" v-on="on">
                                <v-icon small color="error">mdi-delete</v-icon>
                            </v-btn>
                        </template>
                        <span>Удалить парк</span>
                    </v-tooltip>
                </template>

                <!--table footer-->
                <template v-slot:footer>
                    <table-footer :paginated="paginated" />
                </template>
            </v-data-table>
        </v-card>

        <!--CREATE EDIT DIALOG-->
        <v-dialog v-if="dialog" v-model="dialog" persistent max-width="800px" width="100%">
            <park-form
                :managers="managers"
                :park-data="parkData"
                :regions="regions"
                :cities="cities"
                :entities="entities"
                @close="closeDialog"
                @refresh="refreshParks"
            />
        </v-dialog>

        <!--DELETE DIALOG-->
        <v-dialog v-model="deleteDialog" max-width="600px" max-height="650px" width="100%">
            <v-card v-if="deletingPark" :loading="deleteLoading">
                <v-card-title class="title">Вы уверены, что хотите удалить парк?</v-card-title>

                <v-card-text>
                    <v-alert outlined type="error">
                        После удалеиня информация о парке <strong>{{ deletingPark.name }}</strong> будет утерена!
                    </v-alert>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn small @click="(deleteDialog = false), (deletingPark = null)" text>отмена</v-btn>
                    <v-btn small :loading="deleteLoading" outlined @click="deletePark()" color="error">удалить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script src="./Parks.main.js"/>
