<template>
    <AdminLayout>
        <div class="p-6">
            <div class="bg-white shadow border rounded p-4 mb-10 md:mb-0">
                <h1 class="text-2xl mb-4">Mail Setting</h1>
                <form @submit.prevent="update(props.mail_settings.id)">
                    <div class="md:flex md:gap-5 md:items-center mb-6">
                        <div class="md:w-1/2 mb-6 md:mb-0">
                            <JetLabel for="mailTransport" value="Mail Transport" />
                            <JetInput
                                id="mailTransport"
                                type="text"
                                v-model="form.mail_transport"
                                class="w-full"
                            />
                        </div>
                        <div class="md:w-1/2 mb-6 md:mb-0">
                            <JetLabel for="mailHost" value="Mail Host" />
                            <JetInput
                                id="mailHost"
                                type="text"
                                v-model="form.mail_host"
                                class="w-full"
                            />
                        </div>
                    </div>
                    <div class="md:flex md:gap-5 md:items-center mb-6">
                        <div class="md:w-1/2 mb-6 md:mb-0">
                            <JetLabel for="mailPort" value="Mail Port" />
                            <JetInput
                                id="mailPort"
                                type="text"
                                v-model="form.mail_port"
                                class="w-full"
                            />
                        </div>
                        <div class="md:w-1/2 mb-6 md:mb-0">
                            <JetLabel for="mailUsername" value="Mail Username" />
                            <JetInput
                                id="mailUsername"
                                type="text"
                                v-model="form.mail_username"
                                class="w-full"
                            />
                        </div>
                    </div>
                    <div class="md:flex md:gap-5 md:items-center mb-6">
                        <div class="md:w-1/2 mb-6 md:mb-0">
                            <JetLabel for="mailPassword" value="Mail Password" />
                            <JetInput
                                id="mailPassword"
                                type="password"
                                v-model="form.mail_password"
                                class="w-full"
                            />
                        </div>
                        <div class="md:w-1/2 mb-6 md:mb-0">
                            <JetLabel for="mailEncryption" value="Mail Encryption" />
                            <JetInput
                                id="mailEncryption"
                                type="text"
                                v-model="form.mail_encryption"
                                class="w-full"
                            />
                        </div>
                    </div>
                    <div class="md:flex md:gap-5 md:items-center mb-6">
                        <div class="md:w-1/2 mb-6 md:mb-0">
                            <JetLabel for="mailFrom" value="Mail From" />
                            <JetInput
                                id="mailFrom"
                                type="email"
                                v-model="form.mail_from"
                                class="w-full"
                            />
                        </div>
                    </div>
                    <JetButton class="bg-blue-500 hover:bg-blue-600 rounded" :class="{ 'opacity-25': form.processing || disable === true}" :disabled="form.processing || disable === true">Update</JetButton>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
    import AdminLayout from "../../../Layouts/AdminLayout";
    import JetInput from '@/Jetstream/Input.vue';
    import JetLabel from '@/Jetstream/Label.vue';
    import JetButton from '@/Jetstream/Button.vue';
    import {useForm} from "@inertiajs/inertia-vue3";
    import {onMounted, ref, watch} from "vue";
    import Swal from "sweetalert2";

   const props = defineProps({
       mail_settings: Object
   });
    const disable = ref(true);
    const form = useForm({
        mail_transport: '',
        mail_host: '',
        mail_port: '',
        mail_username: '',
        mail_password: '',
        mail_encryption: '',
        mail_from: '',
    });

    onMounted(()=>{
       form.mail_transport = props.mail_settings.mail_transport;
       form.mail_host = props.mail_settings.mail_host;
       form.mail_port = props.mail_settings.mail_port;
       form.mail_username = props.mail_settings.mail_username;
       form.mail_password = props.mail_settings.mail_password;
       form.mail_encryption = props.mail_settings.mail_encryption;
       form.mail_from = props.mail_settings.mail_from;
    })

    watch(form, (current, old)=>{
        if(Object.values(form)[0] !== "" && Object.values(form)[1] !== "" && Object.values(form)[2] !== "" && Object.values(form)[3] !== "" && Object.values(form)[4] !== "" && Object.values(form)[5] !== "" && Object.values(form)[6] !== ""){
            disable.value = false
        }else {
            disable.value = true
        }
    })

    const update = ($id) => {
        form.put(route('admin.mail.update', $id),  {
            onSuccess: ()=>{
                Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                }).fire({
                    icon: 'success',
                    title: 'Mail settings update successfully'
                })
            }
        })
    }
</script>

<style scoped>

</style>
