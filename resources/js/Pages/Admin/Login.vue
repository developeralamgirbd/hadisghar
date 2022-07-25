<script>
    export default {
        props: {
            msg: String,
            retries: String,
        },
        data(){
            return{

            }
        },
        computed: {
            padTo2Digits() {
                let totalSeconds = this.$page.props.flash.msg;
                let minutes = Math.floor(totalSeconds / 60);
                let seconds = totalSeconds % 60;
                return minutes.toString().padStart(2, '0')+':'+seconds.toString().padStart(2, '0');
            }
        },

    }
</script>
<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import {watch} from "vue";
import axios from "axios";

defineProps({
    canResetPassword: Boolean,
    status: String,
    errors: Object,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

</script>

<template>
    <Head title="Admin Log in" />
    <div class="admin-login bg-gray-50">
        <div class="flex justify-center items-center min-h-screen">
            <div>
                <div class="">
                    <div v-if="$page.props.flash.status" class="mb-4 font-medium text-sm text-green-600 border">
                        {{ $page.props.flash.status }}
                    </div>
                    <div v-if="$page.props.flash.msg" class="mb-4 font-medium text-sm text-red-500 p-4 rounded border-l-3 border-red-500 p-2 border">
                        <p><span class="uppercase">Error:</span> Too many failed login attempts. </p>
                        Please try again in {{ padTo2Digits }} minutes
                    </div>
                    <div v-if="$page.props.flash.retries > 0" class="mb-4 font-medium text-sm text-red-600 bg-white p-4 rounded border-l-3 border-red-500 p-2 border">
                        <p><span class="uppercase">Error:</span> <JetValidationErrors class="mb-4" /> </p>
                        {{ $page.props.flash.retries }} Remaining attempt.
                    </div>
                </div>

                <div class="bg-white shadow rounded border border-gray-300 md:w-[380px]">
                <h1 class="text-2xl text-center font-semibold uppercase text-gray-900 mb-2 p-6">Admin Login</h1>
                <form @submit.prevent="submit" class="p-6">
                    <div>
                        <JetLabel class="text-gray-900 text-lg" for="email" value="Email" />
                        <JetInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            ref="email"
                            class="mt-1 block w-full text-gray-900"
                            required
                            autofocus
                        />
                    </div>

                    <div class="mt-4">
                        <JetLabel class="text-gray-900 text-lg" for="password" value="Password" />
                        <JetInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full text-gray-900"
                            required
                            autocomplete="current-password"
                        />
                    </div>

                    <div class="block mt-4">
                        <label class="flex items-center">
                            <JetCheckbox v-model:checked="form.remember" name="remember" />
                            <span class="ml-2 text-sm text-gray-900">Remember me</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <Link :href="route('admin.password.request')" class="underline text-sm text-gray-900">
                            Forgot your password?
                        </Link>


                        <button type="submit" class="ml-4 rounded bg-green-600 font-bold text-white px-3 py-1 uppercase" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Log in
                        </button>
                    </div>
                </form>

            </div>
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
