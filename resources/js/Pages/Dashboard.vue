<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    layout: AuthenticatedLayout,
    props: {
        departments: Array,
        issues: Array,
        users: Array,
    },
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="container mt-4">
        <!-- Welcome -->
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Welcome to the Issue Tracker System</h2>
                <hr />
            </div>
        </div>

        <!-- Issue Tracker Content -->
        <div class="row">
            <div class="col-md-6">
                <!-- Department Section -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Departments</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item" v-for="department in departments" :key="department.id">
                                {{ department.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Issues Section -->
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4>Issues</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item" v-for="issue in issues" :key="issue.id">
                                {{ issue.title }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
