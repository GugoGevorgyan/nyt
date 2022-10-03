<!-- @format -->

<template>
    <v-dialog :persistent="persistent" v-model="dialog" :width="maxWidth">
        <div style="position: relative">
            <v-img
                style="background-color: #0a0302"
                contain
                :max-width="maxWidth"
                :max-height="maxHeight"
                v-if="files.length"
                :src="files[active]"
            />
            <div
                style="
                    position: absolute;
                    top: 5px;
                    right: 5px;
                    z-index: 9;
                    color: white;
                    display: flex;
                    align-items: center;
                "
            >
                <small class="mx-2" v-if="files.length > 1">{{ active + 1 }} из {{ files.length }}</small>
                <v-btn @click="$emit('close')" icon color="white" style="background: rgba(0, 0, 0, 0.5)">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </div>
            <template v-if="files.length > 1">
                <v-btn
                    @click="slideLeft()"
                    v-hotkey="keymap"
                    icon
                    color="white"
                    style="
                        position: absolute;
                        top: 50%;
                        left: 5px;
                        z-index: 9;
                        background: rgba(0, 0, 0, 0.5);
                        transform: translate(0%, -50%);
                    "
                >
                    <v-icon v-text="'mdi-chevron-left'" />
                </v-btn>
                <v-btn
                    @click="slideRight()"
                    v-hotkey="keymap"
                    icon
                    color="white"
                    style="
                        position: absolute;
                        top: 50%;
                        right: 5px;
                        z-index: 9;
                        background: rgba(0, 0, 0, 0.5);
                        transform: translate(0%, -50%);
                    "
                >
                    <v-icon v-text="'mdi-chevron-right'" />
                </v-btn>
            </template>
        </div>
    </v-dialog>
</template>

<script>
export default {
    props: {
        files: {
            required: true,
            type: Array,
        },
        maxWidth: {
            required: false,
            default: "1200",
            type: String,
        },
        maxHeight: {
            required: false,
            default: "600",
            type: String,
        },
        persistent: {
            required: false,
            default: true,
            type: Boolean,
        },
    },

    data() {
        return {
            active: 0,
            dialog: false,
        };
    },

    watch: {
        files() {
            this.dialog = !!this.files.length;
            this.active = 0;
        },
    },

    computed: {
        keymap() {
            return {
                arrowright: this.slideRight,
                arrowleft: this.slideLeft,
            };
        },
    },

    methods: {
        slideLeft() {
            this.active = this.active === 0 ? this.files.length - 1 : this.active - 1;
        },

        slideRight() {
            this.active = this.active === this.files.length - 1 ? 0 : this.active + 1;
        },
    },
};
</script>