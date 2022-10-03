/** @format */

import ProfileComplaintPagination from "../../forms/ProfileComplaintPagination";
import ComplaintDialog from "../../components/Complaint/Complaint";
import ComplaintForm from "../../components/Complaint/ComplaintForm";
import ProfileWorker from "../../models/ProfileWorker";
import Snackbar from "../../facades/Snackbar";
import axios from "axios";

export default {
    name: "Profile",

    components: { ComplaintForm, ComplaintDialog },

    props: {
        user: {
            required: true,
        },
    },

    data() {
        return {
            noImage: "/storage/img/camera.png",

            complaints: new ProfileComplaintPagination({ path: "profile/complaints" }),
            complaintDialog: false,
            viewComplaint: null,
            newComplaintDialog: false,

            nicknameLoading: false,
            emailLoading: false,
            worker: new ProfileWorker(this.user),
            infoUpdateLoading: false,

            tab: 0,
            height: 0,
            heightDif: 129,
        };
    },

    watch: {
        "complaints.current_page": function () {
            this.complaints.getData;
        },
        "worker.change_password": function () {
            if (!this.worker.change_password) {
                this.worker.password = null;
            }
        },
        "worker.email": function () {
            if (this.worker.email && this.worker.email.length >= 3) {
                this.checkEmail();
            }
        },
        "worker.nickname": function () {
            if (this.worker.nickname && this.worker.nickname.length >= 9) {
                this.checkNickname();
            }
        },
    },

    computed: {
        isHome() {
            return this.$store.state.auth.user.system_worker_id === this.user.system_worker_id;
        },
        complaintsHeight() {
            return this.height - 102;
        },
        infoChanged() {
            let changed = false;
            Object.keys(this.user).forEach(key => {
                if (this.worker.hasOwnProperty(key) && this.user[key] !== this.worker[key]) {
                    changed = true;
                }
            });
            if (this.worker.change_password) {
                changed = true;
            }
            return changed;
        },
        phoneMask() {
            return this.$store.state.phoneMask;
        }
    },

    methods: {
        showComplaint(complaint) {
            this.viewComplaint = complaint;
            this.complaintDialog = true;
        },
        closeComplaint() {
            this.complaintDialog = false;
            this.viewComplaint = null;
        },

        refreshComplaints() {
            this.complaints.getData;
        },

        handleResize() {
            this.height = window.innerHeight - this.heightDif;
        },

        previewImage(event, key) {
            let reader = new FileReader();

            reader.onload = e => {
                this.worker[key] = e.target.result;
            };

            if (event) {
                reader.readAsDataURL(event.target.files[0]);
                this.worker.photo_file = event.target.files[0];
            } else {
                this.worker.photo = this.lazyImage;
            }

            return true;
        },
        generatePassword() {
            this.worker.password =
                Math.random().toString(32).substring(2, 6) + Math.random().toString(32).substring(2, 6);
        },

        checkEmail() {
            this.emailLoading = true;

            let data = {
                email: this.worker.email,
                table: "system_workers",
                col: "email",
                primary: "system_worker_id",
                primary_value: this.worker.system_worker_id,
            };

            axios
                .post(process.env.MIX_APP_WORKER_URL + "check/unique", data)
                .then(response => {
                    this.emailLoading = false;
                    if (!response.data.valid) {
                        this.errors.add({
                            field: "email",
                            msg: response.data.data.message,
                        });
                    }
                })
                .catch(error => {
                    this.emailLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        checkNickname() {
            this.nicknameLoading = true;

            let data = {
                nickname: this.worker.nickname,
                table: "system_workers",
                col: "nickname",
                primary: "system_worker_id",
                primary_value: this.worker.system_worker_id,
            };

            axios
                .post(process.env.MIX_APP_WORKER_URL + "check/unique", data)
                .then(response => {
                    this.nicknameLoading = false;
                    if (!response.data.valid) {
                        this.errors.add({
                            field: "nickname",
                            msg: response.data.data.message,
                        });
                    }
                })
                .catch(error => {
                    this.nicknameLoading = false;
                    Snackbar.error(error.response.data.message);
                });
        },

        updateInfo() {
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    this.infoUpdateLoading = true;
                    this.worker
                        .update({ profile: "info" })
                        .then(response => {
                            Snackbar.info(response.data.message);
                            this.infoUpdateLoading = false;
                            Object.keys(response.data.user).forEach(key => {
                                this.user[key] = response.data.user[key];
                            });
                            this.worker = new ProfileWorker(this.user);
                        })
                        .catch(error => {
                            Snackbar.error(error.response.data.message);
                            this.infoUpdateLoading = false;
                        });
                }
            });
        },

        cancelUpdateInfo() {
            this.worker = new ProfileWorker(this.user);
        },
    },

    created() {
        if (this.isHome) {
            this.complaints.getData;
        }
        this.handleResize();
        window.addEventListener("resize", this.handleResize);
    },
};
