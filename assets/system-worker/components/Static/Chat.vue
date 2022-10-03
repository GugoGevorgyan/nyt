<!-- @format -->

<template>
    <v-layout>
        <v-navigation-drawer
            :height="window.height"
            :hide-overlay="true"
            clipped
            fixed
            overflow
            persistent
            right
            style="margin-top: 3.4%"
            v-model="messenger.toggleMsgContent"
        >
            <v-list-item>
                <v-list-item-avatar>
                    <v-img src="https://randomuser.me/api/portraits/men/78.jpg"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>John Leider</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list :height="window.height" class="" dense style="overflow-y: auto">
                <v-list-item
                    :data-content="worker.room_id"
                    :key="worker.room_id"
                    @click="selectRoom"
                    link
                    v-for="worker in workers"
                >
                    <v-list-item-icon style="width: 50px; height: 50px">
                        <v-img :src="'http://nyt.loc/img/' + avatar" style="width: 100%" />
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ worker.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-progress-circular
                    :width="2"
                    color="orange"
                    indeterminate
                    style="margin-left: 45%; margin-top: 40%"
                    v-if="loadMsgContent"
                ></v-progress-circular>
            </v-list>
        </v-navigation-drawer>

        <v-dialog
            :data-content="conversationRoomId"
            :hide-overlay="true"
            max-width="50%"
            width="100%"
            persistent
            v-model="correspondenceDialog"
        >
            <v-card class="" max-height="300px">
                <v-btn @click="correspondenceDialog = false" icon>
                    <v-icon>mdi-close-box</v-icon>
                </v-btn>
                <v-divider class="mb-3"></v-divider>
                <v-chip-group column>
                    <v-row cols="6">
                        <v-chip
                            :class="
                                message.senderable_id === auth.user.system_worker_id
                                    ? 'float-left mb-2 mt-2'
                                    : 'float-right mb-2 mt-2'
                            "
                            :color="
                                message.senderable_id === auth.user.system_worker_id
                                    ? 'grey lighten-1'
                                    : 'grey lighten-3'
                            "
                            :key="message.message_id"
                            style="width: 50%"
                            v-for="message in messages"
                        >
                            {{ message.message }}
                        </v-chip>
                    </v-row>
                </v-chip-group>
            </v-card>
            <v-text-field
                class="mt-3 mb-0"
                outlined
                rounded
                style="position: fixed; max-width: 500px"
                v-model="messageSended"
                v-on:keyup.enter="sendMessage"
            ></v-text-field>
        </v-dialog>
    </v-layout>
</template>

<script>
import axios from "axios";
import { mapMutations, mapState } from "vuex";

export default {
    data() {
        return {
            load: false,
            correspondenceDialog: false,
            conversationRoomId: null,
            height: 65,
            toggle: false,
            messages: [],
            messageSended: "",
            avatar: "taxi-Search.svg",
            window: {
                width: 0,
                height: window.innerHeight - this.height,
            },
        };
    },

    watch: {},

    computed: {
        ...mapState(["messenger"]),

        workers() {
            return this.$store.state.chatWorkers;
        },

        loadMsgContent() {
            let load = this.$store.state.toggleChatContentLoader;
            this.load = load;
            return load;
        },

        auth() {
            return this.$store.state.auth;
        },
    },

    methods: {
        ...mapMutations({ initMessenger: "initMessenger" }),

        handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight - this.height;
        },

        selectRoom(e) {
            let roomId = e.currentTarget.getAttribute("data-content");
            this.conversationRoomId = roomId;
            this.correspondenceDialog = true;
            axios
                .get(`get-conversation/${roomId}`)
                .then(response => {
                    this.messages = response.data[0];
                })
                .catch(error => {});
        },

        sendMessage() {
            axios
                .post("send-msg", {
                    room_id: this.conversationRoomId,
                    message_data: this.messageSended,
                })
                .then(response => {
                    this.messages.push({
                        senderable_id: this.auth.user.system_worker_id,
                        room_id: this.conversationRoomId,
                        message: this.messageSended,
                    });
                    this.messageSended = null;
                })
                .catch();
        },
    },

    created() {
        window.addEventListener("resize", this.handleResize);
    },
};
</script>

<style scoped>
.v-navigation-drawer__content {
    overflow: hidden !important;
}
</style>
