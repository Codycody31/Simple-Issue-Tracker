<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
        InputError,
        Modal,
    },
    layout: AuthenticatedLayout,
    props: {
        departments: {
            type: Array,
            required: true,
        },
    },
    setup() {
        // State
        const showDepartmentModal = ref(false);
        const ShowEditModal = ref(false);
        const showDeleteModal = ref(false);

        // Inputs.
        const departmentName = ref(null);
        const departmentDescription = ref(null);
        const departmentToDelete = ref(null);

        // Forms
        const departmentForm = useForm({
            id: "",
            name: "",
            description: "",
        });
        const deleteDepartmentForm = useForm({
            _method: "DELETE",
            id: "",
            name: "",
        });

        // Methods
        const createDepartment = () => {
            showDepartmentModal.value = true;
            nextTick(() => {
                departmentName.value.focus();
            });
        };
        const editDepartment = (department) => {
            ShowEditModal.value = true;
            departmentForm.id = department.id;
            departmentForm.name = department.name;
            departmentForm.description = department.description;

            nextTick(() => {
                departmentName.value.focus();
            });
        };
        const deleteDepartment = (department) => {
            showDeleteModal.value = true;
            deleteDepartmentForm.id = department.id;
            deleteDepartmentForm.name = department.name;
        };

        // Handlers
        const handleDepartmentSubmit = () => {
            departmentForm.post(route("departments.store"), {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                    departmentForm.reset();
                    console.log("Department created successfully!");
                },
                onError: () => {
                    console.log(
                        "Error submitting form! Please check your inputs."
                    );
                },
            });
        };
        const handleDepartmentUpdate = () => {
            departmentForm.patch(route("departments.update"), {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                    departmentForm.reset();
                },
                onError: () => {
                    console.log(
                        "Error submitting form! Please check your inputs."
                    );
                },
            });
        };
        const handleDepartmentDelete = () => {
            deleteDepartmentForm.delete(route("departments.destroy"), {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                },
                onError: () => {
                    console.log("Error deleting department!");
                },
            });
        };

        const closeModal = () => {
            showDepartmentModal.value = false;
            ShowEditModal.value = false;
            showDeleteModal.value = false;
            departmentForm.reset();
            deleteDepartmentForm.reset();
        };

        // Return.
        return {
            // State
            showDepartmentModal,
            ShowEditModal,
            showDeleteModal,
            // Inputs
            departmentName,
            departmentDescription,
            departmentToDelete,
            // Forms
            departmentForm,
            deleteDepartmentForm,
            // Methods
            createDepartment,
            editDepartment,
            deleteDepartment,
            // Handlers
            handleDepartmentSubmit,
            handleDepartmentUpdate,
            handleDepartmentDelete,
            closeModal,
        };
    },
};
</script>

<template>
    <Head title="Departments" />

    <div class="container mx-auto py-8 border border-gray-300 rounded-lg">
        <h3 class="text-3xl font-bold mb-4">Departments List</h3>

        <!-- Create New Button -->
        <div class="flex items-center mb-4">
            <button class="btn btn-dark btn-sm rounded-lg px-4 py-2 mr-4" type="button" @click="createDepartment">
                Add New
            </button>
        </div>

        <!-- Department List -->
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
                    <tr v-for="department in departments" :key="department.id" :data-id="department.id">
                        <td class="px-4 py-2">{{ department.id }}</td>
                        <td class="px-4 py-2">{{ department.name }}</td>
                        <td class="px-4 py-2">{{ department.description }}</td>
                        <th class="px-4 py-2">
                            <div class="flex items-center">
                                <button id="btnGroupDrop1" type="button"
                                    class="btn btn-primary dropdown-toggle btn-sm rounded-lg px-3 py-1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li>
                                        <a class="dropdown-item edit_data text-primary cursor-pointer"
                                            :data-id="department.id" :data-name="department.name"
                                            :data-description="department.description" @click="
                                                editDepartment(department)
                                                ">Edit</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item delete_data text-danger cursor-pointer"
                                            :data-id="department.id" :data-name="department.name" @click="
                                                deleteDepartment(department)
                                                ">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                    <tr class="text-center" v-if="!departments || !departments.length">
                        <th class="px-4 py-2" colspan="4">No data display.</th>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Create Department Modal -->
        <Modal :show="showDepartmentModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Create New Department
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Please fill in the form below. All fields are required.
                </p>
                <hr class="mt-4" />
                <form @submit.prevent="handleDepartmentSubmit">
                    <!-- Name -->
                    <div class="mt-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="name" v-model="departmentForm.name" ref="departmentName" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter name here" />
                        <InputError :message="departmentForm.errors.name" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <input id="description" v-model="departmentForm.description" ref="departmentDescription" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter description here" />
                        <InputError :message="departmentForm.errors.description" class="mt-2" />
                    </div>

                    <!-- Submit -->
                    <div class="mt-6 text-center flex justify-between">
                        <button
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-gray-600 hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring-gray-500 disabled:opacity-50"
                            @click="closeModal">
                            Cancel
                        </button>

                        <button
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-indigo-500 disabled:opacity-50"
                            :disabled="departmentForm.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="32" height="32" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m10 16.4l-4-4L7.4 11l2.6 2.6L16.6 7L18 8.4l-8 8Z" />
                            </svg>

                            <span v-if="departmentForm.processing">
                                Processing...
                            </span>
                            <span v-else>Create</span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit Department Modal -->
        <Modal :show="ShowEditModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Edit Department
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Please fill in the form below. All fields are required.
                </p>
                <hr class="mt-4" />
                <form @submit.prevent="handleDepartmentUpdate">
                    <!-- Id -->
                    <div class="mt-4">
                        <label for="id" class="block text-sm font-medium text-gray-700">Id</label>
                        <input id="id" v-model="departmentForm.id" ref="departmentId" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100"
                            placeholder="Enter id here" readonly />
                        <InputError :message="departmentForm.errors.id" class="mt-2" />
                    </div>

                    <!-- Name -->
                    <div class="mt-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="name" v-model="departmentForm.name" ref="departmentName" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter name here" />
                        <InputError :message="departmentForm.errors.name" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <input id="description" v-model="departmentForm.description" ref="departmentDescription" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter description here" />
                        <InputError :message="departmentForm.errors.description" class="mt-2" />
                    </div>

                    <!-- Submit -->
                    <div class="mt-6 text-center flex justify-between">
                        <button
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-gray-600 hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring-gray-500 disabled:opacity-50"
                            @click="closeModal">
                            Cancel
                        </button>

                        <button
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-indigo-500 disabled:opacity-50"
                            :disabled="departmentForm.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="32" height="32" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m10 16.4l-4-4L7.4 11l2.6 2.6L16.6 7L18 8.4l-8 8Z" />
                            </svg>

                            <span v-if="departmentForm.processing">
                                Processing...
                            </span>
                            <span v-else>Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Department Modal -->
        <Modal :show="showDeleteModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Delete Department
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Are you sure you want to delete this department?
                </p>
                <!-- Department Errors -->
                <InputError :message="deleteDepartmentForm.errors.department" class="mt-2" />
                <hr class="mt-4" />
                <form @submit.prevent="handleDepartmentDelete">
                    <!-- Name -->
                    <div class="mt-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="name" v-model="deleteDepartmentForm.name" ref="departmentName" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100"
                            placeholder="Enter name here" readonly />
                        <InputError :message="deleteDepartmentForm.errors.name" class="mt-2" />
                    </div>

                    <!-- Id -->
                    <div class="mt-4">
                        <label for="id" class="block text-sm font-medium text-gray-700">Id</label>
                        <input id="id" v-model="deleteDepartmentForm.id" ref="departmentId" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100"
                            placeholder="Enter id here" readonly />
                        <InputError :message="deleteDepartmentForm.errors.id" class="mt-2" />
                    </div>

                    <!-- Submit -->
                    <div class="mt-6 text-center flex justify-between">
                        <button
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-gray-600 hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring-gray-500 disabled:opacity-50"
                            @click="closeModal">
                            Cancel
                        </button>

                        <button
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-red-600 hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring-red-500 disabled:opacity-50"
                            :disabled="deleteDepartmentForm.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="32" height="32" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M7 21q-.825 0-1.413-.588T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.588 1.413T17 21H7Zm2-4h2V8H9v9Zm4 0h2V8h-2v9Z" />
                            </svg>

                            <span v-if="deleteDepartmentForm.processing">
                                Processing...
                            </span>
                            <span v-else>Delete </span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>
