<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    layout: AuthenticatedLayout,
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="">
        <h3>Welcome to the Issue Tracker System</h3>
        <hr />
    </div>
</template>
