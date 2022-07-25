<template>
    <AdminLayout title="Brute Force">
            <div class="p-6">
                <h1 class="text-2xl text-green-900 mb-4">Brute Force</h1>
                <div class="mb-[-1px]">
                    <button @click="fnRenameLoginPage" class="text-gray-900 py-2 px-4" :class="{'border-l border-t border-r bg-white':renameLoginPage}">Rename Admin Login Url</button>
                    <button @click="fnLoginCaptcha" class="text-gray-900 py-2 px-4" :class="{'border-l border-t border-r bg-white':loginCaptcha}">Login Captcha</button>
                </div>
                <div class="border-t">
                    <div id="id" ref="setting" v-if="renameLoginPage">
                    <form @submit.prevent="submit(admin_url.id)" class="border-r border-l border-b p-4 w-full md:w-2/3">
                        <div class="mb-4">
                            <label class="mr-14">Login Page Url</label>
                            <div class="flex items-center">
                                {{ hostname }}<JetInput v-model="form.adminLoginUrl" type="text" name="attempt" :class="{'border-red-500' : errors.adminLoginUrl}" class="md:w-[300px] w-[150px] border ring:border-none focus:outline-none focus:border-none focus:bg-none px-2 py-1"/>
                                <p v-if="errors.adminLoginUrl" class="text-red-500">{{ errors.adminLoginUrl }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <label class="mr-14"></label>
                            <button type="submit" class="bg-gray-700 py-1 px-4 rounded text-gray-50"> Save </button>
                        </div>

                    </form>
                </div>
                </div>
            </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout";
import JetInput from '@/Jetstream/Input.vue';
import {useForm} from "@inertiajs/inertia-vue3";
export default {
    props: {
      errors: Object,
        admin_url: Object,

    },
    name: "BruteForce",
    components: {
        AdminLayout, JetInput
    },
    data() {
        return{
            hostname: window.location.origin+'/',
            renameLoginPage: true,
            loginCaptcha: false,
            data: [1, 2],
            form: useForm({
                adminLoginUrl: null,
            }),
            convertSecond: '',
        }
    },

    created() {
        this.form.adminLoginUrl = this.$props.admin_url.admin_login_url
    },

    methods: {
        fnRenameLoginPage(){
            this.renameLoginPage = true
            this.loginCaptcha = false
        },
        fnLoginCaptcha(){
            this.renameLoginPage = false
            this.loginCaptcha = true
        },


        submit(id) {
            this.form.put(route('admin.brute.force.admin.login.url.update', id), {
                preserveState: false,
                preserveScroll: true,

                onSuccess: () => {
                    this.$toast.fire({
                        icon: 'success',
                        title: 'Admin login url saved'
                    })
                }
            })
        }
    },


}
</script>


<style scoped>

</style>
