<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";
import Modal from "@/Components/Modal.vue";
import { Input } from "postcss";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
        InputError,
        Modal,
        Input
    },
    layout: AuthenticatedLayout,
    props: {
        users: {
            type: Array,
            required: true,
        },
        departments: {
            type: Array,
            required: true,
        },
    },
    setup() {
        // State
        const creatingUser = ref(false);
        const editingUser = ref(false);
        const deletingUser = ref(false);

        // Inputs
        const userFullNameInput = ref(null);
        const userEmailInput = ref(null);
        const userContactInput = ref(null);
        const userUsernameInput = ref(null);
        const userDepartmentInput = ref(null);
        const userDesignationInput = ref(null);
        const userTypeInput = ref(null);
        const userToDelete = ref(null);

        // Forms
        const userForm = useForm({
            id: "",
            fullname: "",
            email: "",
            contact: "",
            username: "",
            department: "",
            designation: "",
            type: 2,
        });
        const deleteUserForm = useForm({
            _method: "DELETE",
            id: "",
            name: "",
        });

        // Methods
        const createUser = () => {
            creatingUser.value = true;

            nextTick(() => {
                userFullNameInput.value.focus();
            });
        };
        const editUser = (user) => {
            editingUser.value = true;

            userForm.id = user.id;
            userForm.fullname = user.fullname;
            userForm.email = user.email;
            userForm.contact = user.contact;
            userForm.username = user.username;
            userForm.department = user.department_id;
            userForm.designation = user.designation;
            userForm.type = user.type;

            nextTick(() => {
                userFullNameInput.value.focus();
            });
        };
        const deleteUser = (user) => {
            userToDelete.value = user;

            deleteUserForm.id = user.id;
            deleteUserForm.name = user.fullname;

            deletingUser.value = true;
        };

        // Handlers
        const handleSubmit = () => {
            userForm.post(route("users.store"), {
                preserveScroll: true,
                onSuccess: () => {
                    // Close modal
                    closeModal();

                    // Reset form
                    userForm.reset();

                    // Show success messag
                },
            });
        };
        const handleEdit = () => {
            userForm.patch(route("users.update"), {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                    userForm.reset();
                },
            });
        };
        const handleDelete = () => {
            deleteUserForm.delete(route("users.destroy"), {
                preserveScroll: true,
                onSuccess: () => {
                    // Close modal
                    deletingUser.value = false;

                    // Show success message
                },
            });
        };

        // Close modal
        const closeModal = () => {
            creatingUser.value = false;
            editingUser.value = false;
            deletingUser.value = false;

            userForm.reset();
            deleteUserForm.reset();
        };

        return {
            // State
            creatingUser,
            editingUser,
            deletingUser,
            // Inputs
            userFullNameInput,
            userEmailInput,
            userContactInput,
            userUsernameInput,
            userDepartmentInput,
            userDesignationInput,
            userTypeInput,
            userToDelete,
            // Forms
            userForm,
            deleteUserForm,
            // Methods
            createUser,
            editUser,
            deleteUser,
            // Handlers
            handleSubmit,
            handleEdit,
            handleDelete,
            // Close modal
            closeModal,
        };
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
    },
};
</script>

<template>
    <Head title="Users" />

    <div class="container mx-auto py-8 border border-gray-300 rounded-lg">
        <!-- Header -->
        <h3 class="text-3xl font-bold mb-4">Users List</h3>

        <!-- Create new -->
        <div class="flex items-center mb-4">
            <button class="btn btn-dark btn-sm rounded-lg px-4 py-2 mr-4" type="button" @click="createUser">
                Add New
            </button>
        </div>

        <!-- Table -->
        <div class="table-responsive">
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
                                <button id="btnGroupDrop1" type="button"
                                    class="btn btn-primary dropdown-toggle btn-sm rounded-lg px-3 py-1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li>
                                        <a class="dropdown-item edit_data text-primary cursor-pointer" :data-id="user.id"
                                            :data-name="user.fullname" @click="editUser(user)">
                                            Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item delete_data text-danger cursor-pointer" :data-id="user.id"
                                            :data-name="user.fullname" @click="deleteUser(user)">
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

        <!-- Create user modal -->
        <Modal :show="creatingUser" @close="closeModal">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Create New User
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Please fill in the form below. All fields are required.
                </p>
                <hr class="mt-4" />
                <form @submit.prevent="handleSubmit">
                    <!-- Full Name -->
                    <div class="mt-4">
                        <label for="fullname" class="block text-sm font-medium text-gray-700">
                            Full Name
                        </label>
                        <div class="mt-1">
                            <input type="text" name="fullname" id="fullname"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.fullname" ref="userFullNameInput" placeholder="Enter full name" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.fullname">
                            {{ userForm.errors.fullname }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.email" ref="userEmailInput" placeholder="Enter email address" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.email">
                            {{ userForm.errors.email }}
                        </p>
                    </div>

                    <!-- Contact -->
                    <div class="mt-4">
                        <label for="contact" class="block text-sm font-medium text-gray-700">
                            Contact
                        </label>
                        <div class="mt-1">
                            <input type="text" name="contact" id="contact"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.contact" ref="userContactInput" placeholder="Enter contact number" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.contact">
                            {{ userForm.errors.contact }}
                        </p>
                    </div>

                    <!-- Username -->
                    <div class="mt-4">
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Username
                        </label>
                        <div class="mt-1">
                            <input type="text" name="username" id="username"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.username" ref="userUsernameInput" placeholder="Enter username" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.username">
                            {{ userForm.errors.username }}
                        </p>
                    </div>

                    <!-- Department -->
                    <div class="mt-4">
                        <label for="department" class="block text-sm font-medium text-gray-700">
                            Department
                        </label>
                        <div class="mt-1">
                            <select id="department" v-model="userForm.department" ref="issueDepartmentInput"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="" disabled>
                                    Please select here
                                </option>
                                <option v-for="department in departments" :key="department.id" :value="department.id">
                                    {{ department.name }}
                                </option>
                            </select>
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.department">
                            {{ userForm.errors.department }}
                        </p>
                    </div>

                    <!-- Designation -->
                    <div class="mt-4">
                        <label for="designation" class="block text-sm font-medium text-gray-700">
                            Designation
                        </label>
                        <div class="mt-1">
                            <input type="text" name="designation" id="designation"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.designation" ref="userDesignationInput" placeholder="Enter designation" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.designation">
                            {{ userForm.errors.designation }}
                        </p>
                    </div>

                    <!-- Type -->
                    <div class="mt-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">
                            Type
                        </label>
                        <div class="mt-1">
                            <select name="type" id="type"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.type" ref="userTypeInput">
                                <option value="" disabled>
                                    Please select here
                                </option>
                                <option value="1">Administrator</option>
                                <option value="2">Employee</option>
                            </select>
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.type">
                            {{ userForm.errors.type }}
                        </p>
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
                            :disabled="userForm.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="32" height="32" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M18 14v-3h-3V9h3V6h2v3h3v2h-3v3h-2Zm-9-2q-1.65 0-2.825-1.175T5 8q0-1.65 1.175-2.825T9 4q1.65 0 2.825 1.175T13 8q0 1.65-1.175 2.825T9 12Zm-8 8v-2.8q0-.85.438-1.563T2.6 14.55q1.55-.775 3.15-1.163T9 13q1.65 0 3.25.388t3.15 1.162q.725.375 1.163 1.088T17 17.2V20H1Z" />
                            </svg>

                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit user modal -->
        <Modal :show="editingUser" @close="closeModal">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Edit User
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Please fill in the form below. All fields are required.
                </p>
                <InputError :message="userForm.errors.user" class="mt-2" />
                <hr class="mt-4" />
                <form @submit.prevent="handleEdit">
                    <!-- Id -->
                    <div class="mt-4">
                        <label for="id" class="block text-sm font-medium text-gray-700">
                            Id
                        </label>
                        <div class="mt-1">
                            <input type="text" name="id" id="id"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-100"
                                v-model="userForm.id" ref="userIdInput" placeholder="Enter id" readonly />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.id">
                            {{ userForm.errors.id }}
                        </p>
                    </div>

                    <!-- Full Name -->
                    <div class="mt-4">
                        <label for="fullname" class="block text-sm font-medium text-gray-700">
                            Full Name
                        </label>
                        <div class="mt-1">
                            <input type="text" name="fullname" id="fullname"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.fullname" ref="userFullNameInput" placeholder="Enter full name" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.fullname">
                            {{ userForm.errors.fullname }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.email" ref="userEmailInput" placeholder="Enter email address" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.email">
                            {{ userForm.errors.email }}
                        </p>
                    </div>

                    <!-- Contact -->
                    <div class="mt-4">
                        <label for="contact" class="block text-sm font-medium text-gray-700">
                            Contact
                        </label>
                        <div class="mt-1">
                            <input type="text" name="contact" id="contact"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.contact" ref="userContactInput" placeholder="Enter contact number" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.contact">
                            {{ userForm.errors.contact }}
                        </p>
                    </div>

                    <!-- Username -->
                    <div class="mt-4">
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Username
                        </label>
                        <div class="mt-1">
                            <input type="text" name="username" id="username"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.username" ref="userUsernameInput" placeholder="Enter username" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.username">
                            {{ userForm.errors.username }}
                        </p>
                    </div>

                    <!-- Department -->
                    <div class="mt-4">
                        <label for="department" class="block text-sm font-medium text-gray-700">
                            Department
                        </label>
                        <div class="mt-1">
                            <select id="department" v-model="userForm.department" ref="issueDepartmentInput"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="" disabled>
                                    Please select here
                                </option>
                                <option v-for="department in departments" :key="department.id" :value="department.id">
                                    {{ department.name }}
                                </option>
                            </select>
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.department">
                            {{ userForm.errors.department }}
                        </p>
                    </div>

                    <!-- Designation -->
                    <div class="mt-4">
                        <label for="designation" class="block text-sm font-medium text-gray-700">
                            Designation
                        </label>
                        <div class="mt-1">
                            <input type="text" name="designation" id="designation"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.designation" ref="userDesignationInput" placeholder="Enter designation" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.designation">
                            {{ userForm.errors.designation }}
                        </p>
                    </div>

                    <!-- Type -->
                    <div class="mt-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">
                            Type
                        </label>
                        <div class="mt-1">
                            <select name="type" id="type"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                v-model="userForm.type" ref="userTypeInput">
                                <option value="" disabled>
                                    Please select here
                                </option>
                                <option value="1">Administrator</option>
                                <option value="2">Employee</option>
                            </select>
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="userForm.errors.type">
                            {{ userForm.errors.type }}
                        </p>
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
                            :disabled="userForm.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="32" height="32" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m10 16.4l-4-4L7.4 11l2.6 2.6L16.6 7L18 8.4l-8 8Z" />
                            </svg>

                            <span v-if="userForm.processing">
                                Processing...
                            </span>
                            <span v-else>Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete user modal -->
        <Modal :show="deletingUser" @close="closeModal">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Delete User Confirmation
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Are you sure you want to delete this user?
                </p>
                <!-- User Errors -->
                <InputError :message="deleteUserForm.errors.user" class="mt-2" />
                <hr class="mt-4" />
                <form @submit.prevent="handleDelete">
                    <!-- Name -->
                    <div class="mt-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="name" v-model="deleteUserForm.name" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100"
                            placeholder="Enter name here" readonly />
                        <InputError :message="deleteUserForm.errors.name" class="mt-2" />
                    </div>

                    <!-- Id -->
                    <div class="mt-4">
                        <label for="id" class="block text-sm font-medium text-gray-700">Id</label>
                        <input id="id" v-model="deleteUserForm.id" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100"
                            placeholder="Enter id here" readonly />
                        <InputError :message="deleteUserForm.errors.id" class="mt-2" />
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
                            :disabled="deleteUserForm.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="32" height="32" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M19 6.5l-1.5-1.5-5.5 5.5-5.5-5.5L5 6.5l5.5 5.5-5.5 5.5L6.5 19l5.5-5.5 5.5 5.5L19 17.5l-5.5-5.5Z" />
                            </svg>

                            <span v-if="deleteUserForm.processing">
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
