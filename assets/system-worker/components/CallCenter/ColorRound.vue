<!-- @format -->

<template>
    <div
        class="elevation-3 rounded-circle"
        :style="{ height: sizeValue + 'px', width: sizeValue + 'px', 'background-color': colorValue }"
    ></div>
</template>

<script>
export default {
    props: ["color", "size"],
    data() {
        return {
            defColor: "white",
            defSize: 15,
        };
    },
    computed: {
        colorValue: function () {
            return this.color || this.defColor;
        },
        sizeValue: function () {
            return this.size || this.defSize;
        },
    },
};
</script>
