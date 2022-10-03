<!-- @format -->

<template>
    <v-card>
        <v-card-title class="grey lighten-3">
            Составление новой жалобы
            <v-spacer />
            <v-btn :disabled="loading" outlined color="error" icon small @click="close()">
                <v-icon small v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider class="mb-5" />
        <v-card-text>
            <v-row>
                <v-col cols="12" md="8">
                    <v-chip v-if="worker" color="error" outlined class="mb-4">
                        Жалоба на {{ worker.name }} {{ worker.patronymic }} {{ worker.surname }}
                    </v-chip>
                    <v-select
                        color="yellow darken-3"
                        v-else
                        :error-messages="errors.collect('recipient_id')"
                        label="На кого жалоба"
                        name="recipient_id"
                        v-model="complaint.recipient_id"
                        v-validate="complaint.rules.recipient_id"
                        outlined
                        dense
                        data-vv-as="на кого"
                        :items="workers"
                        :loading="workersLoading"
                        item-value="system_worker_id"
                    >
                        <template v-slot:item="{ item }">
                            <small>{{ item.name }} {{ item.patronymic }} {{ item.surname }}</small>
                        </template>
                        <template v-slot:selection="{ item }">
                            <small>{{ item.name }} {{ item.patronymic }} {{ item.surname }}</small>
                        </template>
                    </v-select>

                    <search-order
                        :order-id="complaint.order_id"
                        @accept="complaint.order_id = $event"
                        @discharge="complaint.order_id = null"
                    />

                    <v-textarea
                        color="yellow darken-3"
                        :error-messages="errors.collect('subject')"
                        label="Тема"
                        name="subject"
                        v-model="complaint.subject"
                        v-validate="complaint.rules.subject"
                        outlined
                        dense
                        data-vv-as="тема"
                        rows="2"
                    />
                    <v-textarea
                        color="yellow darken-3"
                        :error-messages="errors.collect('complaint')"
                        label="Жалоба"
                        name="complaint"
                        v-model="complaint.complaint"
                        v-validate="complaint.rules.complaint"
                        outlined
                        dense
                        data-vv-as="жалоба"
                        rows="6"
                    />
                </v-col>
                <v-col cols="12" md="4">
                    <div style="border: 1px dashed gray" class="mb-2">
                        <div style="height: 423px; overflow-y: auto">
                            <v-row class="no-gutters" v-if="complaint.previews.length">
                                <v-col
                                    v-for="(preview, i) in complaint.previews"
                                    :key="i"
                                    :cols="previewCols"
                                    style="position: relative"
                                >
                                    <v-btn
                                        @click="complaint.removeFile(i, 'files', 'preview')"
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
                                        <v-icon small v-text="'mdi-close'" />
                                    </v-btn>
                                    <v-img
                                        @click="dialogFiles = [preview]"
                                        style="cursor: pointer"
                                        :height="previewHeight"
                                        cover
                                        :src="preview"
                                    />
                                </v-col>
                            </v-row>
                            <div
                                v-else
                                style="height: 100%; display: flex; justify-content: center; align-items: center"
                            >
                                <v-subheader>нет прикрепленных файлов</v-subheader>
                            </div>
                        </div>
                    </div>
                    <input
                        class="d-none"
                        multiple
                        name="files"
                        data-vv-as="файлы"
                        v-validate="'ext:jpeg,jpg,png'"
                        ref="files"
                        hidden
                        type="file"
                        @change="complaint.getFiles($event, 'files', 'previews')"
                    />
                    <v-tooltip right>
                        <template v-slot:activator="{ on: on }">
                            <v-btn dark v-on="on" outlined fab small color="#F9A825" @click="$refs.files.click()">
                                <v-icon color="grey" v-text="'mdi-paperclip'" />
                            </v-btn>
                        </template>
                        <span>Прикрепить файл</span>
                    </v-tooltip>
                </v-col>
            </v-row>
            <v-divider />
        </v-card-text>
        <v-card-actions>
            <v-spacer />
            <v-btn :loading="loading" color="error" small @click="send()">пожаловаться</v-btn>
        </v-card-actions>

        <files-show-dialog :files="dialogFiles" @close="dialogFiles = []" />
    </v-card>
</template>

<script>
import Complaint from "../../models/Complaint";
import FilesShowDialog from "./FilesShowDialog";
import Snackbar from "../../facades/Snackbar";
import axios from "axios";
import SearchOrder from "./SearchOrder";

export default {
    name: "ComplaintForm",
    components: { SearchOrder, FilesShowDialog },
    props: {
        worker: {
            required: true,
        },
    },
    data() {
        return {
            complaint: new Complaint(this.worker ? { recipients: this.worker.system_worker_id } : {}),
            loading: false,
            dialogFiles: [],

            workersLoading: false,
            workers: [],
        };
    },
    watch: {
        worker() {
            if (this.worker) {
                this.complaint = new Complaint({ recipient_id: this.worker.system_worker_id });
            }
        },
    },
    computed: {
        previewCols() {
            if (this.complaint.previews.length > 6) {
                return 4;
            } else if (this.complaint.previews.length > 2) {
                return 6;
            } else {
                return 12;
            }
        },
        previewHeight() {
            if (this.complaint.previews.length > 6) {
                return 78;
            } else if (this.complaint.previews.length > 4) {
                return 104;
            } else if (this.complaint.previews.length > 1) {
                return 156;
            } else {
                return 312;
            }
        },
    },
    methods: {
        close() {
            this.complaint = new Complaint();
            this.loading = false;
            this.$emit("close");
        },
        send() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.loading = true;
                    this.complaint
                        .store()
                        .then(response => {
                            this.loading = false;
                            this.$emit("created");
                            this.close();
                            Snackbar.info(response.data.message);
                        })
                        .catch(error => {
                            this.loading = false;
                            Snackbar.error(error.response.data.message);
                        });
                }
            });
        },
        getWorkers() {
            this.workersLoading = true;
            axios
                .get(process.env.MIX_APP_WORKER_URL + "complaint/workers")
                .then(response => {
                    this.workersLoading = false;
                    this.workers = response.data;
                })
                .catch(error => {
                    this.workersLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },
    },
    created() {
        this.worker
            ? (this.complaint = new Complaint({ recipient_id: this.worker.system_worker_id }))
            : this.getWorkers();
    },
};
</script>
