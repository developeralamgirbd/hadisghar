<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('admin.password.email'));
};
</script>

<template>
    <Head title="Admin Log in" />
    <div class="admin-login">
        <div class="flex justify-center items-center min-h-screen">
            <div class="card shadow bg-gray-50 rounded w-[420px] p-8">
                <h1 class="text-2xl text-center font-semibold uppercase text-gray-800 mb-4">Admin Password Reset</h1>
                <div class="mb-4 text-sm text-gray-600">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </div>
                <JetValidationErrors class="mb-4" />

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <JetLabel for="email" value="Email" />
                        <JetInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                            autofocus
                        />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <a :href="route('admin.login')" class="text-sm font-sans"><fa icon="fa-left-long" class="text-sm mr-1 "/>Go to login</a>
                        <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Password Reset Link
                        </JetButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.admin-login{
    position: relative;
    height: 100vh;
}
.admin-login-bg{
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    z-index: -9999;
}
</style>
