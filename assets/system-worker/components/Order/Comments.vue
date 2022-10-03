<!-- @format -->

<template>
    <div style="height: 100%">
        <v-list class="my-0" v-if="comments.length">
            <v-list-item class="px-0" v-for="item in comments" :key="item.order_worker_comment_id">
                <v-list-item-content class="py-0">
                    <div class="d-flex justify-space-between mb-1">
                        <span class="font-weight-medium" style="font-size: 12px">
                            {{ item.worker.surname }}
                            {{ item.worker.name }}
                            {{ item.worker.patronymic }}
                        </span>
                        <small style="color: #f9a825">{{ __getDateShorted(item.created_at) }}</small>
                    </div>
                    <p class="mb-2" style="font-size: 12px">{{ item.text }}</p>
                    <v-divider class="mt-0 mb-4"></v-divider>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <div v-else class="d-flex justify-center align-center" style="height: 100%; width: 100%">
            <span>нет коментариев</span>
        </div>
    </div>
</template>
<script>
import { mutators } from "../../mixins/Mutators";

export default {
    name: "Comments",
    props: {
        comments: {
            required: true,
        },
    },
    mixins: [mutators],
};
</script>
