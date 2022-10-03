<!-- @format -->

<template>
    <v-card max-height="70vh">
        <v-system-bar absolute>
            <span>Ответы по критериям </span>
        </v-system-bar>
        <v-divider />
        <v-list class="mt-5">
            <v-list-item v-if="question" v-for="(question, index) in questions" :key="question.report_id">
                <v-list-item-content style="max-width: 50px"> {{ index + 1 }}: </v-list-item-content>
                <v-list-item-content> {{ question.question }} </v-list-item-content>
                <v-list-item-content style="max-width: 80px">
                    <v-icon v-if="question.verified" v-text="'mdi-check'" color="green" />
                    <v-icon v-else v-text="'mdi-close'" color="red" />
                </v-list-item-content>

                <v-list-item-content style="max-width: 177px">
                    <small style="color: grey" v-if="question.comment">
                        {{ question.comment.substring(1, 30) }}
                    </small>
                </v-list-item-content>
                <v-list-item-content>
                    <v-menu
                        v-if="question.comment"
                        transition="slide-x-transition"
                        bottom
                        left
                        offset-y
                        :close-on-content-click="false"
                    >
                        <template v-slot:activator="{ on, attrs }" style="max-width: 30px">
                            <v-icon
                                v-bind="attrs"
                                icon
                                color="primary"
                                v-on="on"
                                small
                                v-text="'mdi-comment-account-outline'"
                            />
                        </template>
                        <v-list max-width="350">
                            <v-list-item>
                                <v-list-item-content>
                                    {{ question.comment }}
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-list-item-content>
            </v-list-item>
            <v-alert class="align-center" colored-border color="red" v-text="'NO QUESTIONS'"/>
        </v-list>
    </v-card>
</template>

<script>
import Waybill from "../../models/Waybill";

export default {
    name: "InfoDialog",

    props: ["waybillId"],

    data() {
        return {
            waybill: new Waybill(this.item),
            questions: [],
        };
    },

    created() {
        this.waybill.waybillInfo(this.waybillId).then(result => {
            this.questions = result.data;
        });
    },
};
</script>

<style scoped></style>
