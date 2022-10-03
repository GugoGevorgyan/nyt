<!-- @format -->

<template>
    <v-dialog v-model="dialog" persistent max-width="290" width="100%">
        <v-card tile>
            <v-card-title class="headline">Accept Code</v-card-title>
            <v-card-text>
                <v-text-field
                    color="yellow darken-3"
                    v-mask="'+#(###)-###-##-##'"
                    placeholder="+7(995)-999-99-99"
                    v-model="client.phone"
                    dense
                    name="phone"
                    v-validate="'required'"
                    :error-messages="errors.collect('phone')"
                    data-vv-as="Телефон"
                    label="Телефон *"
                    disabled
                />
                <v-text-field
                    color="yellow darken-3"
                    placeholder="Accept"
                    v-model="acceptCode"
                    dense
                    type="text"
                    v-mask="'#-#-#-#-#-#'"
                    name="accept_code"
                    v-validate="'required|min:11|max:11'"
                    :error-messages="errors.collect('accept_code')"
                    data-vv-as="Accept"
                    label="Accept"
                    autofocus
                />
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="sendAcceptCode">Agree</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapState } from 'vuex';
import axios from 'axios';

export default {
    name: 'AcceptComponent',

    props: { dialog: null },

    data() {
        return {
            acceptCode: null,
        };
    },

    computed: {
        ...mapState(['orderForm', 'client']),
    },

    methods: {
        sendAcceptCode() {
            axios
                .post('app_login_by_phone', { phone: this.client.phone, accept_code: this.acceptCode.replace(/[-]/g, '') })
                .then(result => {
                    console.log(result);
                });
        },
    },
};
</script>
