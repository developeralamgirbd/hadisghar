<template>
    <AdminLayout>
            <div class="p-6">
                <div ref="outputEl2" class="my-4 text-green-600" v-if="$page.props.flash.output">
                    <div class="">
                        <p v-for="(msg, i) in $page.props.flash.output" :key="i">{{ msg }}</p>
                    </div>
                </div>
                <div class="backupWrap">
                    <h1 class="text-2xl">Next scheduled backups:</h1>
                    <div class="md:flex justify-between items-center border border-gray-300 w-full bg-white mt-2 mb-12 shadow">
                        <div class=" md:w-2/3 p-2 md:p-8 md:border-r border-gray-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="file">
                                   <h1>Files:</h1>
                                    <p class="text-green-500">{{ moment(nextBackupSchedule).format('MMMM Do YYYY, h:mm:ss a') }}</p>
                                </div>
                                <div class="database">
                                    <h1>Database:</h1>
                                    <p class="text-green-500">{{ moment(nextBackupSchedule).format('MMMM Do YYYY, h:mm:ss a') }}</p>
                                </div>
                            </div>
                            <h1 class="bg-gray-100 rounded md:w-2/3 p-2 text-rose-500"><span class="mr-2">Time now: </span>{{ time }}</h1>
                        </div>
                        <div class="md:w-1/3 md:p-8 flex justify-center items-center mb-2 md:mb-0">
                            <button @click.prevent="backupSubmit" type="button" class="bg-sky-600 text-white py-3 text-lg px-8 rounded flex gap-2" :class="{'opacity-25': backupProcess}" :disabled="backupProcess">
                                <svg v-if="backupProcess" version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" class="w-[30px]" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
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
                              Backup Now
                            </button>
                        </div>
                    </div>
                </div>
                <div class="md:flex items-center gap-2 mb-2">
                    <h1 class="text-2xl flex items-center gap-2">
                      <span>Existing backups</span>   <span class="bg-green-600 rounded-full text-white block w-5 h-5 text-center text-sm">{{ files.length }}</span>
                    </h1>
                    <Link :href="route('admin.backup.trash')" preserve-scroll class="ml-4 text-green-500"><fa icon="fa-trash"/> Trash ({{ totalDelete ? totalDelete.length : '0' }})</Link>
                </div>
                <table class="w-full md:w-2/3 bg-gray-50 rounded">
                    <thead class="text-left">
                    <tr>
                        <th class="text-gray-900 font-bold" >
                            Backup Date
                        </th>
                        <th class="text-gray-900 font-bold w-[7rem]" >
                            Action
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(file, i) in files" :key="i">
                        <td>
                            <p>{{ moment(file.server_modified).format('MMMM Do YYYY, h:mm:ss a') }}</p>
                            <img class="h-6 w-6 object-cover block mt-2" :src="'/images/dropbox.png'" >

                        </td>
                        <td>
                            <button type="button" @click.prevent="deleteFile(file.path_lower, i)" class="bg-red-400 py-1 px-4 rounded ml-2 text-white" :class="{'opacity-25': process && index == i}" :disabled="process && index == i" > {{ process && index == i ? 'Deleting...' : 'Delete'  }}</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout";
import Moment from "moment";
import { Link } from "@inertiajs/inertia-vue3";
const moment = Moment;
export default {
    name: "Manage",
    props: {
        files: Array,
        totalDelete: Array,
        nextBackupSchedule: String,
    },
    components: {
        AdminLayout, Link
    },
    data(){
        return{
            moment: Moment,
            process: false,
            backupProcess: false,
            index: null,
            time: '',
            baTime: '',
        }
    },
    methods: {
        deleteFile(path, i){
            this.index = i;
            this.$inertia.get(route('admin.backup.destroy', {
                'path': path,
                processing: this.process = true
            }))
        },
        nowTime(){
            let time = this;
          setInterval(function (){
              time.time = moment().format('MMMM Do YYYY, h:mm:ss a');
            },1000)
        },

        backupSubmit(){
            this.$inertia.get(route('admin.backup.start', {
                processing: this.backupProcess = true
            }))
        }
    },
    mounted() {
        this.nowTime();
        const getId = this.$refs.outputEl2;
        if (this.$page.props.flash.output){
            setTimeout(function (){
                getId.style.display = 'none';
            }, 10000);
        }
    },

}
</script>

<style scoped>

</style>
