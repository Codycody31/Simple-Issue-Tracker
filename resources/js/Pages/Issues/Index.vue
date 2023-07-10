<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";
import { MdEditor } from "md-editor-v3";
import "md-editor-v3/lib/style.css";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
        Modal,
        InputError,
        MdEditor,
    },
    layout: AuthenticatedLayout,
    props: {
        issues: {
            type: Array,
            required: true,
        },
        departments: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        // Show/hide issue modal
        const creatingIssue = ref(false);

        // Inputs
        const issueTitleInput = ref(null);
        const issueDescriptionInput = ref(null);
        const issueDepartmentInput = ref(null);

        // Issue form
        const issueForm = useForm({
            title: "",
            description: "",
            department_id: "",
        });

        const createIssue = () => {
            creatingIssue.value = true;

            nextTick(() => issueTitleInput.value.focus());
        };

        const handleIssueSubmit = () => {
            issueForm.post(route("issues.store"), {
                preserveScroll: true,
                onSuccess: () => {
                    // Close modal
                    closeModal();

                    // Reset form
                    issueForm.reset();

                    // Log
                    console.log("Issue created successfully!");
                },
                onError: () => {
                    // Log
                    console.log(
                        "Error submitting issue form. Please try again."
                    );
                },
                onFinish: () => {
                    // Log
                    console.log("Finished submitting issue.");
                },
            });
        };

        const closeModal = () => {
            creatingIssue.value = false;

            issueForm.reset();
        };

        return {
            creatingIssue,
            issueForm,
            createIssue,
            closeModal,
            handleIssueSubmit,
            issueTitleInput,
            issueDescriptionInput,
            issueDepartmentInput,
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
    <Head title="Issues" />

    <!-- Issue list header -->
    <div class="w-100 d-flex justify-content-between">
        <h3>Issue List</h3>
        <button
            class="btn btn-dark btn-sm py-1 rounded col-2"
            type="button"
            @click="createIssue"
        >
            Add New
        </button>
    </div>
    <hr />

    <!-- Issue list -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        <div
            class="col"
            v-for="issue in sortIssuesByDate(issues)"
            :key="issue.id"
        >
            <div
                class="w-100 bg-dark text-light bg-gradient opcaity-70 py-3 px-2 rounded-lg shadow-sm"
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

                    <!-- Timestamp -->
                    <span class="w-auto badge bg-secondary">
                        {{ formatDate(issue.created_at) }}
                    </span>

                    <Link
                        class="btn btn-sm btn-primary col-auto py-0 rounded-lg"
                        :href="route('issues.show', issue.id)"
                        >View</Link
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- No issue listed -->
    <div class="w-100 text-center" v-if="issues.length == 0">
        <div class="alert alert-warning">No issue listed yet.</div>
    </div>

    <!-- Create issue modal -->
    <Modal :show="creatingIssue" @close="closeModal">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900">Create New Issue</h2>
            <p class="mt-2 text-sm text-gray-600">
                Please fill in the form below. All fields are required.
            </p>
            <hr class="mt-4" />
            <form @submit.prevent="handleIssueSubmit">
                <!-- Title -->
                <div class="mt-4">
                    <label
                        for="title"
                        class="block text-sm font-medium text-gray-700"
                        >Title</label
                    >
                    <input
                        id="title"
                        v-model="issueForm.title"
                        ref="issueTitleInput"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Enter title here"
                    />
                    <InputError
                        :message="issueForm.errors.title"
                        class="mt-2"
                    />
                </div>

                <!-- Department -->
                <div class="mt-4">
                    <label
                        for="department_id"
                        class="block text-sm font-medium text-gray-700"
                        >Assign to Department</label
                    >
                    <select
                        id="department_id"
                        v-model="issueForm.department_id"
                        ref="issueDepartmentInput"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                        <option value="" disabled>Please select here</option>
                        <option
                            v-for="department in departments"
                            :key="department.id"
                            :value="department.id"
                        >
                            {{ department.name }}
                        </option>
                    </select>
                    <InputError
                        :message="issueForm.errors.department_id"
                        class="mt-2"
                    />
                </div>

                <!-- Description -->
                <div class="mt-4">
                    <label
                        for="description"
                        class="block text-sm font-medium text-gray-700"
                        >Description</label
                    >
                    <MdEditor
                        id="description"
                        v-model="issueForm.description"
                        ref="issueDescriptionInput"
                        language="en-US"
                        placeholder="Enter description here"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                    <InputError
                        :message="issueForm.errors.description"
                        class="mt-2"
                    />
                </div>

                <!-- Submit -->
                <div class="mt-6 text-center flex justify-between">
                    <button
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-gray-600 hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring-gray-500 disabled:opacity-50"
                        @click="closeModal"
                    >
                        Cancel
                    </button>

                    <button
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-indigo-500 disabled:opacity-50"
                        :disabled="issueForm.processing"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="mr-2"
                            width="32"
                            height="32"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill="currentColor"
                                d="m10 16.4l-4-4L7.4 11l2.6 2.6L16.6 7L18 8.4l-8 8Z"
                            />
                        </svg>

                        Create Issue
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>
