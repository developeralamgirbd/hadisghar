<template>
    <AdminLayout title="Create a new user">
            <h1 class="uppercase text-green-500 text-2xl my-3 text-center">Create New User</h1>
            <div class="md:flex justify-between items-center ml-6">
                <div class="mb-4 md:mb-0">
                    <Link preserve-scroll :href="route('admin.user.index')" class="text-gray-50 bg-green-500 px-3 py-2 text-lg rounded"><fa icon="fa-arrow-left"></fa> Back</Link>
                </div>
            </div>
            <div class="p-2 md:p-6 w-full">
            <div class="flex">
                <div class=" w-full">
                    <form @submit.prevent="submit" class="p-6 rounded">
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
                        <!-- Password -->
                        <div class="field-group md:flex mb-6">
                            <p class="md:w-1/4">Password <span class="text-red-600">*</span></p>
                            <div class="field md:w-2/4 relative">

                                    <input :type="passwordVisible ? 'text' : 'password'" name="password" v-model="form.password" class="w-full rounded border-gray-400" autocomplete="new-password" :class="errors.password ? 'border-red-500': ''">

                                <button type="button" @click="passwordVisible = !passwordVisible" class="absolute right-[2px] md:right-[-62px] top-[-28px] md:top-1 border border-green-600 rounded px-2 py-1 text-[10px]" >
                                    <span class="flex items-center">
                                        <i class="fa-solid mr-1 " :class="passwordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                                        <span :class="passwordVisible ? 'hidden' : 'block'">Show</span>
                                        <span :class="passwordVisible ? 'block' : 'hidden'">Hide</span>
                                    </span>
                                </button>
                                <p class="text-red-500" v-if="errors.password" >{{ errors.password }}</p>

                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div class="field-group md:flex mb-6">
                            <p class="md:w-1/4">Confirm Password <span class="text-red-600">*</span></p>
                            <div class="field md:w-2/4">
                                <input :type="passwordVisible ? 'text' : 'password'" name="password_confirmation" v-model="form.password_confirmation" class="w-full rounded border-gray-400" :class="errors.password ? 'border-red-500': ''" autocomplete="new-password">
                                <p class="text-red-500" v-if="errors.password" >{{ errors.password }}</p>
                            </div>
                        </div>
                        <!-- Generate Password -->
                        <div class="field-group md:flex mb-6">
                            <p class="md:w-1/4"></p>
                            <div class="field md:w-2/4">
                                <button type="button" @click="generatePassword" class="bg-green-500 text-white py-1 px-2 rounded">Generate Password</button>
                            </div>
                        </div>

                        <!-- Send User Notification -->
                        <div class="field-group md:flex mb-6">
                            <p class="md:w-1/4 hidden md:block">Send User Notification <span class="text-red-600">*</span></p>
                            <div class="field flex items-center gap-2">
                                <input id="send_notification" type="checkbox" name="send_notification" v-model="form.send_notification" class="rounded border-gray-400 w-[18px] h-[18px]" :class="errors.send_notification ? 'border-red-500': ''">
                                <label for="send_notification" class="w-full text-[14px] md:text-lg">Send the new user an email about their account.</label>
                            </div>
                            <p class="text-red-500" v-if="errors.send_notification" >{{ errors.send_notification }}</p>
                        </div>

                        <!-- Role -->
                        <div class="field-group mb-6 md:mb-0 md:flex">
                            <p class="md:w-1/4">User Role <span class="text-red-600">*</span></p>
                            <select name="user_role" v-model="form.user_role" class="rounded border-gray-400 w-full md:w-1/4" :class="errors.user_role ? 'border-red-500': ''">
                                <option value="" selected>Select Role</option>
                                <option v-for="(role, index) in roles" :key="role.id" :value="role.name">{{ role.name.toUpperCase() }}</option>
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
                            Create Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>


<script>
import {useForm, Link} from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    name: "Create",
    props:{
        roles: Object,
        errors: Object,
    },
    components:{
      AdminLayout, Link
    },
   data(){
        return{
            form: useForm({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                send_notification: '',
                user_role: '',

            }),
            disable: true,
            passwordVisible: true
        }
   },
    methods:{
        submit(){
            if(this.form.name !== '' && this.form.email !== '' && this.form.password !== '' && this.form.password_confirmation !== '' && this.form.user_role !== '') {

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Name: ${this.form.name}. role: ${this.form.user_role}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, create it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.form.post(route('admin.user.store'), {
                            name: this.form.name,
                            email: this.form.email,
                            password: this.form.password,
                            send_notification: this.form.send_notification,
                            user_role: this.form.user_role,
                            _token: this.$page.props.csrf_token,

                            preserveScroll: true,
                            onFinish: () => this.form.reset('password', 'password_confirmation'),
                            onSuccess: () => Swal.fire(
                                'Created!',
                                'New User has been created.',
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

        generatePassword(){
            this.form.password = '';
            this.form.password_confirmation = '';
            let length = 15;
              const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            for (let i = 0, n = charset.length; i < length; ++i) {
                this.form.password += charset.charAt(Math.floor(Math.random() * n));

            }
            this.form.password_confirmation += this.form.password;
        }

    },


    watch: {
        form:{
            handler(){
                if(Object.values(this.form)[0] !== null && Object.values(this.form)[1] && Object.values(this.form)[2] && Object.values(this.form)[3] && Object.values(this.form)[5]){
                    this.disable = false
                }else{
                    this.disable = true
                }

            },
            deep: true,
        }
    },
}
</script>
<style scoped>

</style>
