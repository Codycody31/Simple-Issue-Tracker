<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

export default {
    props: {
        token: String,
        email: String,
    },
    components: {
        GuestLayout,
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
    },
    setup(props) {
        const form = useForm({
            token: props.token,
            email: props.email,
            password: "",
            password_confirmation: "",
        });

        const submit = () => {
            form.post(route("password.store"), {
                onFinish: () => form.reset("password", "password_confirmation"),
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
    <Head title="Reset Password" />

    <div class="reset-password-page">
        <div class="reset-password-form">
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

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm Password"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Reset Password
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.reset-password-page {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f5f5f5;
}

.reset-password-form {
    width: 400px;
    padding: 40px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.mt-4 {
    margin-top: 1rem;
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

.opacity-25 {
    opacity: 0.25;
}

.reset-password-button {
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
</style>
