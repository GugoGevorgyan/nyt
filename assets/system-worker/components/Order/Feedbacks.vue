<!-- @format -->

<template>
    <div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Отзывы"></info-subtitle>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Отзыв клиента
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 align-center">
                    <template v-if="clientFeedBack">
                        <v-rating
                            dense
                            small
                            readonly
                            :value="clientFeedBack.assessment"
                            :color="clientFeedBack ? __assessmentColor(clientFeedBack.assessment) : 'gray'"
                            :background-color="clientFeedBack ? __assessmentColor(clientFeedBack.assessment) : 'gray'"
                        />
                        <p class="pl-1">{{ feedbackText(clientFeedBack) }}</p>
                    </template>
                    <v-chip v-else color="error" outlined x-small>нет отзывоа от клиента</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Отзыв водителя
                </v-col>
                <v-col cols="12" md="9" class="font-weight-medium pl-2 align-center">
                    <template v-if="driverFeedBack">
                        <v-rating
                            dense
                            small
                            readonly
                            :value="driverFeedBack ? driverFeedBack.assessment : 0"
                            :color="driverFeedBack ? __assessmentColor(driverFeedBack.assessment) : 'gray'"
                            :background-color="driverFeedBack ? __assessmentColor(driverFeedBack.assessment) : 'gray'"
                        />
                        <p class="pl-1">{{ feedbackText(driverFeedBack) }}</p>
                    </template>
                    <v-chip v-else color="error" outlined x-small>нет отзывоа от водителя</v-chip>
                </v-col>
            </v-row>
            <v-row no-gutters class="mb-2">
                <v-col cols="12" md="3" class="pr-2" style="border-right: 1px solid rgba(0, 0, 0, 0.12)">
                    Отзывы работников
                </v-col>
                <v-col
                    cols="12"
                    md="9"
                    class="font-weight-medium pl-2 align-center"
                    :class="workerFeedBacks.length ? 'd-block' : 'd-flex'"
                >
                    <template v-if="workerFeedBacks.length">
                        <div v-for="(feedback, index) in workerFeedBacks" :key="feedback.order_feedback_id">
                            <div class="d-flex align-center">
                                <v-rating
                                    class="mr-1"
                                    dense
                                    small
                                    readonly
                                    :value="feedback.assessment"
                                    :color="__assessmentColor(feedback.assessment)"
                                    :background-color="__assessmentColor(feedback.assessment)"
                                ></v-rating>
                                <span v-if="feedback.readable_type === 'driver'">o водителе</span>
                                <span v-else-if="feedback.readable_type === 'client'">o клиенте</span>
                            </div>

                            <p class="pl-1 small">{{ feedbackText(feedback) }}</p>
                            <v-divider v-if="index !== workerFeedBacks.length - 1"/>
                        </div>
                    </template>
                    <v-chip v-else color="error" outlined x-small>нет отзывов от работников</v-chip>
                </v-col>
            </v-row>
        </div>
        <div class="mb-6" style="font-size: 12px">
            <info-subtitle text="Жалобы"></info-subtitle>
            <template v-if="history.complaints.length">
                <div v-for="(complaint, index) in history.complaints" class="mb-1 mt-0">
                    <p class="mb-1">
                        <span>От:</span>
                        <span style="color: #00c853" class="mr-1">
                            {{ complaint.writer.name }}
                            {{ complaint.writer.patronymic }}
                            {{ complaint.writer.surname }}
                        </span>
                        <span>на:</span>
                        <span style="color: #c62828">
                            {{ complaint.recipient.name }}
                            {{ complaint.recipient.patronymic }}
                            {{ complaint.recipient.surname }}
                        </span>
                    </p>
                    <p class="mb-1 font-weight-bold">{{ complaint.subject }}</p>
                    <p class="mb-1 small font-weight-medium">{{ complaint.complaint }}</p>
                    <v-divider class="mt-0 mb-4" v-if="index !== history.complaints.length - 1"/>
                </div>
            </template>
            <v-chip v-else color="error" outlined x-small>нет жалоб на работников</v-chip>
        </div>
    </div>
</template>
<script>
import { mutators } from "../../mixins/Mutators";
import InfoSubtitle from "./InfoSubtitle";

export default {
    name: "Feedbacks",
    components: { InfoSubtitle },
    props: {
        history: {
            required: true,
        },
        clientFeedBack: {
            required: true,
        },
        driverFeedBack: {
            required: true,
        },
        workerFeedBacks: {
            required: true,
        },
    },
    mixins: [mutators],
    methods: {
        feedbackText(feedback) {
            if (feedback.text) {
                return feedback.text;
            } else if (feedback.option) {
                return feedback.option.name;
            }
        },
    },
};
</script>
