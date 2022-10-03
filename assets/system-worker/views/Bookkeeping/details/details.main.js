/** @format */

import { TRANSACTION } from "../../../plugins/config";
import waybill from "./templates/waybill";
import order from "./templates/order";
import crash from "./templates/crash";

/** @format */

export default {
    name: "BookkeepingDetails",

    components: {
        'template-waybill': waybill,
        'template-order': order,
        'template-crash': crash,
    },

    props: {
        selectedId: {
            required: true,
        },
    },

    data() {
        return {
            loading: true,
            details: {},
            TRANSACTION: TRANSACTION,
        };
    },

    created() {
        this.$http.get(`all/details/${this.selectedId}`).then(result => {
            this.details = result.data;
            this.loading = false;
        });
    },
};
