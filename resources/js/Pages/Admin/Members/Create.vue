<template>
    <AdminLayout title="Create role">
            <h1 class="uppercase text-green-500 text-2xl my-3 text-center">Create Role</h1>
            <div class="md:flex justify-between items-center ml-6">
                <div class="mb-4 md:mb-0">
                    <Link preserve-scroll :href="route('admin.role.index')" class="text-gray-50 bg-green-500 px-5 py-3 rounded"><fa icon="fa-arrow-left"></fa> Back</Link>
                </div>
            </div>
            <div class="p-2 md:p-6">
                <CreateTable :permissions="permissions" :errors="errors"/>
            </div>
    </AdminLayout>
</template>


<script>
import CreateTable from "../../../Components/DataTables/Role/CreateTable";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";
export default {
    name: 'Create',
    props: {
      permissions: Object,
      errors: Object,
    },
    components: {
        CreateTable, AdminLayout, Link
    },
}
</script>


