<!-- @format -->

<template>
    <v-card class="border">
        <v-card-title>Вы уверены, что хотите удалить отмеченные аэропорты ?</v-card-title>

        <v-card-text>
            <v-alert outlined type="error"> После удалеиня информация будет утерена! </v-alert>
            <v-form>
                <v-text-field
                    color="yellow darken-3"
                    outlined
                    dense
                    :error-messages="errors.collect('confirm_delete_password')"
                    autofocus
                    label="Введите пароль"
                    name="confirm_delete_password"
                    placeholder="Необходимо ввести пароль"
                    type="password"
                    v-model="confirmPassword"
                    v-validate="'min:5|max:32|required'"
                    data-vv-as="пароль"
                />
            </v-form>
        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn small @click="$emit('deleteDialog', false)" text>отменить</v-btn>
            <v-btn small @click="destroy()" color="error">удалить</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import Snackbar from '../../../facades/Snackbar';

export default {
    name: 'AirportDeleteComponent',

    props: {
        model: {},
        selected: {},
    },

    data() {
        return {
            confirmPassword: null,
        };
    },

    methods: {
        destroy() {
            let id = this.selected[0].airport_id ?? this.selected[0].metro_id ?? this.selected[0].railway_id;
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.deleteLoading = true;
                    this.model
                        .delete({ airport: id })
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.deleteLoading = false;
                            this.$emit('deleteDialog', false);
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.deleteLoading = false;
                        });
                }
            });
        },
    },
};
</script>

<style scoped></style>
