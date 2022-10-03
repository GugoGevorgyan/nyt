<!-- @format -->

<template>
    <v-container fluid fill-height grid-list-md>
        <v-layout row no-wrap fill-height>
            <v-flex xs12>
                <v-card tile elevation="4">
                    <v-data-table
                        loader-height="2"
                        v-model="paginated.selected"
                        :loading="paginated.loading"
                        :headers="paginated.headers"
                        :items="paginated.data"
                        :items-per-page="Number(paginated.per_page)"
                        item-key="franchise_id"
                        :height="height"
                        hide-default-footer
                    >
                        <!--table header-->
                        <template v-slot:top>
                            <div ref="header">
                                <v-toolbar flat color="grey lighten-5" height="55">
                                    <v-toolbar-title>Франшизы</v-toolbar-title>
                                    <v-divider class="mx-3" inset vertical />
                                    <v-spacer />
                                    <v-text-field
                                        v-model="paginated.search"
                                        prepend-inner-icon="mdi-magnify"
                                        color="yellow darken-3"
                                        background-color="white"
                                        label="Поиск"
                                        hide-details
                                        single-line
                                        clearable
                                        solo
                                        dense
                                    />
                                    <v-spacer/>
                                    <v-btn outlined href="/admin/super/franchises/create" color="yellow darken-3" dark>
                                        Новая франшиза
                                    </v-btn>
                                </v-toolbar>
                            </div>
                        </template>
                        <!--table content-->
                        <template v-slot:item.name="{ item }">
                            <div class="d-flex align-center justify-space-between">
                                <span class="mr-2">{{ item.name }}</span>
                                <v-avatar v-if="item.logo" color="yellow darken-4" size="32">
                                    <img :src="item.logo" alt="logo" />
                                </v-avatar>
                            </div>
                        </template>
                        <template v-slot:item.modules="{ item }">
                            <v-sheet class="mx-auto" max-width="400">
                                <v-slide-group multiple show-arrows>
                                    <v-slide-item
                                        v-for="module in item.modules"
                                        :key="module.module_id"
                                        v-slot:default="{ active, toggle }"
                                    >
                                        <v-chip color="orange" dark outlined small class="mx-1 my-1">
                                            {{ module.text }}
                                        </v-chip>
                                    </v-slide-item>
                                </v-slide-group>
                            </v-sheet>
                        </template>
                        <template v-slot:item.admins="{ item }">
                            <v-sheet class="mx-auto" max-width="400">
                                <v-slide-group multiple show-arrows>
                                    <v-slide-item
                                        v-for="admin in item.admins"
                                        :key="admin.system_worker_id"
                                        v-slot:default="{ active, toggle }"
                                    >
                                        <v-chip color="orange" dark outlined small class="mx-1 my-1">
                                            {{ admin.name }}
                                        </v-chip>
                                    </v-slide-item>
                                </v-slide-group>
                            </v-sheet>
                        </template>
                        <template v-slot:item.regions="{ item }">
                            <v-sheet class="mx-auto" max-width="300">
                                <v-slide-group multiple show-arrows>
                                    <v-slide-item
                                        v-for="region in item.regions"
                                        :key="region.region_id"
                                        v-slot:default="{ active, toggle }"
                                    >
                                        <v-chip color="orange" dark outlined small class="mx-1 my-1">
                                            {{ region.name }}
                                        </v-chip>
                                    </v-slide-item>
                                </v-slide-group>
                            </v-sheet>
                        </template>
                        <template v-slot:item.action="{ item }">
                            <div class="d-flex">
                                <v-btn
                                    small
                                    :href="`${$route.path}/${item.franchise_id}/edit`"
                                    icon
                                    color="primary"
                                    depressed
                                    class="mr-2"
                                >
                                    <v-icon small>mdi-pencil-outline</v-icon>
                                </v-btn>
                                <v-btn small color="error" icon depressed @click.stop="confirm(item)">
                                    <v-icon small>mdi-delete-outline</v-icon>
                                </v-btn>
                            </div>
                        </template>
                        <template v-slot:item.created_at="{ item }">
                            {{ item.created_at | formatDate }}
                        </template>
                        <!--table footer-->
                        <template v-slot:footer>
                            <div ref="footer">
                                <v-divider class="ma-0" />
                                <v-row no-gutters class="py-1">
                                    <v-col cols="12" md="2" class="d-flex justify-center align-center"></v-col>
                                    <v-col cols="12" md="8" class="d-flex justify-center align-center">
                                        <v-tooltip left>
                                            <template v-slot:activator="{ on, attrs }">
                                                <div v-bind="attrs" v-on="on">
                                                    <v-pagination
                                                        :length="paginated.last_page"
                                                        :total-visible="7"
                                                        circle
                                                        color="yellow darken-3"
                                                        v-model="paginated.current_page"
                                                    />
                                                </div>
                                            </template>
                                            <span>
                                                {{
                                                    Number(paginated.total)
                                                        ? `${paginated.from}-${paginated.to} из ${paginated.total}`
                                                        : "Нет данных"
                                                }}
                                            </span>
                                        </v-tooltip>
                                    </v-col>
                                    <v-col cols="12" md="2" class="d-flex justify-center align-center">
                                        <v-menu offset-y max-width="100">
                                            <template v-slot:activator="{ on: menu, attrs }">
                                                <v-tooltip left>
                                                    <template v-slot:activator="{ on: tooltip }">
                                                        <v-btn
                                                            fab
                                                            small
                                                            dark
                                                            color="yellow darken-3"
                                                            class="mb-1"
                                                            v-bind="attrs"
                                                            v-on="{ ...tooltip, ...menu }"
                                                        >
                                                            {{ paginated.per_page }}
                                                        </v-btn>
                                                    </template>
                                                    <span>строк на странице</span>
                                                </v-tooltip>
                                            </template>
                                            <v-list>
                                                <v-list-item
                                                    :disabled="paginated.per_page === item"
                                                    color="yellow darken-3"
                                                    v-for="(item, index) in paginated.perPages"
                                                    :key="index"
                                                    @click="paginated.per_page = item"
                                                >
                                                    <v-list-item-title>{{ item }}</v-list-item-title>
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </v-col>
                                </v-row>
                            </div>
                        </template>
                    </v-data-table>

                    <v-dialog v-model="dialog" max-width="500" width="100%">
                        <v-card :loading="dialogLoading">
                            <v-card-title class="title">Вы уверены что хотите удалить франшизу?</v-card-title>

                            <v-card-text>
                                <v-alert type="error" :value="true" border="left">
                                    Важно! После удаления все данные связанные с франшизой будут потеряны.
                                </v-alert>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn text @click="dialog = false">отменить</v-btn>
                                <v-btn :loading="dialogLoading" color="error" @click="destroy">удалить</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import Storage from "../../facades/Storage";
import Snackbar from "../../facades/Snackbar";
import Franchise from "../../models/Franchise";
import FranchisesPagination from "../../forms/FranchisesPagination";

export default {
    name: "FranchiseIndex",
    data() {
        return {
            dialog: false,
            deletable: undefined,
            paginated: new FranchisesPagination(
                {
                    current_page: Number(this.$route.query["page"]),
                    per_page: Number(this.$route.query["per-page"]),
                    search: this.$route.query["search"],
                },
                "/admin/super/franchises/paginate",
            ),

            height: 0,
            dialogLoading: false,
        };
    },
    watch: {
        "paginated.current_page": function () {
            this.$router.push(
                {
                    name: "admin.super.Franchises",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getFranchises();
                },
            );
        },
        "paginated.per_page": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "admin.super.Franchises",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getFranchises();
                },
            );
        },
        "paginated.search": function () {
            this.paginated.current_page = 1;
            this.$router.push(
                {
                    name: "admin.super.Franchises",
                    query: {
                        page: this.paginated.current_page,
                        "per-page": this.paginated.per_page,
                        search: this.paginated.search,
                    },
                },
                () => {
                    this.paginated.getFranchises();
                },
            );
        },
    },
    methods: {
        cancel() {
            this.dialog = false;
            this.deletable = undefined;
        },
        confirm(item) {
            this.dialog = true;
            this.deletable = new Franchise(item);
        },
        destroy() {
            this.dialogLoading = true;
            this.deletable
                .delete({ franchise: this.deletable.franchise_id })
                .then(response => {
                    if (response.status === 200) {
                        if (response.data.success) {
                            Snackbar.success(response.data.message);
                            this.dialogLoading = false;
                            this.paginated.deleteFranchise(this.deletable.franchise_id);
                        } else {
                            this.dialogLoading = false;
                            Snackbar.error(response.data.message);
                        }
                    }
                    this.cancel();
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);

                    this.cancel();
                });
        },

        /*window*/
        calcHeight() {
            this.height = window.innerHeight - this.$refs.header.clientHeight - this.$refs.footer.clientHeight - 136;
        },
    },
    filters: {
        storageUrl(value) {
            return Storage.url(value);
        },
    },
    mounted() {
        this.calcHeight();
    },
    created() {
        window.addEventListener("resize", this.calcHeight);
        this.paginated.getFranchises();
    },
};
</script>
