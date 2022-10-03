<!-- @format -->

<template>
    <v-card height="100%" width="100%" class="border">
        <v-card-title class="grey lighten-4">
            <span class="align-center" v-text="'Отправить текстовое оповещение'" />
            <v-spacer />
            <v-btn icon @click="$emit('closeNotify')">
                <v-icon color="grey darken-3" v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>

        <v-divider />
        <v-card-text style="max-height: 230px">
            <div class="mt-5">
                <v-text-field
                    label="Заголовок"
                    autofocus
                    name="title"
                    v-validate="rules.titleRules"
                    :error-messages="errors.collect('title')"
                    data-vv-as="Заголовок"
                    v-model="titleNotify"
                />

                <v-textarea
                    v-validate="rules.textRules"
                    :error-messages="errors.collect('text')"
                    v-model="textNotify"
                    data-vv-as="ОПОВЕЩЕНИЕ"
                    name="text"
                    rows="3"
                    row-height="1"
                    color="primary"
                    dense
                    class="rounded-0"
                    label="ОПОВЕЩЕНИЕ"
                />
            </div>
        </v-card-text>
        <v-card-actions>
            <v-btn block small outlined depressed color="primary" @click="sendNotification" :loading="sendLoading">
                <v-icon v-text="'mdi-send'" />
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: "Notification",

    props: {
        clients: {
            required: true,
        },
        path: {
            required: true,
            type: String,
        },
    },

    data() {
        return {
            sendLoading: false,

            rules: {
                textRules: {
                    required: true,
                    max: 500,
                },
                titleRules: {
                    required: true,
                    max: 50,
                },
            },
            textNotify: "",
            titleNotify: "",
        };
    },

    methods: {
        sendNotification() {
            this.sendLoading = true;
            this.$http
                .post(`${process.env.MIX_APP_WORKER_URL}${this.path}`, {
                    clients: this.clients,
                    title: this.titleNotify,
                    text: this.textNotify,
                })
                .then(r => {
                    this.sendLoading = false;
                    this.$emit("closeNotify");
                })
                .catch(e => {
                    this.sendLoading = false;
                });
        },
    },
};
</script>
