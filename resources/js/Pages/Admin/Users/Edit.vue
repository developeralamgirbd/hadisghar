<template>
    <AdminLayout title="Edit User">
            <h1 class="uppercase text-green-500 text-2xl my-3 text-center">Edit User</h1>
            <div class="md:flex justify-between items-center ml-6">
                <div class="mb-4 md:mb-0">
                    <Link preserve-scroll :href="route('admin.user.index')" class="text-gray-50 bg-green-500 px-3 py-2 text-lg rounded"><fa icon="fa-arrow-left"></fa> Back</Link>
                </div>
            </div>
        <div class="p-2 md:p-6 w-full">
            <div class="flex">
                <div class="w-full">
                    <form @submit.prevent="userUpdate" class="mt-16 bg-white rounded p-2">
                        <!-- Name -->
                        <div class="field-group md:flex mb-6">
                            <p class="md:w-1/4">Name <span class="text-red-600">*</span></p>
                            <div class="field md:w-2/4">
                                <input type="text" name="name" v-model="form.name" class="w-full rounded border-gray-400" autocomplete="name" :class="errors.name ? 'border-red-500': ''">

                                <p class="text-red-500" v-if="errors.name" >{{ errors.name }}</p>
                            </div>
                        </div>
                        <!-- E-mail -->
                        <div class="field-group md:flex mb-6">
                            <p class="md:w-1/4">E-mail <span class="text-red-600">*</span></p>
                            <div class="field md:w-2/4">
                                <input type="email" name="email" v-model="form.email" class="w-full rounded border-gray-400" :class="errors.email ? 'border-red-500': ''">
                                <p class="text-red-500" v-if="errors.email" >{{ errors.email }}</p>
                            </div>
                        </div>
                        <!-- Role -->

                        <div class="field-group mb-6 md:mb-0 md:flex">
                            <p class="md:w-1/4">User Role <span class="text-red-600">*</span></p>
                            <select name="user_role" v-model="form.user_role" class="rounded border-gray-400 w-full md:w-1/4" :class="errors.user_role ? 'border-red-500': ''">
                                <option v-for="(role, index) in roles" :key="role.id" :value="role.name" selected>{{ role.name.toUpperCase() }}</option>
                            </select>
                            <p class="text-red-500  flex-col" v-if="errors.user_role" >{{ errors.user_role }}</p>
                        </div>
                        <div class="field-group hidden md:block flex mb-6">
                            <p class="w-1/4"></p>
                            <p class="text-red-500  flex-col" v-if="errors.user_role" >{{ errors.user_role }}</p>

                        </div>
                        <button class="bg-green-500 py-1 px-2 rounded text-green-50 flex items-center gap-1" type="submit" :class="{ 'opacity-25': form.processing || disable == true}" :disabled="form.processing || disable == true">

                            <svg v-if="form.processing" version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" class="w-[30px]" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                            <path fill="#fff" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                              <animateTransform
                                  attributeName="transform"
                                  attributeType="XML"
                                  type="rotate"
                                  dur="1s"
                                  from="0 50 50"
                                  to="360 50 50"
                                  repeatCount="indefinite" />
                          </path>
                        </svg>

                            <span v-if="form.processing">Updating</span>
                            <span v-else>Update</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>


<script>
import {useForm} from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Edit",
    props:{
        role: String,
        user: Object,
        roles: Object,
        errors: Object
    },
    data(){
        return{
            sending: false,
            form: useForm({
                name: '',
                email: '',
                send_notification: '',
                user_role: '',


            }),
            disable: true,
            passwordVisible: true
        }
    },

    methods:{
        userUpdate(){
            if(this.form.name !== '' && this.form.email !== '' && this.form.password !== '' && this.form.password_confirmation !== '' && this.form.user_role !== '') {

                Swal.fire({
                    title: 'Are you sure?',
                    html: `<h1 class="text-2xl text-red-600">Name: ${this.form.name} <br> Role: ${this.form.user_role}</h1>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.form.put(route('admin.user.update', this.$props.user.id), {
                            name: this.form.name,
                            email: this.form.email,
                            password: this.form.password,
                            send_notification: this.form.send_notification,
                            user_role: this.form.user_role,
                            _token: this.$page.props.csrf_token,

                            preserveScroll: true,
                            onSuccess: () => Swal.fire(
                                'Updated!',
                                'User has been updated.',
                                'success'
                            ),
                            onError: () => Swal.fire(
                                'Something Wrong!',
                                'Please try again.',
                                'warning'
                            ),
                        });

                    }
                })
            }
        },

        passwordReset(){
            Inertia.on('start', (event) =>{
                this.sending = true;
            });
            Inertia.on('finish', (event) =>{
                this.sending = false;
            });

            this.$inertia.post(route('admin.user.sendPasswordResetLink', this.form, {
                onSuccess: () => {
                    this.sending = false
                }
            }))
        }
    },
    mounted(){
      this.form.name = this.$props.user.name;
          this.form.email = this.$props.user.email;
          this.form.user_role = this.$props.user.email;
        this.form.user_role = this.$props.role;
    },

    watch: {
        form:{
            handler(){
                if(this.form.name === this.$props.user.name && this.form.email === this.$props.user.email && this.form.user_role === this.$props.role){
                    this.disable = true
                }else{
                    this.disable = false
                }

            },
            deep: true,
        }
    },
}
</script>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";
const err = {
    error: Boolean,
    disable: true

}

</script>
<style>

</style>
