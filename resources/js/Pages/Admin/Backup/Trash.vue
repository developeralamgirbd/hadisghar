<template>
    <AdminLayout title="Backup Deletes Files">
            <h1 class="uppercase text-green-500 text-2xl my-3 text-center">Backup Deletes Files</h1>
            <div class="md:flex justify-between items-center">
                <div class="mb-4 md:mb-0 pl-6">
                    <Link :href="route('admin.backup.index')" preserve-scroll class="text-gray-50 bg-green-500 px-3 py-2 text-lg rounded"> <fa icon="fa-arrow-left"/> Back</Link>
                </div>
            </div>
            <div class="p-6">
                <table class="w-2/3 bg-gray-50 rounded mt-6">
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
                    <tr v-for="(file, i) in trash" :key="i">
                        <td>
                            <p>{{ file.name }}</p>
                            <img class="h-6 w-6 object-cover block mt-2" :src="'/images/dropbox.png'" >

                        </td>
                        <td>
                            <button type="button" @click.prevent="restoreFile(file.path_lower, i)" class="bg-blue-400 py-1 px-4 rounded ml-2 text-white" :class="{'opacity-25': process && index == i}" :disabled="process && index == i" > {{ process && index == i ? 'Restoring...' : 'Restore'  }}</button>
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
import {Link} from "@inertiajs/inertia-vue3";

export default {
    name: "Trash",
    props: {
        trash: Array
    },
    components: {
        AdminLayout, Link
    },
    data(){
        return{
            moment: Moment,
            index: null,
            process: false
        }
    },

    methods: {
        restoreFile(path, i){
            this.index = i;
            this.$inertia.get(route('admin.backup.restore', {
                'path': path,
                processing: this.process = true,
            }))
        },
    }

}
</script>

<style scoped>

</style>
