<!-- @format -->

<template>
    <div>
        <v-form data-vv-scope="client" style="width: 99%">
            <v-container fluid grid-list-lg>
                <v-row>
                    <v-col cols="12">
                        <div class="display-1 font-weight-light text-center">
                            {{ client.client_id ? "Обновление данных клиента" : "Введите номер телефона клиента" }}
                            <v-btn
                                v-if="client.client_id"
                                absolute
                                top
                                right
                                small
                                icon
                                color="error"
                                @click="$emit('close')"
                            >
                                <v-icon v-text="'mdi-close'" />
                            </v-btn>
                        </div>
                        <v-divider />
                    </v-col>
                </v-row>

                <v-row class="d-flex justify-center align-center" :style="!client.client_id ? { height: '80%' } : ''">
                    <v-col cols="12" :lg="client.client_id ? 12 : 3">
                        <v-text-field
                            class="mb-5"
                            data-vv-as="телефон"
                            autofocus
                            outlined
                            dense
                            :loading="existsLoading"
                            v-mask="'+#(###)-###-##-##'"
                            :error-messages="errors.collect('client.phone')"
                            v-model="client.phone"
                            :background-color="darkMode ? 'black' : 'grey lighten-3'"
                            :dark="darkMode"
                            color="yellow darken-3"
                            label="Телефон"
                            name="phone"
                            v-validate="client.rules.phone"
                            hide-details
                            @keypress="
                                13 === $event.keyCode ? (client.client_id ? updateClient() : createClient()) : null
                            "
                        />
                        <v-text-field
                            :background-color="darkMode ? 'black' : 'grey lighten-3'"
                            :dark="darkMode"
                            :disabled="disabled"
                            class="mb-4"
                            data-vv-as="фамилия"
                            outlined
                            dense
                            :error-messages="errors.collect('client.surname')"
                            v-model="client.surname"
                            color="yellow darken-3"
                            label="Фамилия"
                            name="surname"
                            v-validate="client.rules.surname"
                            hide-details
                            @keypress="
                                13 === $event.keyCode ? (client.client_id ? updateClient() : createClient()) : null
                            "
                        />
                        <v-text-field
                            :disabled="disabled"
                            :background-color="darkMode ? 'black' : 'grey lighten-3'"
                            :dark="darkMode"
                            class="mb-4"
                            data-vv-as="имя"
                            outlined
                            dense
                            :error-messages="errors.collect('client.name')"
                            v-model="client.name"
                            color="yellow darken-3"
                            label="Имя"
                            name="name"
                            v-validate="client.rules.name"
                            hide-details
                            @keypress="
                                13 === $event.keyCode ? (client.client_id ? updateClient() : createClient()) : null
                            "
                        />
                        <v-text-field
                            :disabled="disabled"
                            class="mb-4"
                            data-vv-as="отчество"
                            outlined
                            :background-color="darkMode ? 'black' : 'grey lighten-3'"
                            :dark="darkMode"
                            dense
                            :error-messages="errors.collect('client.patronymic')"
                            v-model="client.patronymic"
                            color="yellow darken-3"
                            label="Отчество"
                            name="patronymic"
                            v-validate="client.rules.patronymic"
                            hide-details
                            @keypress="
                                13 === $event.keyCode ? (client.client_id ? updateClient() : createClient()) : null
                            "
                        />
                        <v-text-field
                            :disabled="disabled"
                            class="mb-4"
                            data-vv-as="эл. адрес"
                            outlined
                            dense
                            :background-color="darkMode ? 'black' : 'grey lighten-3'"
                            :dark="darkMode"
                            :error-messages="errors.collect('client.email')"
                            v-model="client.email"
                            color="yellow darken-3"
                            label="Эл. адрес"
                            name="email"
                            v-validate="client.rules.email"
                            hide-details
                            @keypress="
                                13 === $event.keyCode ? (client.client_id ? updateClient() : createClient()) : null
                            "
                        />
                        <div class="d-flex justify-center">
                            <v-btn
                                :dark="darkMode"
                                rounded
                                small
                                :disabled="disabled"
                                color="primary"
                                :loading="loading"
                                @click="client.client_id ? updateClient() : createClient()"
                            >
                                <v-icon
                                    :dark="!darkMode"
                                    :color="darkMode ? 'white' : 'black'"
                                    v-text="client.client_id ? 'mdi-refresh' : 'mdi-plus'"
                                    left
                                />
                                {{ client.client_id ? "обновить" : "создать" }}
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>

        <!--Exist client dialog-->
        <v-dialog persistent v-model="existsDialog" max-width="400" width="100%">
            <v-card v-if="existsClient">
                <v-card-title>Информация о {{ existsClient.client.phone }}</v-card-title>
                <v-card-text>
                    <v-alert border="left" colored-border type="info" elevation="2">
                        Клиент с номером {{ existsClient.client.phone }} уже существует!
                    </v-alert>
                </v-card-text>
                <v-card-actions class="d-flex justify-end">
                    <v-btn text color="error" @click="closeExistsDialog()" v-text="'Отмена'" />
                    <v-btn text color="primary" @click="setExistsClient()" v-text="'Загрузить информацию '" />
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="js" src="./ClientForm.main.js" />
<style lang="scss" src="./ClientForm.style.scss" />
