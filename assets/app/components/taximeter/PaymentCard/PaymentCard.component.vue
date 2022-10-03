<!-- @format -->

<template>
    <v-card elevation="4" class="border" max-height="450">
        <v-card-title>
            Добавление карты
            <v-spacer />
            <v-btn
                icon
                small
                outlined
                color="grey darken-1"
                @click="
                    paymentCard = PAYMENT_TYPE.CASH;
                    paymentDialog = false;
                "
            >
                <v-icon v-text="'mdi-close'" color="grey darken-3" />
            </v-btn>
        </v-card-title>

        <v-card-text class="mt-5">
            <span>Номер карточки</span>
            <v-text-field
                autofocus
                mode="debounce"
                v-model="model.ccNumber"
                type="text"
                v-mask="'####-####-####-#######'"
                placeholder="1234-5678-9112-3456789"
                name="cc_number"
                prepend-inner-icon="mdi-credit-card"
                dense
                outlined
                height="40"
                color="yellow darken-3"
                background-color="grey lighten-4"
                class="rounded-1"
                v-validate="'required|min:19|max:22'"
                :error-messages="errors.collect('cc_number')"
            />
            <v-row>
                <v-col>
                    <span>Дата истечения</span>
                    <v-text-field
                        :disabled="numberInValid"
                        v-model="model.ccExpiration"
                        prepend-inner-icon="mdi-credit-card-clock"
                        type="text"
                        v-mask="'##/##'"
                        placeholder="21/12"
                        name="cc_expiration"
                        dense
                        outlined
                        height="40"
                        color="yellow darken-4"
                        background-color="grey lighten-4"
                        class="rounded-1"
                        v-validate="'required|min:5|max:5'"
                        :error-messages="errors.collect('cc_expiration')"
                    />
                </v-col>
                <v-col>
                    <span>CVC/CVV</span>
                    <v-text-field
                        v-model="model.ccCvc"
                        :disabled="numberInValid"
                        type="text"
                        v-mask="'###'"
                        name="cc_cvc"
                        placeholder="123"
                        dense
                        outlined
                        height="40"
                        color="yellow darken-3"
                        background-color="grey lighten-4"
                        class="rounded-1"
                        v-validate="'required|min:3|max:3'"
                        :error-messages="errors.collect('cc_cvc')"
                    />
                </v-col>
            </v-row>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <v-btn
                :disabled="numberInValid"
                :loading="loading"
                depressed
                style="margin-right: 8px"
                class="rounded-2"
                color="yellow darken-2"
                v-text="'Привязать'"
                @click="addCC"
            />
        </v-card-actions>
    </v-card>
</template>

<script lang="js" src="./PaymentCard.main.js" />
<style lang="scss" src="./PaymentCard.style.scss" scoped />
