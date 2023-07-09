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

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Users List</h3>
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
                <colgroup>
                    <col width="5%" />
                    <col width="25%" />
                    <col width="20%" />
                    <col width="15%" />
                    <col width="20%" />
                    <col width="15%" />
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center p-0">#</th>
                        <th class="text-center p-0">Full Name</th>
                        <th class="text-center p-0">Email</th>
                        <th class="text-center p-0">Contact</th>
                        <th class="text-center p-0">Type</th>
                        <th class="text-center p-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id" :data-id="user.id">
                        <td class="text-center p-0">{{ user.id }}</td>
                        <td class="py-0 px-1">{{ user.fullname }}</td>
                        <td class="py-0 px-1">{{ user.email }}</td>
                        <td class="py-0 px-1">{{ user.contact }}</td>
                        <td class="py-0 px-1">
                            {{ user.type == 1 ? "Administrator" : "Employee" }}
                        </td>
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
                                            :data-id="user.id"
                                            href="javascript:void(0)"
                                            >Edit</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item delete_data"
                                            :data-id="user.id"
                                            :data-name="user.fullname"
                                            href="javascript:void(0)"
                                            >Delete</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                    <tr class="text-center" v-if="!users || users.length === 0">
                        <th class="text-center p-0" colspan="6">
                            No data display.
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
