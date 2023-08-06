<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";
import Modal from "@/Components/Modal.vue";

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
        issue: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        // Modals
        const deletingIssue = ref(null);
        const editingComment = ref(null);

        // Inputs
        const issueToDelete = ref(null);

        // Forms
        const commentForm = useForm({
            comment: "",
        });
        const editCommentForm = useForm({
            _method: "patch",
            id: "",
            comment: "",
        });
        const deleteIssueForm = useForm({
            _method: "delete",
            id: "",
            title: "",
        });

        // Actions
        const deleteIssue = (user) => {
            issueToDelete.value = user;
            deleteIssueForm.id = user.id;
            deleteIssueForm.title = user.title;
            deletingIssue.value = true;
        };
        const editComment = (comment) => {
            editingComment.value = comment.id;
            editCommentForm.id = comment.id;
            editCommentForm.comment = comment.comment;
        };
        const cancelEditing = () => {
            editingComment.value = null;
            editCommentForm.reset();
        };

        const handleCommentSubmit = () => {
            // Make the API call to submit the comment using the `post` function
            commentForm.post(route("issues.comments.store", props.issue.id), {
                preserveScroll: true,
                onSuccess: () => {
                    // Reset the form after success
                    commentForm.reset();

                    // Log
                    console.log(
                        "Comment submitted for issue " + props.issue.id
                    );
                },
                onError: () => {
                    // Log
                    console.log(
                        "Error submitting comment for issue " + props.issue.id
                    );
                },
                onFinish: () => {
                    // Log
                    console.log(
                        "Finished submitting comment for issue " +
                        props.issue.id
                    );
                },
            });
        };
        const handleCommentEditSubmit = () => {
            // Make the API call to update the comment using the `put` function
            editCommentForm.patch(route("issues.comments.update", [props.issue.id]), {
                preserveScroll: true,
                onSuccess: () => {
                    // Reset the form after success
                    commentForm.reset();
                    cancelEditing();
                },
                onError: () => {
                    // Log
                    console.log("Error updating comment for issue " + props.issue.id);
                },
            });
        };
        const handleDeleteIssueSubmit = () => {
            // Make the API call to submit the comment using the `post` function
            deleteIssueForm.post(route("issues.destroy"), {
                preserveScroll: true,
                onSuccess: () => {
                    // Reset the form after success
                    deleteIssueForm.reset();
                },
                onError: () => {
                    // Log
                    console.log("Error deleting issue: " + props.issue.id);
                },
            });
        };

        // Reset forms
        const resetCommentForm = () => {
            commentForm.reset();
        };
        const resetDeleteIssueForm = () => {
            deleteIssueForm.reset();
        };

        // Close modals
        const closeModal = () => {
            deletingIssue.value = false;
        };

        return {
            // Modals
            deletingIssue,
            editingComment,
            // Inputs
            issueToDelete,
            // Forms
            commentForm,
            editCommentForm,
            deleteIssueForm,
            // Actions
            deleteIssue,
            handleCommentEditSubmit,
            editComment,
            cancelEditing,
            handleCommentSubmit,
            handleDeleteIssueSubmit,
            resetCommentForm,
            resetDeleteIssueForm,
            // Close modals
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
        /**
         * Convert new line to \<br>
         * @param {*} str String to be converted
         * @returns String with \<br>
         */
        nl2br(str) {
            if (typeof str !== "string") {
                throw new Error("Expected a string");
            }
            return str.replace(/(?:\r\n|\r|\n)/g, "<br>");
        },
    },
};
</script>

<template>
    <Head :title="'Issue: ' + issue.title" />

    <!-- Issue actions -->
    <div class="row justify-content-end px-3 mb-2">
        <Link class="btn btn-sm btn-primary me-2 close_issue rounded col-auto" type="button"
            :href="route('issues.updateStatus', [issue.id, 1])" method="patch" as="button" v-if="($page.props.auth.user.type == 1 && issue.status == 0) ||
                ($page.props.auth.user.department_id == issue.department_id &&
                    issue.status == 0)
                ">
        Close Issue
        </Link>
        <Link class="btn btn-sm btn-success me-2 open_issue rounded col-auto" type="button"
            :href="route('issues.updateStatus', [issue.id, 0])" method="patch" as="button" v-if="($page.props.auth.user.type == 1 && issue.status == 1) ||
                ($page.props.auth.user.department_id == issue.department_id &&
                    issue.status == 1)
                ">
        Open Issue
        </Link>
        <a class="btn btn-sm btn-danger delete_data rounded col-auto" v-if="$page.props.auth.user.type == 1 ||
            $page.props.auth.user.id == issue.user_id
            " :data-id="issue.id" @click="deleteIssue(issue)">
            Delete Issue
        </a>
    </div>

    <!-- Issue -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Issue: {{ issue.title }}</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <p>
                                <small class="text-muted">Posted by:</small><br />
                                <b>{{ issue.user.fullname }}</b>
                            </p>
                            <p>
                                <small class="text-muted">Posted for:</small><br />
                                <b>{{ issue.department.name }}</b>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p>
                                <small class="text-muted">Status:</small><br />
                                <span class="badge bg-success" v-if="issue.status == 0">
                                    Open
                                </span>
                                <span class="badge bg-danger" v-else>
                                    Closed
                                </span>
                            </p>
                            <p>
                                <small class="text-muted">Date Posted:</small><br />
                                {{ formatDate(issue.created_at) }}
                            </p>
                        </div>
                    </div>
                    <hr class="my-4" />
                    <div class="md-html" v-html="nl2br(issue.description)"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comments -->
    <div class="py-3">
        <!-- Comments -->
        <div class="row mb-2">
            <div class="col-md-8">
                <h5><b>Comments:</b></h5>
                <hr />
                <ul class="list-group">
                    <li class="list-group-item p-3 mb-3 border comment-item" v-for="comment in issue.comments"
                        :key="comment.id">
                        <!-- Edit comment -->
                        <div v-if="editingComment === comment.id">
                            <div class="flex justify-between">
                                <div>
                                    <h6 class="mb-0">
                                        <b>{{ comment.user.fullname }}</b>
                                    </h6>
                                    <p class="mb-0 text-muted">
                                        {{ formatDate(comment.created_at) }}
                                    </p>
                                </div>
                                <div v-if="$page.props.auth.user.type == 1 || $page.props.auth.user.id == comment.user_id">
                                    <button type="button" class="btn btn-sm btn-danger me-2" @click="cancelEditing()">
                                        Cancel
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        :disabled="editCommentForm.processing" @click="handleCommentEditSubmit(comment)">
                                        <span v-if="editCommentForm.processing">
                                            Saving...
                                        </span>
                                        <span v-else>
                                            Save
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <textarea v-model="editCommentForm.comment" rows="4"
                                class="form-control resize-none w-full rounded-lg px-3 py-2 bg-white shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                required placeholder="Write your comment here."></textarea>
                            <InputError :message="editCommentForm.errors.comment" class="mt-2 text-red-500" />
                        </div>

                        <!-- Comment -->
                        <div v-else>
                            <div class="flex justify-between">
                                <div>
                                    <h6 class="mb-0">
                                        <b>{{ comment.user.fullname }}</b>
                                    </h6>
                                    <p class="mb-0 text-muted">
                                        {{ formatDate(comment.created_at) }}
                                    </p>
                                </div>
                                <div v-if="$page.props.auth.user.type == 1 || $page.props.auth.user.id == comment.user_id">
                                    <button type="button" class="btn btn-sm btn-secondary me-2"
                                        @click="editComment(comment)">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" @click="deleteComment(comment)">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <div class="md-html mt-2" v-html="nl2br(comment.comment)"></div>
                        </div>
                    </li>
                    <li class="text-center list-group-item" v-if="!issue.comments || issue.comments.length === 0">
                        No comments listed yet.
                    </li>
                </ul>
            </div>
        </div>

        <!-- Comment form -->
        <div class="row">
            <div class="col-md-8">
                <form id="comment-form" @submit.prevent="handleCommentSubmit">
                    <div class="form-group">
                        <textarea v-model="commentForm.comment" name="comment" id="comment" rows="4"
                            class="form-control rounded-1" required placeholder="Write your comment here."></textarea>
                        <InputError :message="commentForm.errors.comment" />
                    </div>
                    <div class="form-group row justify-content-end py-2 px-3">
                        <button class="btn btn-sm rounded btn-success col-auto me-2" :disabled="commentForm.processing">
                            Save
                        </button>
                        <button class="btn btn-sm rounded btn-secondary col-auto" type="button" @click="resetCommentForm">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <Modal :show="deletingIssue" @close="closeModal">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900">Delete Issue</h2>
            <p class="mt-2 text-sm text-gray-600">
                Are you sure you want to delete this issue?
            </p>
            <InputError :message="deleteIssueForm.errors.issue" class="mt-2" />
            <hr class="mt-4" />
            <form @submit.prevent="handleDeleteIssueSubmit">
                <!-- Title -->
                <div class="mt-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input id="title" v-model="deleteIssueForm.title" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100"
                        placeholder="Enter title here" readonly />
                    <InputError :message="deleteIssueForm.errors.id" class="mt-2" />
                </div>

                <!-- Id -->
                <div class="mt-4">
                    <label for="id" class="block text-sm font-medium text-gray-700">Id</label>
                    <input id="id" v-model="deleteIssueForm.id" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100"
                        placeholder="Enter id here" readonly />
                    <InputError :message="deleteIssueForm.errors.id" class="mt-2" />
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
                        :disabled="deleteIssueForm.processing">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="32" height="32" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M19 6.5l-1.5-1.5-5.5 5.5-5.5-5.5L5 6.5l5.5 5.5-5.5 5.5L6.5 19l5.5-5.5 5.5 5.5L19 17.5l-5.5-5.5Z" />
                        </svg>

                        <span v-if="deleteIssueForm.processing">
                            Processing...
                        </span>
                        <span v-else>Delete </span>
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>

<style scoped>
.comment-item {
    margin-bottom: 20px;
}

.comment-field {
    word-wrap: break-word;
}

.comment-field p {
    margin-bottom: 0;
}

.comment-item {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    border-radius: 5px;
}

.edit_comment,
.delete_comment {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 3px;
}

.edit_comment {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.edit_comment:hover {
    background-color: #0b5ed7;
    border-color: #0b5ed7;
}

.delete_comment {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

.delete_comment:hover {
    background-color: #c82333;
    border-color: #c82333;
}
</style>
