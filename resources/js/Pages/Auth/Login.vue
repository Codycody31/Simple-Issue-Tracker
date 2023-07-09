<script>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

export default {
    components: {
        Checkbox,
        GuestLayout,
        Head,
        InputError,
        Link,
    },

    props: {
        canResetPassword: Boolean,
        status: String,
    },

    setup(props) {
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

        return {
            form,
            submit,
        };
    },
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="login-page">
            <div class="login-form">
                <h3 class="login-header">Issue Tracker System</h3>
                <div class="alert alert-success" v-if="status">
                    {{ status }}
                </div>
                <div class="text-center">
                    <img
                        src="@/assets/imgs/Simple-Issue-Tracker.png"
                        alt="Issue Tracker"
                        width="100"
                        height="100"
                        class="d-inline-block align-top"
                    />
                </div>
                <form @submit.prevent="submit" id="login-form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            id="email"
                            type="email"
                            class="form-control"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError
                            class="error-message"
                            :message="form.errors.email"
                        />
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            required
                            v-model="form.password"
                            autocomplete="current-password"
                        />
                        <InputError
                            class="error-message"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="remember-me">
                        <label class="checkbox-label">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                            />
                            <span class="ml-2">Remember me</span>
                        </label>
                    </div>

                    <div class="forgot-password">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="forgot-password-link"
                        >
                            Forgot your password?
                        </Link>
                    </div>

                    <button
                        class="login-button"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Login
                    </button>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
.login-page {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f5f5f5;
}

.login-form {
    width: 400px;
    padding: 40px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.login-header {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    height: 40px;
    padding: 0 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
    font-size: 14px;
    color: #333;
}

.remember-me {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    color: #333;
}

.forgot-password {
    text-align: right;
    margin-bottom: 10px;
}

.forgot-password-link {
    color: #333;
}

.login-button {
    display: block;
    width: 100%;
    height: 40px;
    padding: 0 10px;
    border: none;
    border-radius: 5px;
    background-color: #3b82f6;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
}

.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
