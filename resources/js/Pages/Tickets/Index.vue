<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import { Head, Link } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
        Modal,
        InputError,
    },
    layout: AuthenticatedLayout,
    props: {
        tickets: {
            type: Array,
            required: true,
        },
    },
    methods: {
        /**
         * Format date to locale string
         * @param {*} date Date to be formatted
         * @returns Formatted date
         */
        formatDate(date) {
            if (!date) {
                return "";
            }
            try {
                return new Date(date).toLocaleString();
            } catch (error) {
                console.error(error);
                return "";
            }
        },
        /**
         * Sort issues by date
         * @param {*} issues Issues to be sorted
         * @returns Sorted issues
         */
        sortIssuesByDate(issues) {
            if (issues && Array.isArray(issues)) {
                return issues.sort((a, b) => {
                    return new Date(b.created_at) - new Date(a.created_at);
                });
            } else {
                throw new Error("Invalid issues array");
            }
        },
    },
};
</script>
<template>
    <Head title="Tickets" />

    <!-- Page title -->
    <div class="w-100">
        <h3>Issue Ticket List</h3>
        <small>Below are the list of issues posted into your department</small>
    </div>
    <hr />

    <!-- Issue list -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        <div
            class="col"
            v-for="ticket in sortIssuesByDate(tickets)"
            :key="ticket.id"
            :data-id="ticket.id"
        >
            <div
                class="w-100 bg-dark text-light bg-gradient opcaity-70 py-3 px-2 rounded-lg shadow-sm"
            >
                <h5
                    class="truncate-1 border-bottom border-light"
                    :title="ticket.title"
                >
                    <b>{{ ticket.title }}</b>
                </h5>
                <small class="truncate-3">
                    <!-- Limit length -->
                    {{
                        ticket.description.length > 100
                            ? ticket.description.substring(0, 100) + "..."
                            : ticket.description
                    }}
                </small>
                <div
                    class="w-100 d-flex justify-content-between align-items-middle mt-3"
                >
                    <span
                        class="w-auto badge bg-success"
                        v-if="ticket.status == 0"
                        >Open</span
                    >
                    <span class="w-auto badge bg-danger" v-else>Closed</span>

                    <!-- Timestamp -->
                    <span class="w-auto badge bg-secondary">
                        {{ formatDate(ticket.created_at) }}
                    </span>
                    <Link
                        class="btn btn-sm btn-primary col-auto py-0 rounded-lg"
                        :href="route('issues.show', ticket.id)"
                        >View</Link
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- No issue listed -->
    <div class="w-100 text-center" v-if="!tickets || tickets.length == 0">
        <div class="alert alert-warning">No issue listed yet.</div>
    </div>
</template>
