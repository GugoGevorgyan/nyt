<!-- @format -->
<template>
    <v-container fluid>
        <v-card outlined>
            <v-data-table
                class="elevation-4"
                loader-height="2"
                dense
                :fixed-header="true"
                :headers="paginated.headers"
                :items="paginated.data"
                :items-per-page="Number(paginated.per_page)"
                :loading="paginated.loading"
                hide-default-footer
                item-key="legal_entity_id"
                :calculate-widths="true"
                :height="window.height"
                disable-sort
            >
                <!--Header-->
                <template v-slot:top>
                    <v-toolbar flat :dark="darkMode" :color="darkMode ? 'black' : 'grey lighten-4'" height="55px">
                        <v-toolbar-title>Юридические лица</v-toolbar-title>
                        <v-divider vertical class="mx-3" />
                        <v-text-field
                            prepend-inner-icon="mdi-magnify"
                            clearable
                            color="yellow darken-3"
                            outlined
                            dense
                            background-color="white"
                            hide-details
                            label="Поиск"
                            single-line
                            v-model="paginated.search"
                            class="rounded-1"
                        />
                        <v-divider vertical class="mx-3" />
                        <v-select
                            outlined
                            style="max-width: 400px"
                            :items="types"
                            clearable
                            color="yellow darken-3"
                            background-color="white"
                            item-text="name"
                            item-value="entity_type_id"
                            label="Тип"
                            v-model="paginated.type"
                            dense
                            hide-details
                            class="rounded-1"
                        />
                        <v-divider vertical class="mx-3" />
                        <v-spacer />
                        <v-btn
                            v-text="'Добавить юридическое лицо'"
                            :href="$router.resolve({ name: 'legal_entity_create' }).href"
                            depressed
                            height="100%"
                            class="rounded-1"
                        />
                    </v-toolbar>
                </template>

                <!--Content-->
                <template v-slot:item.type="{ item }">
                    <template v-if="item.type">
                        <span class="mr-1">{{ item.type.abbreviation }}</span>
                        <small>{{ item.type.name }}</small>
                    </template>
                </template>
                <template v-slot:item.action="{ item }">
                    <div class="d-flex">
                        <v-tooltip left>
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    small
                                    icon
                                    color="primary"
                                    v-on="on"
                                    :href="
                                        $router.resolve({
                                            name: 'legal_entity_edit',
                                            params: { entity_id: item.legal_entity_id },
                                        }).href
                                    "
                                >
                                    <v-icon small>mdi-pencil-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>Редактировать</span>
                        </v-tooltip>
                        <v-tooltip v-if="!item.main" left>
                            <template v-slot:activator="{ on }">
                                <v-btn small icon color="error" v-on="on" @click="showConfirm(item)">
                                    <v-icon small>mdi-close</v-icon>
                                </v-btn>
                            </template>
                            <span>Удалить</span>
                        </v-tooltip>
                    </div>
                </template>
                <template v-slot:item.created_at="{ item }">
                    {{ item.created_at | formatDate }}
                </template>

                <template v-slot:item.phone="{ item }">
                    {{ item.phone | VMask(phoneMask) }}
                </template>

                <!--Footer-->
                <template v-slot:footer>
                    <table-footer :paginated="paginated" />
                </template>
            </v-data-table>
        </v-card>

        <!--DELETE DIALOG-->
        <v-dialog v-model="deleteDialog" persistent max-width="600px" max-height="650px" width="100%">
            <v-card v-if="deletingObj" :loading="deleteLoading">
                <v-card-title class="title" v-text="'Вы уверены, что хотите удалить юридическое лицо?'" />

                <v-card-text>
                    <v-alert outlined type="error">
                        После удалеиня информация о юридическом лице <strong>{{ deletingObj.name }}</strong> будет
                        утерена!
                    </v-alert>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn small @click="closeDelete()" text>отмена</v-btn>
                    <v-btn small :loading="deleteLoading" @click="deleteObj()" color="error">удалить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script lang="js" src="./index.main.js" />
