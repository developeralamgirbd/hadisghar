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
                            <h1 class="text-green-500 border-b border-green-400 mb-4 text-xl">Create Category</h1>
                            <label for="category" class="text-lg text-gray-800">Category Name<span class="text-rose-500">*</span></label>
                            <JetInput
                                id="category"
                                type="text"
                                v-model="form.category_name"
                                name="category_name"
                                class="mt-1 block w-full mb-2 text-lg"
                                :class="errors.category_name ? 'border-red-500': ''"
                                autofocus
                            />
                            <p class="text-sm text-red-500 mb-2" v-if="errors.category_name">{{errors.category_name}}</p>
                            <label for="slug" class="text-lg text-gray-800">Slug<span class="text-rose-500">*</span></label>
                            <JetInput
                                id="slug"
                                type="text"
                                v-model="form.slug"
                                name="slug"
                                class="mt-1 block w-full text-lg"
                                :class="errors.slug ? 'border-red-500': ''"
                                autofocus
                            />
                            <p class="text-sm text-red-500 mb-2" v-if="errors.slug">{{errors.slug}}</p>
                            <div class="input-group w-full mt-8">
                                <label for="metaDescription" class="text-gray-800 text-lg">Meta Description<span class="text-rose-500">*</span></label>
                                <textarea type="text"
                                          rows="6"
                                          id="metaDescription"
                                          name="meta_description"
                                          v-model="form.meta_description"
                                          class="w-full rounded-[5px] border-gray-300"
                                          :class="errors.meta_description ? 'border-rose-500 focus:border-rose-300 focus:ring-rose-300' : 'focus:border-indigo-300 focus:ring-indigo-300' "
                                ></textarea>
                                <progress id="CatMetaProgress" max="156" :value="form.meta_description.length" class="block w-full"></progress>
                                <p class="text-red-500 " v-if="errors.meta_description">{{ errors.meta_description }}</p>
                            </div>
                            
                            <button type="submit" class="bg-green-500 py-2 px-4 text-white text-lg rounded mt-4"><i class="fa-solid fa-floppy-disk"></i> Create</button>
                        </form>
                    </div>
                </div>
            </div>
    </AppLayout>
</template>
<script>
import { Head, Link} from '@inertiajs/inertia-vue3';
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
            categoryData: [],
            isOpen: '',
            categoryId: '',

        }
    },
    mounted(){
        this.$page.props.userPermissions.forEach(item =>
            this.permissionArr.push(item.name),
        );
        this.categoryData = this.$props.categories;
    },
}
</script>
<script setup>

import {ref, watch} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";

let seoMetaCheck = ref(null);
let lengthSeoMeta = ref(null);


const form = useForm({
    'category_name': '',
    'slug': '',
    'meta_description': '',
});

watch(form, (current, old) => {
    let catVal = form.category_name.replace(/([' ','@','#','%','^','&','*','(',')','|','-','~','?','`','"',"'",'!','৥৳','%','ঃ',"\\/"]+)/g, '-');
    form.slug = catVal.toLowerCase();
    lengthSeoMeta.value = form.meta_description.length;

    if (form.meta_description !== ''){
        if (form.meta_description.length < 145 || form.meta_description.length > 156 && form.meta_description.length !== 0){
            seoMetaCheck.value = true;
            const progressEl = document.getElementById('CatMetaProgress')
            progressEl.style.setProperty('--progressbar-background', '#EE7C1B')
        }else if(form.meta_description.length === 0){
            seoMetaCheck.value = false;
        }else {
            seoMetaCheck.value = false;
            const progressEl = document.getElementById('CatMetaProgress')
            progressEl.style.setProperty('--progressbar-background', '#7AD03A')
        }
    }
});

const categoryStore = function (){
    form.post(route('admin.category.store'), {
        preserveState: false,
        preserveScroll: true,
        onSuccess: () => {
            $toast.fire({
                icon: 'success',
                title: 'Category create successfully'
            })
            form.reset();
        }
    })
};

</script>

<style scoped>
progress{
    --progressbar-background: red;
}
progress {
    height: 10px;
    border: 1px solid #dedede;
}
progress::-webkit-progress-bar {
    background-color: transparent;
}
progress::-webkit-progress-value {
    background-color: var(--progressbar-background);
}
</style>
