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

    <div class="container mx-auto py-8 border border-gray-300 rounded-lg">
        <h3 class="text-3xl font-bold mb-4">Departments List</h3>

        <div class="flex items-center mb-4">
            <button
                class="btn btn-dark btn-sm rounded-lg px-4 py-2 mr-4"
                type="button"
                id="create_new"
            >
                Add New
            </button>
        </div>
        <div class="table-responsive">
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="department in departments"
                        :key="department.id"
                        :data-id="department.id"
                    >
                        <td class="px-4 py-2">{{ department.id }}</td>
                        <td class="px-4 py-2">{{ department.name }}</td>
                        <td class="px-4 py-2">{{ department.description }}</td>
                        <th class="px-4 py-2">
                            <div class="flex items-center">
                                <button
                                    id="btnGroupDrop1"
                                    type="button"
                                    class="btn btn-primary dropdown-toggle btn-sm rounded-lg px-3 py-1"
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
                        <th class="px-4 py-2" colspan="4">No data display.</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
