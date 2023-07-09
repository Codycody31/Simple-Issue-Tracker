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

    <div class="container">
        <!-- Welcome -->
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Welcome to the Issue Tracker System</h2>
                <hr />
            </div>
        </div>
    </div>
</template>
