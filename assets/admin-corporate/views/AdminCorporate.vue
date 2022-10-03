<!-- @format -->

<template>
    <div>
        <navbar />
        <v-container fluid>
            <v-card tile>
                <v-tabs active-class="white" color="grey darken-3" v-model="tab" height="40px">
                    <v-tab :key="item.title" :to="{ name: item.name }" v-for="item in items">{{ item.title }}</v-tab>
                    <v-divider vertical />
                    <v-spacer />
                    <v-divider vertical class="mr-2" />

                    <!--         NOTIFICATIONS           -->
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn class="mr-5" style="margin-top: 2px" v-bind="attrs" v-on="on" icon>
                                <v-icon v-text="'mdi-bell-check-outline'" />
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item link>
                                <v-list-item-title />
                            </v-list-item>
                        </v-list>
                    </v-menu>

                    <!--         PROFILE           -->
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn v-bind="attrs" v-on="on" text class="text--primary" tile height="100%">
                                {{ initialData.company.name }}
                                <v-icon v-text="'mdi-arrow-down'" color="grey" />
                            </v-btn>
                        </template>
                        <v-list width="215px" class="">
                            <v-list-item link>
                                <v-list-item-title>
                                    <v-icon v-text="'mdi-face-profile'" />
                                    {{ initialData.user.name }} {{ initialData.user.surname }}
                                </v-list-item-title>
                            </v-list-item>

                            <v-form action="logout-personal-admin" method="post" ref="logout">
                                <input :value="$csrf" name="_token" type="hidden" />
                                <v-list-item @click="$refs.logout.$el.submit()" link>
                                    <v-list-item-title>
                                        <v-icon v-text="'mdi-logout-variant'" color="yellow darken-3" />
                                        <span v-text="'Выйти из системы'" />
                                    </v-list-item-title>
                                </v-list-item>
                            </v-form>
                        </v-list>
                    </v-menu>
                </v-tabs>
            </v-card>
            <router-view class="mt-5" />
        </v-container>
    </div>
</template>

<script>
import { mapMutations } from "vuex";

export default {
    name: "AdminCorporate",

    props: ["initialData"],

    data: () => ({
        tab: null,
        items: [
            { title: "Информация о компании", name: "company_index" },
            { title: "История заказов", name: "company_orders" },
            { title: "Список сотрудников", name: "company_employees" },
        ],
    }),

    computed: {
        broadcast: {
            get() {
                return this.$store.state.broadcast;
            },
            set(val) {
                this.$store.state.broadcast = val;
            },
        },
    },

    methods: {
        ...mapMutations(["initialize"]),
    },

    beforeCreate() {
        if ("/admin/corporate" === this.$route.path || "/admin/corporate/" === this.$route.path) {
            this.$router.push({ name: "company_index" });
        }
    },

    created() {
        this.broadcast = Echo.join(
            `${process.env.MIX_CHANNEL_ADMIN_CORPORATE_WEB}-base.${this.initialData.user.admin_corporate_id}.${this.initialData.user.company_id}.${this.initialData.user.franchise_id}`,
        );

        this.initialize(this.initialData);
    },
};
</script>

<style scoped>
.basil--text {
    color: #356859 !important;
}
</style>
