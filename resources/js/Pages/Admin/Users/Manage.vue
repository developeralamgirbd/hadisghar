<template>
    <AppLayout title="Users List">
            <h1 class="uppercase text-green-500 text-2xl my-3 text-center">User List</h1>
            <div class="md:flex justify-between items-center ml-6">
                <div class="mb-4 md:mb-0">
                    <Link preserve-scroll v-if="permissionArr.includes('create user')" :href="route('admin.user.create')" class="text-gray-50 bg-green-500 px-3 py-2 text-lg rounded"><fa icon="fa-plus"></fa> Add New</Link>
                </div>
            </div>

            <div class="p-2 md:p-6 users">
                <!-------------------------------------
                 ! User Table
                -------------------------------------->
                <div class="mt-8 w-full overflow-x-auto">
                    <UserTable :users="users" :trashed="trashed"/>
                </div>
            </div>
    </AppLayout>
</template>
<script>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '../../../Layouts/AdminLayout';
import moment from 'moment';
import UserTable from "../../../Components/DataTables/User/UserTable";
export default {
    props:{
        users: Object,
          },
    name: "Manage",
    components: {
        Head,
        Link,
        AppLayout,
        UserTable
    },
    data(){
        return{
            moment:moment,
            permissionArr: [],
            isOpen: '',
        }
    },
    methods:{

        showData(id){
            this.isOpen = id;
        }
    },

    mounted(){
        this.$page.props.userPermissions.forEach(item =>
            this.permissionArr.push(item.name),
        );
    },
    }
</script>

<style scoped>

</style>
