<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="h-100 d-flex jsutify-content-center align-items-center">
            <div class="w-100">
                <!-- Session Status -->
                <div
                    v-if="status"
                    class="mb-4 font-medium text-sm text-green-600"
                >
                    {{ status }}
                </div>

                <!-- Login -->
                <h3 class="py-5 text-center">Simple Issue Tracker System</h3>
                <div class="card my-3 col-md-4 offset-md-4">
                    <div class="card-body">
                        <form @submit.prevent="submit" id="login-form">
                            <center>
                                <small>Please enter your credentials.</small>
                            </center>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email" class="control-label"
                                    >Email</label
                                >
                                <input
                                    id="email"
                                    type="email"
                                    class="form-control form-control-sm rounded-0"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.email"
                                />
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password" class="control-label"
                                    >Password</label
                                >
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control form-control-sm rounded-0"
                                    required
                                    v-model="form.password"
                                    autocomplete="current-password"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.password"
                                />
                            </div>

                            <!-- Remember Me -->
                            <div class="block mt-4">
                                <label class="flex items-center">
                                    <Checkbox
                                        name="remember"
                                        v-model:checked="form.remember"
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >Remember me</span
                                    >
                                </label>
                            </div>

                            <!-- Login -->
                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Forgot your password?
                                </Link>
                                <button
                                    class="btn btn-sm btn-primary rounded-0 my-1 ml-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
