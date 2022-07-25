<template>
    <AdminLayout title="Limit Login Attempts">
            <div class="p-6">
                <h1 class="text-2xl text-green-900 mb-4">Limit Login Attempts</h1>
                <div class="mb-[-1px]">
                    <button @click="setting" class="text-gray-900 py-2 px-4" :class="{'border-l border-t border-r bg-white':active}">Settings</button>
                    <button @click="attemptLogs" class="text-gray-900 py-2 px-4" :class="{'border-l border-t border-r bg-white':logs}">Logs</button>
                </div>
                <div class="border-t">
                    <div id="id" ref="setting" v-if="active">
                    <form @submit.prevent="submit(limitLoginAttempt.id)" class="border-r border-l border-b md:w-[500px] p-1 md:p-4">
                        <p class="text-lg">Lockout</p>
                        <div class="mb-4 flex">
                            <label class="mr-2 md:mr-14">allowed attempt</label>
                            <div class="">
                                <JetInput @keypress="isNumber($event)" v-model="form.attempt" type="text" name="attempt" :class="{'border-red-500' : errors.attempt}" class="border ring:border-none focus:outline-none focus:border-none focus:bg-none px-2 w-[60px]"/>
                                <p v-if="errors.attempt" class="text-red-500">{{ errors.attempt }}</p>
                            </div>
                        </div>
                        <div class="mb-4 flex">
                            <label class="mr-2 md:mr-14">minutes lockout</label>
                            <div class="">
                                <JetInput @keypress="isNumber($event)" v-model="form.seconds" type="text" name="seconds" :class="{'border-red-500' : errors.seconds}" class="border ring:border-none focus:outline-none focus:border-none focus:bg-none px-2 w-[60px]"/>
                                <span class="ml-2">= {{ convertSecond ? convertSecond : '00:00' }} minutes</span>
                                <p class="text-sm text-gray-500"><span class="text-red-500">Note:</span> please provide seconds value</p>
                                <p v-if="errors.seconds" class="text-red-500">{{ errors.seconds }}</p>
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
        limitLoginAttempt: Object,

    },
    name: "LimitLoginAttempt",
    components: {
        AdminLayout, JetInput
    },
    data() {
        return{
            active: true,
            logs: false,
            data: [1, 2],
            form: useForm({
                attempt: null,
                seconds: null,
            }),
            convertSecond: '',
        }
    },

    methods: {
        setting(){
            this.active = true
            this.logs = false
        },
        attemptLogs(){
            this.active = false
            this.logs = true
        },

        isNumber: function(evt) {
            evt = (evt) ? evt : window.event;
            let charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                evt.preventDefault();
            } else {
                return true;
            }
        },

        submit(id) {
            this.form.put(route('admin.limit.login.update', id), {
                preserveState: false,
                preserveScroll: true,

                onSuccess: () => {
                    this.$toast.fire({
                        icon: 'success',
                        title: 'Limit login attempt saved'
                    })
                }
            })
        }
    },

    mounted() {
        this.form.attempt = this.$props.limitLoginAttempt.attempt;
        this.form.seconds = this.$props.limitLoginAttempt.second;
    },

    watch:{
        form: {
            handler(){
                let totalSeconds = this.form.seconds;
                let minutes = Math.floor(totalSeconds / 60);
                let seconds = totalSeconds % 60;
                this.convertSecond = minutes.toString().padStart(2, '0')+':'+seconds.toString().padStart(2, '0');
            },
            deep: true
        }
    }

}
</script>


<style scoped>

</style>
