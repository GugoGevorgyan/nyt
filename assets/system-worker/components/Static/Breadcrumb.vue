<!-- @format -->

<template>
    <v-breadcrumbs :items="items" class="mb-2">
        <template v-slot:divider>
            <v-icon>forward</v-icon>
        </template>
    </v-breadcrumbs>
</template>

<script>
export default {
    name: "Breadcrumb",

    data: () => ({
        items: [
            {
                text: "Dashboard",
                disabled: false,
                href: "breadcrumbs_dashboard",
            },
            {
                text: "Link 1",
                disabled: false,
                href: "breadcrumbs_link_1",
            },
            {
                text: "Link 2",
                disabled: true,
                href: "breadcrumbs_link_2",
            },
        ],
    }),
};
</script>

<style scoped></style>
