<template>
    <AdminLayout title="Backup">
            <div class="p-6">
                <h1 id="statusMsg" ref="statusMsg" class="text-green-500 text-2xl font-bold text-center">{{ $page.props.flash.status }}</h1>
               <h1 class="text-2xl mb-8">Backup Configuration</h1>
                <div class="flex">
                    <div class=" w-full">
                        <!-- App Key -->
                        <div class="md:flex mb-8">
                            <p for="app_key" class="md:w-1/6">
                                Choose your remote storage
                                (tap on an icon to select or unselect):<span class="text-red-600">*</span></p>
                            <div class="field md:w-5/6">
                                <button @click="dropboxForm = !dropboxForm"
                                        class="flex items-center hover:bg-gray-50 rounded p-4 transition duration-300"
                                        :class="{'bg-gray-100': dropboxForm}"> <img :src="'/images/dropbox.png'" alt="dropbox" class="mr-2"><span>Dropbox</span> </button>
                            </div>
                        </div>
                        <form @submit.prevent="submit" class="rounded" v-if="dropboxForm">
                            <!-- App Key -->
                            <div class="field-group md:flex mb-6">
                                <label for="app_key" class="md:w-1/6">App key<span class="text-red-600">*</span></label>
                                <div class="field md:w-2/6">
                                    <input type="text" v-model="form.app_key" name="app_key" id="app_key" class="w-full rounded border-gray-400" :class="errors.app_key ? 'border-red-500' : ''" autocomplete="app_key">
                                    <p class="text-red-500" v-if="errors.app_key">{{ errors.app_key }}</p>
                                </div>
                            </div>
                            <!-- App Secret -->
                            <div class="field-group md:flex mb-6">
                                <label for="appSecret" class="md:w-1/6">App Secret<span class="text-red-600">*</span></label>
                                <div class="field md:w-2/6 relative">
                                    <input type="text" v-model="form.app_secret" name="app_secret" id="appSecret"
                                           class="w-full rounded border-gray-400" :class="errors.app_secret ? 'border-red-500' : ''" >
                                    <p class="text-red-500" v-if="errors.app_secret">{{ errors.app_secret }}</p>
                                </div>
                            </div>
                            <!-- Redirect URl -->
                            <div class="field-group md:flex mb-6">
                                <p class="md:w-1/6 text-sm ">Redirect URL<span class="text-red-600">*</span></p>
                                <div class="field md:w-2/6">
                                    <p class="text-gray-800 text-sm">{{ redirect }}</p>
                                </div>
                            </div>

                            <!-- Backup report sent email -->
                            <div class="field-group md:flex mb-6">
                                <p class="md:w-1/6">Email<span class="text-red-600">*</span></p>
                                <div class="field md:w-2/6">
                                    <input type="text" v-model="form.email" class="w-full rounded border-gray-400" id="email" placeholder="email">
                                    <label for="email" class="text-sm text-gray-500">Give a email to have a basic report sent to your email</label>
                                </div>
                            </div>

                            <button class="bg-blue-600 py-3 px-4 rounded text-white flex items-center gap-1" type="submit" :class="{ 'opacity-25': form.processing || disable == true}" :disabled="form.processing || disable == true">
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
                                Authorize Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout";
import {useForm} from "@inertiajs/inertia-vue3";
export default {
    name: "Settings",
    props: {
        reportEmail: String,
        errors: Object,
        success: String,
    },
    components: {
        AdminLayout
    },
    data(){
        return{
            dropboxForm: true,
            show: false,
            redirect: window.location.origin+'/admin/dropbox/authorize-token',
            form: useForm({
               app_key: '',
               app_secret: '',
                redirect_url: window.location.origin+'/admin/dropbox/authorize-token',
                email: '',

            }),
            disable: true,
        }
    },

    mounted() {
        const statusEl = this.$refs.statusMsg;
        if (this.$page.props.flash.status){
            setTimeout(function (){
                statusEl.style.opacity = '0';
                statusEl.style.transition = '03s';
            }, 5000);
        }
        this.form.email = this.reportEmail

    },

    watch: {
        form:{
            handler(){
                if(Object.values(this.form)[0] !== '' && Object.values(this.form)[1] !== '' && Object.values(this.form)[2] !== ''){
                    this.disable = false
                }else{
                    this.disable = true
                }
            },
            deep: true,
        }
    },

    methods: {
        submit(){
            this.form.post(route('admin.dropbox.store'), {
                preserveState: false
            });
        }
    }

}
</script>

<style scoped>

</style>
