<!-- @format -->

<template>
    <v-container fluid grid-list-lg>
        <!--Candidate form-->
        <v-card tile elevation="4">
            <v-card-title class="grey lighten-5">Обновить кандидата</v-card-title>
            <v-divider class="mt-0" />
            <v-card-text class="pt-0">
                <candidate-form
                    :driver-candidate="driverCandidate"
                    :driver-info="driverInfo"
                    :learn-statuses="learnStatuses"
                    :tutors="tutors"
                    :license-types="licenseTypes"
                    :update="true"
                    :disabled-license="false"
                    :disabled="false"
                    :url="url"
                    btn-title="Обновить"
                    :loading="loading"
                    @submit="update()"
                />
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import Candidate from "../../models/Candidate";
import Snackbar from "../../facades/Snackbar";
import DriverInfo from "../../models/DriverInfo";
import MultiModel from "../../base/MultiModel";
import CandidateForm from "./form/CandidateForm";

export default {
    name: "DriverCandidatesEdit",

    components: { CandidateForm },

    props: {
        candidate: {
            required: true,
            type: Object,
        },
        tutors: {
            required: true,
            Array: true,
        },
        licenseTypes: {
            required: true,
        },
        learnStatuses: {
            required: true,
            Array: true,
        },
    },

    data: function () {
        return {
            driverCandidate: new Candidate({ ...this.candidate }),
            driverInfo: new DriverInfo({ ...this.candidate.info }),
            form: Object,
            loading: false,
        };
    },

    computed: {
        url() {
            return this.$store.state.initUrl;
        },
    },

    methods: {
        setDriverLicenseTypeIds() {
            this.candidate.info.license_types.forEach(item => {
                this.driverInfo.license_type_ids.push(item.driver_license_type_id);
            });
        },

        update() {
            this.loading = true;
            this.form = new MultiModel([this.driverCandidate, this.driverInfo], true);
            this.form
                .send(this.url + `driver-candidates/update/` + this.candidate.driver_candidate_id, "put")
                .then(response => {
                    this.loading = false;
                    Snackbar.info(response.data.message);
                    window.location = this.url + "driver-candidates";
                })
                .catch(error => {
                    this.loading = false;
                    Snackbar.error(error.response.data.message);
                    DriverInfo.errors(error.response).forEach(error => this.errors.add(error));
                    Candidate.errors(error.response).forEach(error => this.errors.add(error));
                });
        },
    },

    created() {
        this.driverInfo.birthday = new Date(this.driverInfo.birthday).toISOString().slice(0, 10);
        this.setDriverLicenseTypeIds();
    },
};
</script>
