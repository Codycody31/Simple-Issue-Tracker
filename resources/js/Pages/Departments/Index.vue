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
        departments: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <Head title="Departments" />

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Department List</h3>
            <div class="card-tools align-middle">
                <button
                    class="btn btn-dark btn-sm py-1 rounded-0"
                    type="button"
                    id="create_new"
                >
                    Add New
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center p-0">#</th>
                        <th class="text-center p-0">Name</th>
                        <th class="text-center p-0">Description</th>
                        <th class="text-center p-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class="text-center"
                        v-for="department in departments"
                        :key="department.id"
                    >
                        <td class="text-center p-0">{{ department.id }}</td>
                        <td class="py-0 px-1">{{ department.name }}</td>
                        <td class="py-0 px-1">{{ department.description }}</td>
                        <th class="text-center py-0 px-1">
                            <div class="btn-group" role="group">
                                <button
                                    id="btnGroupDrop1"
                                    type="button"
                                    class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    Action
                                </button>
                                <ul
                                    class="dropdown-menu"
                                    aria-labelledby="btnGroupDrop1"
                                >
                                    <li>
                                        <a
                                            class="dropdown-item edit_data"
                                            :data-id="department.id"
                                            href="javascript:void(0)"
                                            >Edit</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item delete_data"
                                            :data-id="department.id"
                                            :data-name="department.name"
                                            href="javascript:void(0)"
                                            >Delete</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                    <tr
                        class="text-center"
                        v-if="!departments || !departments.length"
                    >
                        <th class="text-center p-0" colspan="4">
                            No data display.
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
