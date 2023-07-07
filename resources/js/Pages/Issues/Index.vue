<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
    },
    layout: AuthenticatedLayout,
    props: {
        issues: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <Head title="Issues" />

    <div class="w-100 d-flex justify-content-between">
        <h3>Issue List</h3>
        <button
            class="btn btn-dark btn-sm py-1 rounded-0 col-2"
            type="button"
            id="create_new"
        >
            Add New
        </button>
    </div>
    <hr />
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        <div class="col" v-for="issue in issues" :key="issue.id">
            <div
                class="w-100 bg-dark text-light bg-gradient opcaity-70 py-3 px-2"
            >
                <h5
                    class="truncate-1 border-bottom border-light"
                    :title="issue.title"
                >
                    <b>
                        {{ issue.title }}
                    </b>
                </h5>
                <small class="truncate-3">
                    <!-- Limit length -->
                    {{
                        issue.description.length > 100
                            ? issue.description.substring(0, 100) + "..."
                            : issue.description
                    }}
                </small>
                <div v-if="issue.user" class="mt-2">
                    <small
                        >By:
                        {{ issue.user.fullname }}
                    </small>
                    <br />
                    <small
                        >To:
                        {{ issue.department.name }}
                    </small>
                </div>
                <div
                    class="w-100 d-flex justify-content-between align-items-middle mt-3"
                >
                    <span
                        class="w-auto badge bg-success"
                        v-if="issue.status == 0"
                        >Open</span
                    >
                    <span class="w-auto badge bg-danger" v-else>Closed</span>
                    <Link
                        class="btn btn-sm btn-primary col-auto py-0 rounded-0"
                        :href="route('issues.show', issue.id)"
                        >View</Link
                    >
                </div>
            </div>
        </div>
    </div>
    <div class="w-100 text-center" v-if="issues.length == 0">
        <div class="alert alert-warning">No issue listed yet.</div>
    </div>
</template>
