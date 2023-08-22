<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

// const user = usePage().props.auth.user;

defineProps({
    user: {
        type: Object,
    },
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Employee Information
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                View your account's employee information.
            </p>
        </header>
        <div class="mt-6 space-y-6">
            <!-- Display User's Department Name -->
            <div>
                <span class="font-bold">Department: </span>
                <span>{{ user.department.name }}</span>
            </div>

            <!-- Display User's Designation -->
            <div>
                <span class="font-bold">Designation: </span>
                <span>{{ user.designation }}</span>
            </div>

            <!-- Display User's Employee ID -->
            <div>
                <span class="font-bold">Employee ID: </span>
                <span>{{ user.id }}</span>
            </div>

            <!-- Display User's role -->
            <div>
                <span class="font-bold">Role: </span>
                <span>{{ user.type === 1 ? "Admin" : "Employee" }}</span>
            </div>
        </div>
    </section>
</template>
