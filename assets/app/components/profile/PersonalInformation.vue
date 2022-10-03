<!-- @format -->

<template>
    <v-container grid-list-lg>
        <v-progress-linear :active="loading" :indeterminate="loading" height="2" color="yellow darken-3" absolute top />

        <v-snackbar v-model="snackbar" multi-line :timeout="3000" :color="color" top>
            {{ text }}
            <v-btn text @click="snackbar = false">Close</v-btn>
        </v-snackbar>

        <v-layout justify-space-between style="width: 100%" class="pa-16">
            <v-flex style="height: 600px" align-self-center class="mr-16">
                <v-form class="float-right" data-vv-scope="form1" mode="lazy" autocomplete="off">
                    <v-text-field
                        v-model="name"
                        label="Имя"
                        color="yellow darken-3"
                        background-color="grey lighten-4"
                        outlined
                        clearable
                        data-vv-name="name"
                        :error-messages="errors.collect('form1.name')"
                        v-validate="'required'"
                    />
                    <v-text-field
                        v-model="surname"
                        label="Фамилия"
                        color="yellow darken-3"
                        background-color="grey lighten-4"
                        outlined
                        clearable
                        data-vv-name="surname"
                        :error-messages="errors.collect('form1.surname')"
                        v-validate="'required'"
                    />
                    <v-text-field
                        v-model="email"
                        label="Эл. адрес"
                        color="yellow darken-3"
                        background-color="grey lighten-4"
                        outlined
                        clearable
                        data-vv-name="email"
                        :error-messages="errors.collect('form1.email')"
                        v-validate="'required'"
                    />
                    <!--                               <v-spacer/>-->
                    <v-text-field
                        v-model="phone"
                        label="Телефон"
                        color="yellow darken-3"
                        background-color="grey lighten-4"
                        v-mask="phoneMask"
                        outlined
                        clearable
                        data-vv-name="phone"
                        :error-messages="errors.collect('form1.phone')"
                        v-validate="'required'"
                    />

                    <v-btn color="green darken-3" class="mt-2" dark large @click="updatePersonalInfo">Обновить</v-btn>
                </v-form>
            </v-flex>

            <v-flex style="height: 600px" align-self-center class="ml-16">
                <v-form data-vv-scope="form2" lazy-validation autocomplete="off">
                    <v-text-field
                        v-if="!user.email"
                        v-model="email"
                        label="Добавить почту"
                        color="yellow darken-3"
                        background-color="grey lighten-4"
                        outlined
                        data-vv-as="Добавить почту"
                        data-vv-name="email"
                        :error-messages="errors.collect('form2.email')"
                        v-validate="'required|email'"
                    >
                        <template slot="append">
                            <v-icon>mdi-email</v-icon>
                        </template>
                    </v-text-field>

                    <v-text-field
                        v-else
                        v-model="currentPassword"
                        label="Текущий пароль"
                        color="yellow darken-3"
                        background-color="grey lighten-4"
                        outlined
                        :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="show1 ? 'text' : 'password'"
                        @click:append="show1 = !show1"
                        data-vv-name="Current password"
                        :error-messages="errors.collect('form2.Current password')"
                        v-validate="'required'"
                        data-vv-as="Текущий пароль"
                    />
                    <v-text-field
                        v-model="newPassword"
                        label="Новый пароль"
                        color="yellow darken-3"
                        background-color="grey lighten-4"
                        outlined
                        :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="show2 ? 'text' : 'password'"
                        @click:append="show2 = !show2"
                        ref="New password"
                        data-vv-name="New password"
                        data-vv-as="Новый пароль"
                        :error-messages="errors.collect('form2.New password')"
                        v-validate="'required'"
                    />
                    <v-text-field
                        v-model="confirmPassword"
                        label="Подтвердить пароль"
                        color="yellow darken-3"
                        background-color="grey lighten-4"
                        outlined
                        :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="show3 ? 'text' : 'password'"
                        @click:append="show3 = !show3"
                        data-vv-name="Confirm password"
                        :error-messages="errors.collect('form2.Confirm password')"
                        v-validate="'required|confirmed:New password'"
                        data-vv-as="Подтвердить пароль"
                    />
                    <div>
                        <v-btn
                            v-if="user.email"
                            color="green darken-3"
                            class="mt-2"
                            full-width
                            dark
                            large
                            @click="changePassword"
                        >
                            Изменить пароль
                        </v-btn>
                        <v-btn v-else color="green darken-3" class="mt-2" full-width dark large @click="addPassword">
                            Добавить пароль
                        </v-btn>
                    </div>
                </v-form>
            </v-flex>
        </v-layout>
        <v-row class="float-right">
            <v-form :action="'/logout'" method="post" ref="logout" style="width: 150px">
                <input :value="$csrf" name="_token" type="hidden" />
                <v-btn @click="$refs.logout.$el.submit()" color="red" large text>
                    <v-icon v-text="'mdi-logout-variant'" />
                    <span v-text="'Logout'" />
                </v-btn>
            </v-form>
        </v-row>
    </v-container>
</template>

<script>
/** @format */

export default {
    name: "PersonalInformation",
    data: () => ({
        loading: false,
        snackbar: false,
        text: "",
        color: "",
        phoneMask: "",
        user: {
            email: "",
            phone: "",
            client_id: Number,
        },
        birthdayMenu: false,
        birthdayPicker: new Date().toISOString().slice(0, 10),
        currentPassword: "",
        newPassword: "",
        confirmPassword: "",
        show1: false,
        show2: false,
        show3: false,
        confirmPasswordError: "",
        name: "",
        surname: "",
        email: "",
        phone: "",
        phoneNumbersCutLength: "",
    }),

    methods: {
        updatePersonalInfo() {
            this.$validator.validateAll("form1").then(valid => {
                if (valid) {
                    this.loading = true;
                    this.$http
                        .put(`/profile/client/info/${this.user.client_id}`, {
                            name: this.name,
                            surname: this.surname,
                            email: this.email,
                            phone: this.phone,
                            client_id: this.user.client_id,
                        })
                        .then(res => {
                            this.text = "Profile updated successfully";
                            this.color = "success";
                            this.snackbar = true;
                            this.loading = false;
                        })
                        .catch(err => {
                            this.text = "Invalid data";
                            this.color = "error";
                            this.snackbar = true;
                            this.loading = false;
                        });
                }
            });
        },
        updateBirthday() {
            this.user.birthday = this.birthdayPicker;
            this.birthdayMenu = false;
        },
        changePassword() {
            this.$validator.validateAll("form2").then(valid => {
                if (valid) {
                    this.loading = true;
                    this.$http
                        .post(`/profile/client/update/password`, {
                            currentPassword: this.currentPassword,
                            newPassword: this.newPassword,
                            confirmPassword: this.confirmPassword,
                            client_id: this.user.client_id,
                        })
                        .then(response => {
                            if (response.status === 200) {
                                this.currentPassword = "";
                                this.confirmPassword = "";
                                this.newPassword = "";
                                this.$validator.reset();
                                this.text = response.data;
                                this.color = "success";
                                this.snackbar = true;
                                this.loading = false;
                            }
                        })
                        .catch(err => {
                            if (err.response.data.errors.currentPassword) {
                                this.text = err.response.data.errors.currentPassword[0];
                            } else {
                                this.text = err.response.data.message;
                            }
                            this.color = "red";
                            this.snackbar = true;
                            this.loading = false;
                        });
                }
            });
        },
        addPassword() {
            this.$validator.validateAll("form2").then(valid => {
                if (valid) {
                    this.loading = true;
                    this.$http
                        .post(`/profile/client/add/password`, {
                            email: this.email,
                            newPassword: this.newPassword,
                            confirmPassword: this.confirmPassword,
                            client_id: this.user.client_id,
                        })
                        .then(response => {
                            if (response.status === 200) {
                                this.email = "";
                                this.confirmPassword = "";
                                this.newPassword = "";
                                this.user.email = response.data.email;
                                this.$validator.reset();
                                this.text = response.data.message;
                                this.getClientInfo();
                                this.color = "success";
                                this.snackbar = true;
                                this.loading = false;
                            }
                        })
                        .catch(err => {
                            this.text = err.response.data;
                            this.color = "red";
                            this.snackbar = true;
                            this.loading = false;
                        });
                }
            });
        },
        getClientPhoneMask() {
            this.loading = true;
            return this.$http(`/profile/client/phoneMask`)
                .then(res => {
                    if (res.data) {
                        this.$validator.reset();
                        this.phoneMask = res.data;
                        let countToParentheses = res.data.indexOf("(");
                        this.phoneNumbersCutLength = countToParentheses - 1; // One element is '+' in '+374'
                        this.getClientInfo();
                    }
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                });
        },
        getClientInfo() {
            this.loading = true;
            this.$http(`/profile/client/info`)
                .then(res => {
                    this.$validator.reset();
                    this.name = res.data.name;
                    this.surname = res.data.surname;
                    this.user.email = res.data.email;
                    this.email = res.data.email;
                    let phoneFromDB = res.data.phone;
                    this.phone = phoneFromDB.substr(this.phoneNumbersCutLength);
                    this.user.client_id = res.data.client_id;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                });
        },
    },

    created() {
        this.getClientPhoneMask();
    },
};
</script>

<style scoped>
/** @format */

.border {
    border: 1px solid black;
}

.v-text-field {
    width: 250px;
    margin-top: 5px;
}
</style>
