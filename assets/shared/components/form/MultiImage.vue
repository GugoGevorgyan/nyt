<!-- @format -->

<template>
    <div>
        <v-card>
            <div style="max-height: 250px; margin-bottom: 15px; overflow-y: auto">
                <v-row class="no-gutters" v-if="images.length">
                    <v-col v-for="(image, i) in images" :key="i" cols="4" style="position: relative">
                        <v-btn
                            @click="$emit('remove', { key: i })"
                            x-small
                            icon
                            color="error"
                            style="position: absolute; top: 5px; right: 5px; z-index: 1; background: rgba(0, 0, 0, 0.5)"
                        >
                            <v-icon small v-text="'mdi-close'" />
                        </v-btn>

                        <v-img
                            style="cursor: pointer"
                            height="78"
                            cover
                            :src="typeof image === 'string' ? image : fileToPreview(image)"
                        />
                    </v-col>
                </v-row>

                <div v-else style="height: 100%; display: flex; justify-content: center; align-items: center">
                    <slot>
                        <v-img :src="lazyImage" max-height="250px" />
                    </slot>
                </div>
            </div>

            <v-card-actions class="pa-0 ma-0 mb-0">
                <v-file-input
                    multiple
                    :disabled="disabled"

                    :data-vv-as="label"
                    :prepend-icon="null"
                    append-icon="mdi-camera"
                    dense
                    color="yellow darken-3"
                    :label="label"
                    :name="inputName"
                    @change="$emit('change', $event)"
                />
            </v-card-actions>

            <span
                class="color red lighten-3"
                v-if="errorFirstMessage"
                v-text="errorFirstMessage"
            />
        </v-card>
    </div>
</template>

<script>
export default {
    name: 'MultiImage',

    inject: ['$validator'],

    props: {
        images: {
            required: true,
            type: Array,
        },
        disabled: {
            default: false,
            type: Boolean,
        },
        errorFirstMessage: {
            default: '',
            type: String
        },
        label: {
            default: null,
        },
        inputName: {
            required: true,
            type: String
        },
        lazyImage: {
            default: '/storage/img/noimage.png',
            type: String,
        },
        inputRules: {
            type: Object
        }
    },

    methods: {
        fileToPreview(file){
            return URL.createObjectURL(file);
        }
    }
};
</script>

<style scoped>
.v-text-field__details {
    display: none !important;
}
</style>
