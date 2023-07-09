<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

export default {
    components: {
        GuestLayout,
        Head,
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
    },

    props: {
        status: {
            type: String,
        },
    },

    setup() {
        const form = useForm({
            email: "",
        });

        const submit = () => {
            form.post(route("password.email"));
        };

        return {
            form,
            submit,
        };
    },
};
</script>

<template>
    <Head title="Forgot Password" />

    <div class="forgot-password-page">
        <div class="forgot-password-form">
            <h3 class="mb-4 text-lg font-medium text-gray-900">
                Forgot your password?
            </h3>
            <div class="mb-4 text-sm text-gray-600">
                No problem. Just let us know your email address and we will
                email you a password reset link that will allow you to choose a
                new one.
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Email Password Reset Link
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.forgot-password-page {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f5f5f5;
}

.forgot-password-form {
    width: 600px;
    padding: 40px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.mb-4 {
    margin-bottom: 1rem;
}

.text-sm {
    font-size: 0.875rem;
}

.text-gray-600 {
    color: #4b5563;
}

.font-medium {
    font-weight: 500;
}

.text-green-600 {
    color: #38a169;
}

.mt-1 {
    margin-top: 0.25rem;
}

.block {
    display: block;
}

.w-full {
    width: 100%;
}

.mt-2 {
    margin-top: 0.5rem;
}

.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-end {
    justify-content: flex-end;
}

.mt-4 {
    margin-top: 1rem;
}

.opacity-25 {
    opacity: 0.25;
}

.text-xs {
    font-size: 0.75rem;
}

.text-indigo-500 {
    color: #3b82f6;
}

.focus\:outline-none:focus {
    outline: none;
}

.focus\:ring-2:focus {
    box-shadow: 0 0 0 2px rgba(66, 153, 225, 0.5);
}

.focus\:ring-offset-2:focus {
    box-shadow: 0 0 0 2px rgba(244, 244, 245, 0.5);
}

.forgot-password-link {
    color: #3b82f6;
}

.underline {
    text-decoration: underline;
}
</style>
