<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <main>
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient">
            <div class="container">
                <Link class="navbar-brand" :href="route('dashboard')">
                    <img
                        src="@/assets/imgs/Simple Issue Tracker.png"
                        alt="Issue Tracker"
                        width="30"
                        height="30"
                        class="d-inline-block align-top"
                    />
                    Issue Tracker
                </Link>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="./"
                                >Home</a
                            >
                        </li>
                        <!-- <?php if ($_SESSION['type'] == 1) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($page == 'department') ? 'active' : '' ?>" aria-current="page" href="./?page=department">Department</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($page == 'users') ? 'active' : '' ?>" aria-current="page" href="./?page=users">Users</a>
                            </li>
                        <?php endif; ?>
                        -->
                        <li class="nav-item">
                            <a class="nav-link" href="./?page=issues">Issues</a>
                        </li>
                        <!--
                        <?php if ($_SESSION['type'] != 1) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./?page=tickets">Tickets</a>
                            </li>
                        <?php endif; ?> -->
                    </ul>
                </div>
                <div>
                    <div class="dropdown">
                        <button
                            class="btn btn-secondary dropdown-toggle bg-transparent text-light border-0"
                            type="button"
                            id="dropdownMenuButton1"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            Hello {{ $page.props.auth.user.fullname }}
                        </button>
                        <ul
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton1"
                        >
                            <li>
                                <Link
                                    class="dropdown-item"
                                    :href="route('profile.edit')"
                                    >Manage Account</Link
                                >
                            </li>
                            <li>
                                <Link
                                    class="dropdown-item"
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    >Logout</Link
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="container py-5">
            <slot />
        </div>
    </main>
    <div
        class="modal fade"
        id="uni_modal"
        role="dialog"
        data-bs-backdrop="static"
    >
        <div
            class="modal-dialog modal-md modal-dialog-centered"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-primary"
                        id="submit"
                        onclick="$('#uni_modal form').submit()"
                    >
                        Save
                    </button>
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirm_modal" role="dialog">
        <div
            class="modal-dialog modal-md modal-dialog-centered"
            role="document"
        >
            <div class="modal-content rounded-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer py-1">
                    <button
                        type="button"
                        class="btn btn-primary btn-sm rounded-0"
                        id="confirm"
                        onclick=""
                    >
                        Continue
                    </button>
                    <button
                        type="button"
                        class="btn btn-secondary btn-sm rounded-0"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
