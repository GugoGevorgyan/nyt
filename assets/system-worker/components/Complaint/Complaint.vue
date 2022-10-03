<!-- @format -->

<template>
    <v-card v-if="complaint" class="border">
        <v-card-title v-if="complaint" class="grey lighten-3">
            Жалоба на {{ complaint.recipient.name }} {{ complaint.recipient.patronymic }}
            {{ complaint.recipient.surname }}
            <v-spacer />
            <v-btn icon color="error" @click="close()">
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider class="mb-5" />
        <v-card-text style="max-height: 750px; overflow-y: hidden">
            <v-row>
                <v-col cols="12" md="8">
                    <div class="mb-1 d-flex">
                        <span style="color: #f9a825; font-size: 18px">Жалоба</span>
                        <v-spacer />
                        <v-chip class="mx-2" x-small outlined :color="complaint.status.color">
                            {{ complaint.status.text }}
                        </v-chip>
                        <v-menu v-if="manager" right offset-x transition="scale-transition">
                            <template v-slot:activator="{ on }">
                                <v-btn :loading="statusLoading" icon color="grey darken-2" small v-on="on">
                                    <v-icon small v-text="'mdi-cogs'" />
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item-group v-model="complaint.status.complaint_status_id">
                                    <v-list-item
                                        @click="changeStatus(status.complaint_status_id)"
                                        v-for="status in statuses"
                                        :key="status.complaint_status_id"
                                        :value="status.complaint_status_id"
                                    >
                                        <v-list-item-subtitle>
                                            {{ status.text }}
                                        </v-list-item-subtitle>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-menu>
                    </div>
                    <div class="mb-4">
                        <div class="font-weight-medium pl-2" style="height: 50px">
                            <span style="font-size: 16px">{{ complaint.subject }}</span>
                        </div>
                        <div class="d-flex">
                            <div
                                :class="complaint.files.length ? 'mr-2' : null"
                                style="width: 100%; height: 200px; overflow-y: auto; border: 1px dashed"
                                class="pa-2"
                            >
                                <p class="ma-0" v-html="complaint.complaint"></p>
                            </div>
                            <div v-if="complaint.files.length" style="position: relative; height: 200px; width: 200px">
                                <div
                                    @click="dialogFiles = complaint.files.map(item => item.file)"
                                    style="
                                        cursor: pointer;
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        height: 100%;
                                        width: 100%;
                                        background-color: rgba(0, 0, 0, 0.5);
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        color: white;
                                        z-index: 9;
                                    "
                                >
                                    <span>Файлы: {{ complaint.files.length }}</span>
                                </div>
                                <v-img cover width="200" height="200" :src="complaint.files[0].file" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <span style="color: #f9a825; font-size: 18px">Заказ</span>
                    </div>
                    <div style="width: 100%; height: 460px; overflow-y: hidden; border: 1px dashed">
                        <div v-if="complaint.order">
                            <info-box :order-id="complaint.order.order_id" :order-obj="null" :height="458" />
                        </div>
                        <div v-else style="height: 100%; width: 100%" class="d-flex justify-center align-center">
                            <span>Жалоба не связана с заказом</span>
                        </div>
                    </div>
                </v-col>
                <v-col cols="12" md="4">
                    <div class="mb-4">
                        <span style="color: #f9a825; font-size: 18px">Ответы</span>
                    </div>
                    <div style="border: 1px dashed gray; position: relative" class="px-2;">
                        <div>
                            <v-btn
                                v-if="!sheet"
                                fab
                                small
                                @click="sheet = true"
                                color="primary"
                                class="mr-3 mb-3"
                                style="position: absolute; bottom: 0; z-index: 999; right: 0"
                            >
                                <v-icon color="grey lighten-5" v-text="'mdi-message-outline'" />
                            </v-btn>
                        </div>
                        <div
                            ref="comments"
                            class="pr-2"
                            :style="{ height: last_page > 1 ? '640px' : '690px' }"
                            style="overflow-y: auto; position: relative"
                        >
                            <template>
                                <v-list v-if="comments.length" :style="{ opacity: loading ? 0.5 : 1 }">
                                    <v-list-item
                                        v-for="comment in comments"
                                        :key="comment.complaint_comment_id"
                                        class="pa-0"
                                        :class="comment.worker_id !== user.system_worker_id ? 'justify-end' : null"
                                    >
                                        <v-list-item-content class="pa-1" style="flex: initial; max-width: 75%">
                                            <div
                                                style="border-radius: 10px"
                                                :style="{
                                                    'background-color':
                                                        comment.worker_id === user.system_worker_id
                                                            ? '#CFD8DC'
                                                            : '#F5F5F5',
                                                }"
                                                class="pa-2 elevation-1"
                                            >
                                                <div :class="comment.new ? 'new-comment' : null" style="width: 100%">
                                                    <div class="d-flex">
                                                        <v-avatar color="#424242" size="28" class="mr-2">
                                                            <v-img
                                                                v-if="comment.worker.photo"
                                                                :src="comment.worker.photo"
                                                            />
                                                            <v-icon small v-else dark>mdi-camera-outline</v-icon>
                                                        </v-avatar>
                                                        <div style="width: 100%">
                                                            <div class="d-flex justify-space-between align-center mb-1">
                                                                <span
                                                                    class="font-weight-bold mr-4"
                                                                    style="font-size: 12px"
                                                                >
                                                                    {{ comment.worker.name }}
                                                                    {{ comment.worker.patronymic }}
                                                                    {{ comment.worker.surname }}
                                                                </span>
                                                                <div>
                                                                    <span
                                                                        class="mx-1"
                                                                        style="color: #f9a825; font-size: 12px"
                                                                        >{{ comment.created_at | formatTime }}</span
                                                                    >
                                                                    <span
                                                                        class="font-weight-bold"
                                                                        style="font-size: 10px"
                                                                        >{{ comment.created_at | formatDate }}</span
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div
                                                                    v-if="comment.text"
                                                                    :class="comment.files.length ? 'pr-2' : null"
                                                                    style="width: 100%"
                                                                >
                                                                    <p
                                                                        style="font-size: 12px"
                                                                        class="ma-0"
                                                                        v-html="comment.text"
                                                                    ></p>
                                                                </div>
                                                                <div
                                                                    v-if="comment.files.length"
                                                                    style="position: relative"
                                                                >
                                                                    <div
                                                                        @click="
                                                                            dialogFiles = comment.files.map(
                                                                                item => item.file,
                                                                            )
                                                                        "
                                                                        style="
                                                                            cursor: pointer;
                                                                            position: absolute;
                                                                            top: 0;
                                                                            left: 0;
                                                                            height: 100%;
                                                                            width: 100%;
                                                                            background-color: rgba(0, 0, 0, 0.5);
                                                                            display: flex;
                                                                            justify-content: center;
                                                                            align-items: center;
                                                                            color: white;
                                                                            z-index: 9;
                                                                        "
                                                                    >
                                                                        <span style="font-size: 12px"
                                                                            >Файлы: {{ comment.files.length }}</span
                                                                        >
                                                                    </div>
                                                                    <v-img
                                                                        width="70px"
                                                                        height="70px"
                                                                        cover
                                                                        :src="comment.files[0].file"
                                                                    ></v-img>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                                <div
                                    v-else-if="!loading"
                                    style="height: 100%; width: 100%"
                                    class="d-flex justify-center align-center"
                                >
                                    <span>Еще нет ни одного ответа на эту жалобу</span>
                                </div>
                            </template>

                            <v-overlay :opacity="0" absolute :value="loading">
                                <v-progress-circular indeterminate :width="2" :size="50" color="yellow darken-3" />
                            </v-overlay>

                            <div style="position: relative; margin-left: -5px; margin-right: -5px">
                                <div class="message-box">
                                    <div class="d-flex justify-end">
                                        <v-btn
                                            v-if="sheet"
                                            fab
                                            x-small
                                            @click="sheet = false"
                                            color="error"
                                            class="mb-2 mr-3"
                                        >
                                            <v-icon color="white">mdi-close</v-icon>
                                        </v-btn>
                                    </div>
                                    <div
                                        class="message-content elevation-10"
                                        :class="sheet ? 'mc-visible' : 'mc-hidden'"
                                    >
                                        <v-row no-gutters>
                                            <v-col cols="12" md="8" style="position: relative">
                                                <div v-if="errors.first('text')" class="text-err-box">
                                                    <v-alert class="mx-2 mb-0" dense type="error">
                                                        <div class="d-flex align-center">
                                                            <small>{{ errors.first("text") }}</small>
                                                        </div>
                                                    </v-alert>
                                                </div>
                                                <v-textarea
                                                    @keypress="
                                                        13 === $event.keyCode && !sendDisabled ? makeComment() : null
                                                    "
                                                    data-vv-as="текст"
                                                    v-model="text"
                                                    color="yellow darken-3"
                                                    height="150"
                                                    solo
                                                    label="Введите текст"
                                                    name="text"
                                                    :error-messages="errors.collect('text')"
                                                    v-validate="'max:2500'"
                                                />
                                            </v-col>
                                            <v-col cols="12" md="4" style="position: relative">
                                                <v-overlay :opacity="0.7" absolute :value="fileErrors">
                                                    <v-alert class="mx-2 mb-0" dense type="error">
                                                        <div class="d-flex">
                                                            <small>{{ errors.first("files") || sizeErr }}</small>
                                                            <v-btn small icon color="white" @click="resetFiles()">
                                                                <v-icon small>mdi-close</v-icon>
                                                            </v-btn>
                                                        </div>
                                                    </v-alert>
                                                </v-overlay>
                                                <div class="" style="height: 150px; overflow-y: auto">
                                                    <v-row class="no-gutters" v-if="filePreviews.length">
                                                        <v-col
                                                            v-for="(preview, i) in filePreviews"
                                                            :key="i"
                                                            cols="12"
                                                            md="4"
                                                            style="height: 75px; position: relative"
                                                        >
                                                            <v-btn
                                                                @click="removeFile(i)"
                                                                x-small
                                                                icon
                                                                color="error"
                                                                style="
                                                                    position: absolute;
                                                                    top: 5px;
                                                                    right: 5px;
                                                                    z-index: 1;
                                                                    background: rgba(0, 0, 0, 0.5);
                                                                "
                                                            >
                                                                <v-icon small>mdi-close</v-icon>
                                                            </v-btn>
                                                            <v-img
                                                                @click="dialogFiles = [preview]"
                                                                style="cursor: pointer"
                                                                height="75px"
                                                                cover
                                                                :src="preview"
                                                            />
                                                        </v-col>
                                                    </v-row>
                                                    <div
                                                        v-else
                                                        style="
                                                            height: 100%;
                                                            display: flex;
                                                            justify-content: center;
                                                            align-items: center;
                                                            border-bottom: 1px dashed gray;
                                                            overflow: hidden;
                                                        "
                                                    >
                                                        <v-subheader>нет прикрепленных файлов</v-subheader>
                                                    </div>
                                                </div>
                                                <div style="height: 50px; display: flex; align-items: center">
                                                    <input
                                                        class="d-none"
                                                        multiple
                                                        name="files"
                                                        data-vv-as="файлы"
                                                        v-validate="'ext:jpeg,jpg,png'"
                                                        ref="files"
                                                        hidden
                                                        type="file"
                                                        @change="getFiles($event)"
                                                    />
                                                    <v-btn
                                                        @click="makeComment()"
                                                        :loading="sendLoading"
                                                        :disabled="sendDisabled"
                                                        fab
                                                        x-small
                                                        class="mx-1 primary"
                                                    >
                                                        <v-icon
                                                            small
                                                            color="white"
                                                            v-text="'mdi-message-arrow-right-outline'"
                                                        />
                                                    </v-btn>
                                                    <v-btn
                                                        @click="$refs.files.click()"
                                                        fab
                                                        x-small
                                                        outlined
                                                        color="yellow darken-3"
                                                        class="mx-1 ml-10"
                                                    >
                                                        <v-icon small color="black" v-text="'mdi-paperclip'" />
                                                    </v-btn>
                                                </div>
                                            </v-col>
                                        </v-row>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <v-pagination
                        v-if="last_page > 1"
                        class="mt-2"
                        :length="last_page"
                        :total-visible="7"
                        circle
                        color="yellow darken-3"
                        v-model="page"
                    />
                </v-col>
            </v-row>
        </v-card-text>

        <files-show-dialog
            :persistent="false"
            :files="dialogFiles"
            @close="dialogFiles = []"
            max-width="900"
            max-height="500"
        />
    </v-card>
</template>

<script>
import Snackbar from "../../facades/Snackbar";
import axios from "axios";
import { serialize } from "object-to-formdata";
import FilesShowDialog from "./FilesShowDialog";
import InfoBox from "../Order/InfoBox";

export default {
    name: "Complaint",

    components: { InfoBox, FilesShowDialog },

    props: {
        complaint: {
            required: true,
        },
        statuses: {
            required: true,
        },
        manager: {
            required: true,
            type: Boolean,
        },
    },

    data() {
        return {
            last_page: 0,
            page: 1,
            per_page: 10,
            filesMaxSize: 2048,
            sizeErr: null,
            comments: [],
            loading: false,
            sheet: false,
            files: [],
            filePreviews: [],
            text: null,
            sendLoading: false,
            statusLoading: false,
            interval: null,
            dialogFiles: [],
        };
    },

    filters: {
        formatTime: function (value) {
            let date = new Date(value);
            return `${date.getHours()}:${date.getMinutes() <= 9 ? "0" + date.getMinutes() : date.getMinutes()} `;
        },
        formatDate: function (value) {
            let date = new Date(value);
            return `${date.getDay()}/${date.getMonth()}/${date.getFullYear()}`;
        },
    },

    computed: {
        url() {
            return this.$store.state.initUrl;
        },
        commentUrl() {
            return this.url + "complaint/comments/" + this.complaint.complaint_id + "?page=" + this.page;
        },

        fileErrors() {
            return !!this.errors.first("files") || !!this.sizeErr;
        },

        sendDisabled() {
            return !this.text && !this.files.length;
        },
        user() {
            return this.$store.state.auth.user;
        },
    },

    watch: {
        complaint() {
            if (this.complaint) {
                this.getComments();
                this.getCommentsInterval();
            } else {
                this.resetAll();
            }
        },
        page() {
            this.getComments();
        },
        files() {
            let size = 0;
            this.files.forEach(item => (size = size + item.size));
            size / 1024 > this.filesMaxSize
                ? (this.sizeErr = "Размер загружаемых файлов не должен превышать 2мб")
                : (this.sizeErr = null);
        },
    },

    methods: {
        attachFiles() {
            this.$refs.files.click();
        },

        getFiles(event) {
            for (let i = 0; i < event.target.files.length; i++) {
                let index = this.files.length;
                let file = event.target.files[i];
                this.files.push(file);
                this.filePreviews[index] = null;
                this.getPreview(file, index);
            }
        },

        getPreview(file, index) {
            let reader = new FileReader();
            reader.onload = e => {
                this.filePreviews.splice(index, 1, e.target.result);
            };
            reader.readAsDataURL(file);
        },

        removeFile(index) {
            this.filePreviews.splice(index, 1);
            this.files.splice(index, 1);
        },

        resetFiles() {
            this.files = [];
            this.filePreviews = [];
            this.$refs.files.value = null;
            this.errors.clear();
        },

        resetAll() {
            this.resetFiles();
            this.text = null;
            this.sheet = false;
            this.page = 1;
            this.comments = [];
            clearInterval(this.interval);
        },

        getCommentsInterval() {
            this.interval = setInterval(() => this.getComments(false), 10000);
        },

        addComment(comment) {
            if (1 === this.page) {
                comment.new = true;
                this.comments.unshift(comment);
                if (this.comments.length > this.per_page) {
                    this.comments.pop();
                }
                this.$refs.comments.scrollTo(0, 0);
            }

            this.resetFiles();
            this.text = null;
            this.sheet = false;
        },

        getComments(loading = true) {
            if (loading) {
                this.loading = true;
            }

            axios
                .get(this.commentUrl)
                .then(response => {
                    this.loading = false;
                    this.comments = response.data.data;
                    this.last_page = response.data.last_page;
                    this.per_page = response.data.per_page;
                })
                .catch(error => {
                    this.loading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        makeComment() {
            this.$validator.validateAll().then(valid => {
                if (valid && !this.sizeErr) {
                    this.sendLoading = true;
                    let data = {
                        complaint_id: this.complaint.complaint_id,
                        text: this.text,
                        files: this.files,
                    };

                    axios
                        .post(this.url + "complaint/comment/create", serialize(data, { indices: true }))
                        .then(response => {
                            this.sendLoading = false;
                            this.addComment(response.data.comment);
                        })
                        .catch(error => {
                            this.sendLoading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },

        changeStatus(status_id) {
            this.statusLoading = true;
            axios
                .post(this.url + "complaint/status", {
                    status_id: status_id,
                    complaint_id: this.complaint.complaint_id,
                })
                .then(response => {
                    this.statusLoading = false;
                    this.complaint.status = response.data.status;
                    this.complaint.status_id = response.data.status.complaint_status_id;
                })
                .catch(error => {
                    this.statusLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        close() {
            clearInterval(this.interval);
            this.$emit("close");
        },
    },

    created() {
        this.getComments();
        this.getCommentsInterval();
    },
};
</script>

<style scoped>
.message-box {
    z-index: 9;
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
}

.message-content {
    transition: 0.3s;
    background-color: white;
    border: 1px solid rgb(249, 168, 37);
    border-radius: 4px;
    overflow: hidden;
}
.mc-visible {
    height: 200px;
    visibility: visible;
}
.mc-hidden {
    height: 0;
    border: none !important;
    visibility: hidden;
}

.new-comment {
    animation-duration: 0.5s;
    animation-name: show;
}

.text-err-box {
    z-index: 9;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70%;
}

@keyframes show {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}
</style>
