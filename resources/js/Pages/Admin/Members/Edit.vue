<template>
    <AdminLayout title="Role Edit">
            <h1 class="uppercase text-green-500 text-2xl my-3 text-center">Edit Role</h1>
            <div class="md:flex justify-between items-center ml-6">
                <div class="mb-4 md:mb-0">
                    <Link preserve-scroll :href="route('admin.role.index')" class="text-gray-50 bg-green-500 px-3 py-2 text-lg rounded"><fa icon="fa-arrow-left"></fa> Back</Link>
                </div>
            </div>
            <div class="p-2 md:p-6">
                <EditTable :permissions="permissions" :roleAndPermissions="roleAndPermissions" :errors="errors"/>
            </div>
    </AdminLayout>
</template>
<script>
import {useForm, Link} from "@inertiajs/inertia-vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import EditTable from "../../../Components/DataTables/Role/EditTable";
export default {
    props: {
        roleAndPermissions: Object,
        errors: Object,
        permissions: Object
    },
    components: {
        EditTable, Link,AdminLayout
    },
    data(){
        return{
            active: false,
            form: useForm({
                role_name: '',
                permissionsArr: []
            })
        }
    },

}
</script>

<script setup>



</script>
<style scoped>
.roleCreate table td input{
    cursor: pointer;
}
.rolesTable{
    border-collapse: collapse;
    border-spacing: 30px;
}
.rolesTable th, td{
    padding: 10px
}
</style>


<!--<form @submit.prevent="roleUpdate(roleAndPermissions[0].id)">-->
<!--<input type="text" v-model="form.role_name" name="role_name" class="border rounded border-gray-100 w-full md:w-1/2" :class="{'border-red-500' : errors.role_name}" placeholder="Enter New Role Name">-->

<!--<div class="md:flex md:gap-2">-->
<!--    <div class="md:w-5/6">-->
<!--        <p v-if="errors.role_name" class="text-red-500">{{ errors.role_name }}</p>-->
<!--        <div class="roles-cap-tabs border mt-6">-->
<!--            <table class="w-full rolesTable bg-white">-->
<!--                <thead>-->
<!--                <tr class="border-b border-blue-400">-->
<!--                    <th class="text-left ml-2 text-blue-500 text-lg md:text-2xl">Permissions Name</th>-->
<!--                    <th class="text-blue-500 text-lg md:text-2xl">Grant</th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                <tr v-for="(permission, index) in permissions" :key="permission.id" class="border-b">-->
<!--                    <td>-->
<!--                        <label :for="`permission${permission.id}`">{{permission.name.toUpperCase()}}</label>-->
<!--                    </td>-->

<!--                    <td class="text-center"><input type="checkbox" v-model="form.permissionsArr" :id="`permission${permission.id}`" :value="permission.name" :name="`permissionSelect${permission.id}`"></td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->

<!--    </div>-->
<!--    <div class="md:w-1/6 mt-6">-->
<!--        <div class="bg-white rounded shadow p-2">-->
<!--            <ul class="text-sm">-->
<!--                <li class="mb-6"><i class="fa-solid fa-check mr-2"></i> Granted: {{form.permissionsArr.length}}</li>-->
<!--                <li class="mb-6"><i class="fa-solid fa-xmark mr-2"></i> Denied: {{ permissions.length - form.permissionsArr.length }}</li>-->
<!--                <li class="text-right border-t mt-2 "><button type="submit" class="bg-blue-500 px-2 py-1 mt-2 rounded text-white">Update</button></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!--</form>-->
