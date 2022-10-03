<template>
    <v-dialog v-model='orderRepeat.status' width='max-content' persistent>
        <v-card>
            <v-card-actions>
                <v-card-title class='py-0 px-0 ma-auto'>
                    {{ orderRepeat.title }}
                </v-card-title>
            </v-card-actions>
            <v-divider/>
            <v-card-actions>
                <v-spacer />
                <v-btn
                    @click='continueOrder'
                    color='orange'
                >
                    Продолжить поиск
                </v-btn>
                <v-btn @click='cancelOrder'>
                    Отменить
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapMutations, mapState } from 'vuex';

export default {
    name: 'RepeatOrder',
    computed: {
        ...mapState(["orderRepeat", "order"])
    },
    methods: {
        ...mapMutations(["initOrderRepeat", "initOrderForm"]),

        continueOrder() {
            this.$http
                .get(`/continue_order/${this.order.order_id}`)
                .then(response => {
                    this.initOrderRepeat({
                        status: false,
                        title: "",
                        continue: true,
                        cancel: false
                    })
                    this.$store.state.order.order_time.repeated_at = response.data.repeated_at;
                }).catch();
        },
        cancelOrder() {
            this.initOrderRepeat({
                status: false,
                title: "",
                continue: false,
                cancel: true,
            });
        },
    },
};
</script>

<style scoped>

</style>
