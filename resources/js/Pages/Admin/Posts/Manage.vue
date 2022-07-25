<template>
    <AppLayout title="Posts List">
            <h1 class="uppercase text-green-500 text-2xl text-center my-3">Post List</h1>
            <div class="md:flex justify-between items-center ml-6">
                <div class="mb-4 md:mb-0">
                    <Link preserve-scroll v-if="permissionArr.includes('create post')" :href="route('admin.post.create')" class="text-white bg-green-500 px-4 py-3 text-lg rounded "><fa icon="fa-plus"></fa> Add New</Link>
                </div>
            </div>
            <div class="p-2 md:p-6 users">
                <!-------------------------------------
                 ! posts Table
                -------------------------------------->
                <div class="mt-8 w-full overflow-x-auto">
                    <PostTable :posts="posts" :trashed="trashed" :draft="draft" :pending="pending" :published="published" :total="total"/>
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
import PostTable from "../../../Components/DataTables/Post/PostTable";
export default {

    props:{
        posts: Object,
        trashed: Number,
        draft: Number,
        pending: Number,
        published: Number,
        total: Number,
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
        Head,
        Link,
        AppLayout,
        JetInput,
        PostTable

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
