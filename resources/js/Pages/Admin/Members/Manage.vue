<template>
    <AdminLayout title="Roles List">
            <h1 class="uppercase text-green-500 text-2xl my-3 text-center">Role List</h1>
            <div class="md:flex justify-between items-center ml-6">
                <div class="mb-4 md:mb-0">
                    <Link preserve-scroll v-if="permissionArr.includes('create role')" :href="route('admin.role.create')" class="text-gray-50 bg-green-500 px-4 py-3 rounded"><fa icon="fa-plus"></fa> Add New</Link>
                </div>
            </div>
            <div class="p-6 roles">
                <!-------------------------
                ! Roles Tables
                -------------------------->
                <div class="mt-8 w-full overflow-x-auto">
                    <RoleTable :roles="roles" :totalPermissions="totalPermissions"></RoleTable>
                </div>
            </div>
    </AdminLayout>
</template>
<script>
import RoleTable from "../../../Components/DataTables/Role/RoleTable";
export default {
    props:{
        roles: Object,
        totalPermissions: Number,
    },
    components: {
        RoleTable
    },

    data(){
      return{
          permissionArr: [],
          isOpen: false,
      }
    },
    created() {
    },
    mounted(){
        this.$page.props.userPermissions.forEach(item =>
            this.permissionArr.push(item.name),
        )
    },
}
</script>

<script setup>
import AdminLayout from "../../../Layouts/AdminLayout";
import {Link} from '@inertiajs/inertia-vue3';

</script>

<style>

</style>
