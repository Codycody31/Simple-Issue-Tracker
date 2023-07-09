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
        users: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <Head title="Users" />

    <div class="container mx-auto py-8 border border-gray-300 rounded-lg">
        <h3 class="text-3xl font-bold mb-4">Users List</h3>

        <div class="flex items-center mb-4">
            <button
                class="btn btn-dark btn-sm rounded-lg px-4 py-2 mr-4"
                type="button"
                id="create_new"
            >
                Add New
            </button>
        </div>

        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Full Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Contact</th>
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id" :data-id="user.id">
                    <td class="px-4 py-2">{{ user.id }}</td>
                    <td class="px-4 py-2">{{ user.fullname }}</td>
                    <td class="px-4 py-2">{{ user.email }}</td>
                    <td class="px-4 py-2">{{ user.contact }}</td>
                    <td class="px-4 py-2">
                        {{ user.type == 1 ? "Administrator" : "Employee" }}
                    </td>
                    <td class="px-4 py-2">
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
                                        :data-id="user.id"
                                        href="javascript:void(0)"
                                    >
                                        Edit
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item delete_data"
                                        :data-id="user.id"
                                        :data-name="user.fullname"
                                        href="javascript:void(0)"
                                    >
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr class="text-center" v-if="!users || users.length === 0">
                    <td class="px-4 py-2" colspan="6">No data display.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
