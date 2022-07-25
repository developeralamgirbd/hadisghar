<template>
    <AppLayout title="Posts List">
            <h1 class="border-b border-green-600 uppercase text-green-500 text-lg mb-4 pt-6 pl-6">Post Trashed List</h1>
            <div class="p-2 md:p-6 users">
                <div class="md:flex justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <Link :href="route('admin.post.index')" preserve-scroll class="text-gray-50 bg-green-500 px-2 py-1 rounded"> <fa icon="fa-arrow-left"/> Back</Link>
                    </div>
                </div>
                <!-------------------------------------
                 ! posts trashed Table
                -------------------------------------->
                <div class="mt-8 w-full overflow-x-auto">
                    <PostTrashedTable :trashed="trashed"/>
                </div>
            </div>
    </AppLayout>
</template>
<script>
import { Head, Link} from '@inertiajs/inertia-vue3';
import AppLayout from '../../../Layouts/AdminLayout';
import JetInput from '@/Jetstream/Input.vue';
import Swal from "sweetalert2";
import moment from 'moment';
import PostTrashedTable from "../../../Components/DataTables/Post/PostTrashedTable";
export default {
    props:{
        trashed: Object,
    },
    name: "Manage",

    data(){
        return{
            moment:moment,
            permissionArr: [],
            isOpen: '',
        }
    },
    components: {
        PostTrashedTable,
        Head,
        Link,
        AppLayout,
        JetInput,
    },
    methods:{
        showAlert: function (id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(route('admin.post.destroy', id));
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        },

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
