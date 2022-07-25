<template>
    <AdminLayout>
            <div class="p-6">
                <div ref="outputEl" class="my-4 text-green-800" v-if="$page.props.flash.output">
                    <div class="">
                        <p v-for="(msg, i) in $page.props.flash.output" :key="i">{{ msg }}</p>
                    </div>
                </div>
                <h1 class="text-2xl mb-4">Schedule Table</h1>
                <table class="w-full bg-gray-50 rounded">
                    <thead class="text-left">
                    <tr>
                        <th>
                            Cron
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Last Run
                        </th>
                        <th>
                            Next Run
                        </th>
                        <th>
                            Run
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(schedule_row, i) in schedule" :key="i">
                        <td>
                            {{ schedule_row.frequency ? schedule_row.frequency.name : schedule_row.cron}}
                        </td>
                        <td>
                            {{ replace(schedule_row.command_name) }}
                        </td>
                        <td class="text-rose-800">
                            {{ moment(schedule_row.last_run).format('MMMM Do YYYY, h:mm:ss a') }}
                        </td>
                        <td class="text-green-600">
                            {{ moment(schedule_row.next_run).format('MMMM Do YYYY, h:mm:ss a') }}
                        </td>
                        <td>
                            <button type="button" @click.prevent="run(schedule_row.command_name, i)" class="bg-green-400 py-1 px-4 rounded ml-2 text-white" :class="{'opacity-25': process && index == i}" :disabled="process && index == i" > {{ process && index == i ? 'Running...' : 'Run'  }}</button>
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
    name: "Calender",
    props: {
        schedule: Object,
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
            show: false
        }
    },
    computed: {

    },
    methods: {
        run(command, i){
            this.index = i;
            this.$inertia.get(route('admin.schedule.run', {
                'command': command,
                processing: this.process = true,
            }))
        },
        replace(data){
            const arr = data.replace(/([^a-zA-Z0-9]+)/g, ' ').split(" ");
            arr.forEach((item, i)=> {
                arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);
            })
            return arr.join(" ");

        }
    },
    mounted() {
        const getId = this.$refs.outputEl;
        if (this.$page.props.flash.output){
            setTimeout(function (){
                getId.style.display = 'none';
            }, 60000);
        }
    },

}
</script>

<style scoped>

</style>
