<template>
    <AppLayout title="Category List">
            <h1 class="uppercase text-green-500 text-2xl mb-4 pt-6 text-center">Category Trashed List</h1>
            <Link :href="route('admin.category.index')" preserve-scroll class="text-gray-50 bg-green-500 px-6 py-3 text-lg rounded ml-6"><fa icon="fa-arrow-left"></fa> Back</Link>
            <div class="p-2 md:p-6 users relative h-full">


                <div class="gap-8 mt-2">
                    <!-------------------------------------
                    ! Categories Trashed Table
                    -------------------------------------->
                    <div v-if="permissionArr.includes('view categories')" class="w-full">
                        <CategoryTrashedTable :trashed="trashed"/>
                    </div>
                </div>
            </div>
    </AppLayout>
</template>
<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AppLayout from '../../../Layouts/AdminLayout';
import CategoryTrashedTable from "../../../Components/DataTables/Category/CategoryTrashedTable";
export default {
    props:{
        trashed: Object,
          },
    components: {
        Head,
        Link,
        AppLayout,
        CategoryTrashedTable
    },
    data(){
        return{
            permissionArr: [],
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
