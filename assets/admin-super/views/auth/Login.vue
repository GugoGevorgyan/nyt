<!-- @format -->

<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card outlined class="mx-auto" :loading="loading">
                    <v-toolbar color="white" flat>
                        <v-btn icon @click="back">
                            <v-icon>mdi-chevron-left</v-icon>
                        </v-btn>

                        <v-spacer></v-spacer>
                        <v-img :src="'/img/taxi-pin.svg'" max-height="50" max-width="50" aspect-ratio="1" alt="NYT"></v-img>
                        <v-spacer></v-spacer>

                        <v-btn icon>
                            <v-icon>mdi-dots-vertical</v-icon>
                        </v-btn>
                    </v-toolbar>

                    <v-card-title>
                        <div class="text-xs-center" style="width: 100%">
                            <div>{{ heading.header }}</div>
                            <span class="subtitle-1" v-html="heading.text"></span>
                        </div>
                    </v-card-title>

                    <v-form ref="login" method="post" :action="action">
                        <input type="hidden" name="_token" :value="$csrf" />

                        <v-window v-model="step">
                            <v-window-item :value="1">
                                <v-card-text>
                                    <v-text-field
                                        v-model="auth.name"
                                        v-validate.disable="auth.rules.name"
                                        :error-messages="errors.collect('name')"
                                        @keypress.enter.prevent="next"
                                        prepend-inner-icon="mdi-shield-account-outline"
                                        autocomplete="off"
                                        label="Name"
                                        name="name"
                                        ref="name"
                                        autofocus
                                        outlined
                                    ></v-text-field>
                                </v-card-text>
                            </v-window-item>

                            <v-window-item :value="2">
                                <v-card-text>
                                    <v-layout justify-center>
                                        <v-chip class="ma-2" outlined @click="step = 1">
                                            <v-avatar left>
                                                <v-icon>mdi-account-circle-outline</v-icon>
                                            </v-avatar>
                                            {{ auth.name }}
                                            <v-icon right>mdi-chevron-down</v-icon>
                                        </v-chip>
                                    </v-layout>

                                    <v-text-field
                                        v-model="auth.password"
                                        v-validate="auth.rules.password"
                                        :error-messages="errors.collect('password')"
                                        :append-icon="icon"
                                        :type="type"
                                        prepend-inner-icon="mdi-lock-outline"
                                        autocomplete="off"
                                        label="Password"
                                        name="password"
                                        ref="password"
                                        autofocus
                                        outlined
                                        @click:append="show = !show"
                                        @keypress.enter="next"
                                    />

                                    <v-checkbox
                                        v-model="auth.remember"
                                        :false-value="null"
                                        :true-value="1"
                                        label="Remember Me?"
                                        type="checkbox"
                                        color="primary"
                                        name="remember"
                                    ></v-checkbox>
                                </v-card-text>
                            </v-window-item>
                        </v-window>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="next">{{ text }}</v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>

                <v-card class="mt-3" dense>
                    <v-card-text>
                        <p>
                            By signing in or creating an account, you agree with our
                            <a href="#">Terms & Conditions</a> and
                            <a href="#">Privacy Statement</a>
                        </p>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import Login from '../../forms/auth/Login';

export default {
    name: 'Login',
    props: {
        inputs: {
            required: true,
            type: [Array, Object],
            default: () => [],
        },
        errs: {
            required: true,
            type: [Array, Object],
            default: () => [],
        },
        action: {
            required: true,
            type: String,
        },
    },
    computed: {
        type() {
            return this.show ? 'text' : 'password';
        },
        icon() {
            return this.show ? 'mdi-eye-off' : 'mdi-eye';
        },
        text() {
            return this.step === 2 ? 'Login' : 'Next';
        },
        heading() {
            switch (this.step) {
                case 1:
                    return {
                        header: 'Sign in',
                        text: 'To continue to NYT.ru',
                    };
                case 2:
                    return {
                        header: 'Welcome',
                        text: '',
                    };
            }
        },
    },
    data: () => ({
        step: 1,
        show: false,
        loading: false,
        auth: new Login(),
    }),
    methods: {
        next() {
            switch (this.step) {
                case 1:
                    this.loading = true;
                    this.$refs.name.blur();
                    this.$validator.validate('name').then(valid => {
                        if (valid) this.step++;

                        this.loading = false;
                    });
                    break;
                case 2:
                    this.loading = true;
                    this.$refs.password.blur();
                    this.$validator.validateAll().then(valid => {
                        if (valid) this.$refs.login.$el.submit();
                    });
                    break;
            }
        },
        back() {
            if (this.step > 1) this.step--;
        },
    },
    created() {
        /**
         * attaching old input values after submitting register form
         */
        if (this.inputs.constructor === Object) {
            for (let key in this.inputs) {
                if (this.inputs.hasOwnProperty(key)) {
                    this.auth.name = this.inputs[key];
                }
            }
        }
        /**
         * adding server validation errors to vee-validate error bag
         */
        if (this.errs.constructor === Object) {
            Login.errors({ status: 422, data: { errors: this.errs } }).forEach(error => this.errors.add(error));
        }
    },
    mounted() {
        this.$refs.name.blur();
    },
};
</script>

<style scoped></style>
