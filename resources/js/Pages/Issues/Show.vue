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
        issue: {
            type: Object,
            required: true,
        },
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

    <div class="row justify-content-end px-3 mb-2">
        <button
            class="btn btn-sm btn-primary me-2 close_issue rounded-0 col-auto"
            type="button"
            :data-id="issue.id"
            v-if="
                $page.props.auth.user.type == 1 ||
                ($page.props.auth.user.department_id == issue.department_id &&
                    issue.status == 0)
            "
        >
            Close Issue
        </button>
        <button
            class="btn btn-sm btn-danger delete_data rounded-0 col-auto"
            type="button"
            :data-id="issue.id"
            v-if="
                $page.props.auth.user.type == 1 ||
                $page.props.auth.user.id == issue.user_id
            "
        >
            Delete Issue
        </button>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body pb-4">
                    <h4>
                        <b>Issue: {{ issue.title }}</b>
                    </h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <span
                                ><small
                                    >Posted by:
                                    <b>{{ issue.user.fullname }}</b></small
                                ></span
                            ><br />
                            <span
                                ><small
                                    >Posted for:
                                    <b>{{ issue.department.name }}</b></small
                                ></span
                            >
                        </div>
                        <div class="col-sm-6">
                            <span
                                ><small
                                    >Status:&nbsp;
                                    <span
                                        class="badge bg-success"
                                        v-if="issue.status == 0"
                                        >Open</span
                                    >
                                    <span class="badge bg-danger" v-else
                                        >Closed</span
                                    >
                                </small></span
                            ><br />
                            <span>
                                <small
                                    >Date Posted:
                                    {{ formatDate(issue.created_at) }}
                                </small></span
                            >
                        </div>
                    </div>

                    <hr class="border-light" />
                    <div v-html="nl2br(issue.description)"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-3">
        <div class="row mb-2">
            <div class="col-md-8">
                <h5><b>Comment/s:</b></h5>
                <hr />
                <ul class="list-group">
                    <li
                        class="list-group-item p-1 mb-3 border-1 comment-item"
                        v-for="comment in issue.comments"
                        :key="comment.id"
                    >
                        <div class="w-100 border-bottom border-dark">
                            <p class="m-0">
                                <b>{{ comment.user.fullname }}</b>
                            </p>
                            <small>
                                {{ formatDate(comment.created_at) }}
                            </small>
                        </div>
                        <div class="w-100 pl-4 py-3 comment-field">
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
                                    class="btn btn-sm btn-primary edit_comment rounded-0 col-auto me-2"
                                    type="button"
                                    :data-id="comment.id"
                                >
                                    Edit
                                </button>
                                <button
                                    class="btn btn-sm btn-danger delete_comment rounded-0 col-auto"
                                    type="button"
                                    :data-id="comment.id"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </li>
                    <li
                        class="text-center list-group-item"
                        v-if="!issue.comments || issue.comments.length == 0"
                    >
                        No comment listed yet.
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <form action="" id="comment-form">
                    <div class="form-group">
                        <input
                            type="hidden"
                            name="issue_id"
                            value="<?php echo $res['issue_id'] ?>"
                        />
                        <input type="hidden" name="id" />
                        <textarea
                            name="comment"
                            id="comment"
                            rows="4"
                            class="form-control rounded-0"
                            required
                            placeholder="Write your comment here."
                        ></textarea>
                    </div>
                    <div class="form-group row justify-content-end py-2 px-3">
                        <button
                            class="btn btn-sm rounded-0 btn-primary col-auto me-2"
                        >
                            Save
                        </button>
                        <button
                            class="btn btn-sm rounded-0 btn-secondary col-auto"
                            type="button"
                            onclick="$('#comment-form').get(0).reset()"
                        >
                            Cancel
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
