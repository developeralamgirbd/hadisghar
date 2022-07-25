<template>
    <AppLayout title="Category List">
            <h1 class="uppercase text-green-500 text-2xl mb-4 pt-6 text-center">Category List</h1>
            <div class="p-2 md:p-6 users h-full">
                <div class="gap-8 mt-2  lg:flex" :class="permissionArr.includes('create category') == false ? 'block' : 'md:flex'">
                    <!-------------------------------------
                    ! Categories Table
                    -------------------------------------->
                    <div v-if="permissionArr.includes('view categories')" class="w-full md:w-4/6 mb-6 lg:mb-0">
                        <CategoryTable :categories="categories" :errors="errors" :trashed="trashed"/>
                    </div>
                    <!-------------------------------------
                     ! Category Create Form
                     -------------------------------------->
                    <div class="md:w-2/6" v-if="permissionArr.includes('create category')">

                        <form @submit.prevent="categoryStore" class="bg-white p-4 rounded border">
                            <h1 class="text-green-500 border-b border-green-400 mb-2">Create Category</h1>
                            <label for="category" class="text-sm text-gray-800">Category Name</label>
                            <JetInput
                                id="category"
                                type="text"
                                v-model="form.category_name"
                                name="category_name"
                                class="mt-1 block w-full mb-2 text-sm"
                                :class="errors.category_name ? 'border-red-500': ''"
                                placeholder="category name"
                                autofocus
                            />
                            <p class="text-sm text-red-500 mb-2" v-if="errors.category_name">{{errors.category_name}}</p>
                            <label for="slug" class="text-sm text-gray-800">Slug</label>
                            <JetInput
                                id="slug"
                                type="text"
                                v-model="form.slug"
                                name="slug"
                                class="mt-1 block w-full text-sm"
                                :class="errors.slug ? 'border-red-500': ''"
                                placeholder="slug"
                                autofocus
                            />
                            <p class="text-sm text-red-500 mb-2" v-if="errors.slug">{{errors.slug}}</p>
                            <button type="submit" class="bg-green-500 py-1 px-2 text-white text-sm rounded mt-4"><i class="fa-solid fa-floppy-disk"></i> Create</button>
                        </form>
                    </div>
                </div>
            </div>
    </AppLayout>
</template>
<script>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '../../../Layouts/AdminLayout';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import moment from 'moment';
import CategoryTable from "../../../Components/DataTables/Category/CategoryTable";

export default {
    props:{
        categories: Object,
        errors: Object,
        trashed: Number,
          },
    components: {
        CategoryTable,
        Head,
        Link,
        AppLayout,
        JetButton,
        JetInput,
        JetLabel,
    },
    data(){
        return{
            moment:moment,
            permissionArr: [],
            form: useForm({
                'category_name': '',
                'slug': ''
            }),
            categoryData: [],
            isOpen: '',
            categoryId: '',

        }
    },
    methods:{
        categoryStore(){
            this.form.post(route('admin.category.store'), {
                preserveState: false,
                preserveScroll: true,
                onSuccess: () => {
                    this.$toast.fire({
                        icon: 'success',
                        title: 'Category create successfully'
                    })
                    this.form.reset();
                }
            });
        },
    },

    mounted(){
        this.$page.props.userPermissions.forEach(item =>
            this.permissionArr.push(item.name),
        );
        this.categoryData = this.$props.categories;
    },

    watch: {
        form:{
            handler(){
                let catVal = this.form.category_name.replace(/([" "]+)/g, '-');
                this.form.slug = catVal.toLowerCase();
            },
            deep: true,
        },
    },
}
</script>

<style scoped>
</style>
