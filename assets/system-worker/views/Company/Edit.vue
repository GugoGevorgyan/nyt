<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <v-card class="mb-3" elevation="4" tile>
            <v-card-title class="grey lighten-5">Список администараторов компании {{ company.name }}</v-card-title>
            <v-divider class="mt-0" />
            <v-card-text>
                <v-data-table
                    v-if="admins.length"
                    :headers="[
                        { text: 'Фамилия', value: 'surname', align: 'left', sortable: false },
                        { text: 'Имя', value: 'name', align: 'left', sortable: false },
                        { text: 'Отчество', value: 'patronymic', align: 'left', sortable: false },
                        { text: 'Эл. адрес', value: 'email', align: 'left', sortable: false },
                        { text: 'Телефон', value: 'phone', align: 'left', sortable: false },
                        { text: 'Зарегистрирован', value: 'created_at', align: 'left', sortable: true },
                    ]"
                    :items="admins"
                    :items-per-page="5"
                >
                    <template v-slot:item.created_at="{ item }">
                        {{ item.created_at | formatDate }}
                    </template>

                    <template v-slot:item.phone="{ item }">
                        {{ item.phone | VMask(phoneMask) }}
                    </template>
                </v-data-table>
                <div v-else class="d-flex justify-center">
                    <v-alert type="error" dense outlined> У компании нет администраторов </v-alert>
                </div>
            </v-card-text>
        </v-card>
        <company-form :company-obj="company" :admins-count="admins.length" />
    </v-container>
</template>

<script>
import CompanyForm from "./form/CompanyForm";
export default {
    components: { CompanyForm },
    props: {
        company: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            admins: this.company.corporate_admins,
        };
    },
    computed: {
        phoneMask() {
            return this.$store.state.phoneMask;
        }
    }
};
</script>
