<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
        InputError,
    },
    layout: AuthenticatedLayout,
    props: {
        issue: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        const commentForm = useForm({
            comment: "",
        });

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

        const resetCommentForm = () => {
            // Reset the form using the `reset` function
            commentForm.reset();
        };

        return {
            commentForm,
            handleCommentSubmit,
            resetCommentForm,
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
        <Link
            class="btn btn-sm btn-primary me-2 close_issue rounded col-auto"
            type="button"
            :href="route('issues.updateStatus', [issue.id, 1])"
            method="patch"
            as="button"
            v-if="
                ($page.props.auth.user.type == 1 && issue.status == 0) ||
                ($page.props.auth.user.department_id == issue.department_id &&
                    issue.status == 0)
            "
        >
            Close Issue
        </Link>
        <Link
            class="btn btn-sm btn-success me-2 open_issue rounded col-auto"
            type="button"
            :href="route('issues.updateStatus', [issue.id, 0])"
            method="patch"
            as="button"
            v-if="
                ($page.props.auth.user.type == 1 && issue.status == 1) ||
                ($page.props.auth.user.department_id == issue.department_id &&
                    issue.status == 1)
            "
        >
            Open Issue
        </Link>
        <Link
            class="btn btn-sm btn-danger delete_data rounded col-auto"
            type="button"
            :href="route('issues.destroy', issue.id)"
            method="delete"
            as="button"
            v-if="
                $page.props.auth.user.type == 1 ||
                $page.props.auth.user.id == issue.user_id
            "
        >
            Delete Issue
        </Link>
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
                                <small class="text-muted">Posted by:</small
                                ><br />
                                <b>{{ issue.user.fullname }}</b>
                            </p>
                            <p>
                                <small class="text-muted">Posted for:</small
                                ><br />
                                <b>{{ issue.department.name }}</b>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p>
                                <small class="text-muted">Status:</small><br />
                                <span
                                    class="badge bg-success"
                                    v-if="issue.status == 0"
                                >
                                    Open
                                </span>
                                <span class="badge bg-danger" v-else>
                                    Closed
                                </span>
                            </p>
                            <p>
                                <small class="text-muted">Date Posted:</small
                                ><br />
                                {{ formatDate(issue.created_at) }}
                            </p>
                        </div>
                    </div>
                    <hr class="my-4" />
                    <div
                        class="md-html"
                        v-html="nl2br(issue.description)"
                    ></div>
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
                    <li
                        class="list-group-item p-3 mb-3 border comment-item"
                        v-for="comment in issue.comments"
                        :key="comment.id"
                    >
                        <div class="border-bottom border-dark">
                            <p class="m-0">
                                <b>{{ comment.user.fullname }}</b>
                            </p>
                            <small>
                                {{ formatDate(comment.created_at) }}
                            </small>
                        </div>
                        <div class="pl-4 py-3 comment-field">
                            <p class="m-0">{{ comment.comment }}</p>
                        </div>
                        <div class="row justify-content-end px-3">
                            <div
                                class="col-auto"
                                v-if="
                                    comment.user_id ==
                                        $page.props.auth.user.id ||
                                    $page.props.auth.user.type == 1
                                "
                            >
                                <button
                                    class="btn btn-sm btn-primary rounded me-2"
                                    type="button"
                                    :data-id="comment.id"
                                >
                                    Edit
                                </button>
                                <Link
                                    class="btn btn-sm btn-danger rounded"
                                    type="button"
                                    :href="
                                        route('issues.comments.destroy', [
                                            issue.id,
                                            comment.id,
                                        ])
                                    "
                                    method="delete"
                                    as="button"
                                >
                                    Delete
                                </Link>
                            </div>
                        </div>
                    </li>
                    <li
                        class="text-center list-group-item"
                        v-if="!issue.comments || issue.comments.length === 0"
                    >
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
                        <textarea
                            v-model="commentForm.comment"
                            name="comment"
                            id="comment"
                            rows="4"
                            class="form-control rounded-1"
                            required
                            placeholder="Write your comment here."
                        ></textarea>
                        <InputError :message="commentForm.errors.comment" />
                    </div>
                    <div class="form-group row justify-content-end py-2 px-3">
                        <button
                            class="btn btn-sm rounded btn-success col-auto me-2"
                            :disabled="commentForm.processing"
                        >
                            Save
                        </button>
                        <button
                            class="btn btn-sm rounded btn-secondary col-auto"
                            type="button"
                            @click="resetCommentForm"
                        >
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
