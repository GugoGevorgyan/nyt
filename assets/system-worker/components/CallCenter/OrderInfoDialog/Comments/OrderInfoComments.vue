<!-- @format -->

<template>
    <div>
        <div ref="comments" :style="{ height: contentHeight - 10 + 'px' }" style="overflow-y: auto">
            <v-list v-if="comments.length">
                <v-list-item v-for="item in comments" :key="item.order_worker_comment_id">
                    <v-list-item-content>
                        <div class="d-flex justify-space-between mb-1">
                            <span class="subtitle-1">
                                {{ item.worker.surname }}
                                {{ item.worker.name }}
                                {{ item.worker.patronymic }}
                            </span>
                            <small style="color: #f9a825">{{ getDateShorted(item.created_at) }}</small>
                        </div>
                        <p>{{ item.text }}</p>
                        <v-divider />
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <div v-else class="d-flex justify-center align-center" style="height: 100%; width: 100%">
                <span>{{ loading ? "загрузка коментариев" : "нет коментариев" }}</span>
            </div>
        </div>
        <div class="pa-2" style="height: 150px; background-color: #f5f5f5">
            <textarea
                v-model="text"
                @keypress="13 === $event.keyCode && !disabled ? send() : null"
                style="border-radius: 3px; background: #fff; width: 100%; height: 100px; padding: 10px"
            />
            <div class="d-flex py-2">
                <v-switch
                    v-if="process"
                    v-model="forDriver"
                    dense
                    hide-details
                    inset
                    class="ma-0 pa-0"
                    label="Для водителей"
                    color="yellow darken-3"
                />
                <v-spacer />
                <v-btn
                    class="rounded-2"
                    @click="send()"
                    depressed
                    :loading="createLoading"
                    :disabled="disabled"
                    small
                    color="yellow darken-3"
                    :dark="!disabled"
                >
                    <v-icon v-text="'mdi-share'" />
                    отправить
                </v-btn>
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment-timezone";
import Snackbar from "../../../../facades/Snackbar";

export default {
    props: {
        process: {
            required: true,
        },
        comments: {
            required: true,
        },
        order: {
            required: true,
        },
        height: {
            required: true,
        },
        loading: {
            required: true,
        },
    },
    data() {
        return {
            forDriver: false,
            text: null,
            createLoading: false,
        };
    },
    watch: {
        comments(newVal, oldVal) {
            if (oldVal.length < newVal.length) {
                this.$refs.comments.scrollTop = this.$refs.comments.scrollHeight;
            }
        },
    },
    computed: {
        disabled() {
            return !this.text || this.text.length < 1 || this.text.length > 2500;
        },
        contentHeight() {
            return this.height - 150;
        },
    },
    methods: {
        isToday(someDate) {
            let today = new Date();
            return (
                someDate.getDate() === today.getDate() &&
                someDate.getMonth() === today.getMonth() &&
                someDate.getFullYear() === today.getFullYear()
            );
        },
        getDateShorted(date) {
            let itemDate = new Date(date);
            return this.isToday(itemDate)
                ? moment(itemDate).format("HH:mm")
                : moment(itemDate).format("DD/MMM/YYYY") + " " + moment(itemDate).format("HH:mm");
        },

        send() {
            this.createLoading = true;
            this.$http
                .post("call-center/order-comment/create", {
                    text: this.text,
                    order_id: this.order.order_id,
                    for_driver: this.forDriver,
                })
                .then(response => {
                    this.$emit("updateComments", response.data);
                    this.text = null;
                    this.createLoading = false;
                })
                .catch(error => {
                    if (422 === error.response.status) {
                        Snackbar.error(error.response.data.message);
                    }

                    this.createLoading = false;
                });
        },
    },
};
</script>
