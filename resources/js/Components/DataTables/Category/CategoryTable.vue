<template>
    <div class="search md:flex md:justify-between md:items-center w-full mb-4 md:mb-0">
        <div class="mb-4 md:mb-0">
            <span class="mr-1 text-green-500">Show</span>
            <select v-model="currentEntries" class="border-gray-300 py-1 pr-6 focus:ring-green-400 focus:outline-none focus:border-green-400" @change="paginateEntries()">
                <option v-for="(show, i) in showEntries" :key="show" :value="show">{{ show }}</option>
            </select>
            <Link :href="route('admin.category.trashed')" preserve-scroll class="ml-4 text-green-500">
                <fa icon="fa-trash"/> Trash ({{ trashed ? trashed : '0' }})</Link>
        </div>
        <div class="">
            <JetInput
                id="email"
                type="text"
                name="search"
                @input="onSearch"
                class="mb-2 block w-full"
                placeholder="Search"
                autofocus
            />
        </div>
    </div>

    <table class="w-full bg-gray-50 rounded table-auto" id="dataTable" ref="dataTable">
        <thead class="text-left">
        <tr>
            <th v-for="(column,name, index) in columns" :key="column.id" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                    <span>{{ name }}</span>
                    <fa icon="fa-arrow-down-a-z" v-if= "sortDirection === 'asc' && name != 'Action'" class= "cursor-pointer"> </fa>
                    <fa icon="fa-arrow-up-z-a" v-if="sortDirection === 'desc' && name != 'Action'" class="cursor-pointer"></fa>
                </div>
            </th>
        </tr>
        </thead>

        <tbody>
        <tr v-if="rows.length > 0" v-for="(category, index) in rows" :key="index">
            <td>
                {{ category.category_name ? category.category_name : '' }}
            </td>
            <td>
                {{ category.slug ? category.slug : '' }}
            </td>
            <td>
                {{ category.posts ? category.posts.length : '0' }}
            </td>
            <td>
                <div class="">
                     <span v-if="permissionArr.includes('edit category')">
                           <button @click="editModal(category.id, category.category_name, category.slug, category.meta_description)" class="mr-2 bg-green-400 text-white px-2 py-1 rounded"><fa icon="fa-pen-to-square"/></button>
                     </span>
                        <span v-if="permissionArr.includes('delete category')">
                            <button type="button" class="bg-red-400 text-white px-2 py-1 rounded" @click.prevent="deleteSwal(category.id, category.category_name)"><fa icon="fa-trash-can"/></button>
                        </span>
                </div>
                <!---------------------
                ! Category Update Modal
                ---------------------->
                <div class="" v-if="isModalOpen">
                    <div class="fixed left-[5px] right-[8px] md:left-1/4 md:right-[0px] lg:left-[400px] top-10  bg-white rounded p-4 z-50 w-[98%] md:w-[400px] lg:w-[400px]">
                        <h1 class="text-lg mb-4 font-medium text-green-800">Update Category</h1>
                        <form @submit.prevent="updateCategory">
                            <div class="">
                                <JetLabel class="block" for="category">Category Name<span class="text-rose-500">*</span></JetLabel>
                                <JetInput
                                    type="text"
                                    v-model="updateForm.category"
                                    id="category"
                                    class="w-full mb-4"
                                    :class="{'border-red-500': errors.category}"
                                />
                                <p class="text-sm text-red-500 mb-2" v-if="errors.category">{{errors.category}}</p>
                            </div>
                            <div class="">
                                <JetLabel class="block" for="slug">Category Slug<span class="text-rose-500">*</span></JetLabel>
                                <JetInput
                                    type="text"
                                    v-model="updateForm.categorySlug"
                                    id="slug"
                                    class="w-full mb-4"
                                    :class="{'border-red-500': errors.categorySlug}"
                                />
                                <p class="text-sm text-red-500 mb-2" v-if="errors.categorySlug">{{errors.categorySlug}}</p>
                            </div>

                            <div class="input-group w-full mt-8">
                                <label for="metaDescription" class="text-gray-800 text-lg">Meta Description<span class="text-rose-500">*</span></label>
                                <textarea type="text"
                                          rows="6"
                                          id="metaDescription"
                                          name="meta_description"
                                          v-model="updateForm.meta_description"
                                          class="w-full rounded-[5px] border-gray-300"
                                          :class="errors.meta_description ? 'border-rose-500 focus:border-rose-300 focus:ring-rose-300' : 'focus:border-indigo-300 focus:ring-indigo-300' "
                                ></textarea>
                                <progress id='CatProgress' ref='CatProgress' max='156' :value='updateForm.meta_description.length' class='block w-full'></progress>
                                <p class="text-red-500 " v-if="errors.meta_description">{{ errors.meta_description }}</p>
                            </div>

                            <div class="">
                                <button type="submit" class="mt-4 mr-2 bg-blue-500 py-1 px-2 rounded text-white">Update</button>
                                <button type="button" @click="cancelEditModal" class="mt-4 bg-red-500 py-1 px-2 rounded text-white">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div v-if="isModalOpen" class="fixed left-0 top-0 w-full bg-gray-600" style="opacity: 0.1; min-height: 100%"></div>
            </td>

        </tr>
        </tbody>
    </table>
    <p class="text-center text-lg mt-4" v-if="isSearchNull">Data not found</p>

    <div class="md:flex md:justify-between md:items-center mt-4 mb-20">
        <div class="" id="demo">Show {{ showInfo.from }} to {{ showInfo.to }} of {{ showInfo.of }} entries</div>
        <div class="">
            <div class="block md:hidden">
                <div class="gap-1 items-center justify-between flex">
                    <div class="">
                        <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === 1}" type="button" @click.prevent="currentPage = 1, paginateEntries()">First</button>
                        <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden' : currentPage === 1}" type="button" @click.prevent="(currentPage < 1) ? currentPage = 1 : currentPage -= 1, paginateEntries()">Prev</button>
                    </div>
                    <div class="">
                        <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === allPages || rows.length < 1 || showInfo.of === currentEntries}" type="button" @click.prevent="(currentPage > allPages) ? currentPage = 1 : currentPage += 1, paginateEntries()">Next</button>
                        <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === allPages || rows.length < 1 || showInfo.of === currentEntries}" type="button" @click.prevent="currentPage = allPages, paginateEntries()">Last</button>
                    </div>
                </div>
            </div>

            <div class="hidden md:block">
                <ul class="flex gap-1 items-center">
                    <li>
                        <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === 1}" type="button" @click.prevent="currentPage = 1, paginateEntries()">First</button>
                    </li>
                    <li>
                        <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden' : currentPage === 1}" type="button" @click.prevent="(currentPage < 1) ? currentPage = 1 : currentPage -= 1, paginateEntries()">Prev</button>
                    </li>

                    <li v-for="pageNum in showPagination" :key="pageNum"
                        class="border border-white hover:text-blue-500 transition rounded"
                        :class="pageNum === currentPage ? 'bg-gray-300 border-white' : 'text-gray-900'"
                        v-if="currentEntries < showInfo.of">
                        <button type="button" @click.prevent="paginateEvent(pageNum)" class="block py-1 px-4" :disabled="pageNum === currentPage" v-if="currentEntries < showInfo.of">{{ pageNum }}</button>
                    </li>

                    <li>
                        <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === allPages || rows.length < 1 || showInfo.of === currentEntries}" type="button" @click.prevent="(currentPage > allPages) ? currentPage = 1 : currentPage += 1, paginateEntries()">Next</button>
                    </li>
                    <li>
                        <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === allPages || rows.length < 1 || showInfo.of === currentEntries}" type="button" @click.prevent="currentPage = allPages, paginateEntries()">Last</button>
                    </li>
                </ul>
            </div>


        </div>
    </div>
</template>


<script>
import {Link, useForm} from "@inertiajs/inertia-vue3";
import JetLabel from '@/Jetstream/Input.vue';
import JetInput from '@/Jetstream/Input.vue';
import Moment from 'moment';
import Swal from "sweetalert2";
import * as $ from 'alga-js';
import _ from "lodash";

export default {
    name: "CategoryTable",
    props: {
        categories: Object,
        errors: Object,
        trashed: Number,
    },

    components: {
      Link, JetInput, JetLabel
    },

    data(){
        return {
            moment: Moment,
            permissionArr: [],
            rawRows: this.$props.categories,
            rows: [],
            columns: {
                Category: 'category_name',
                Slug: 'slug',
                Post: '',
                Action: ''
            },
            showEntries: [5, 10, 15, 25, 50, 75, 100 ],
            currentEntries: 10,
            filteredEntries: [],
            sortDirection: 'desc',
            currentPage: 1,
            allPages: 1,
            isSearchNull: false,
            isModalOpen: false,
            categoryId: '',
            seoMetaCheck: '',
            lengthSeoMeta: '',
            updateForm: useForm({
                'category': '',
                'categorySlug': '',
                'meta_description': ''
            }),


        }
    },

    computed: {
        showInfo(){
           return $.array.pageInfo(this.rawRows, this.currentPage, this.currentEntries);
        },
         showPagination(){
          return $.array.pagination(this.allPages, this.currentPage, 3);
         }
    },
    created(){
        this.$page.props.userPermissions.forEach(item =>
            this.permissionArr.push(item.name),
        );
        this.rows = this.rawRows;
        this.rows = $.array.paginate(this.rawRows, this.currentPage, this.currentEntries);
        this.allPages = $.array.pages(this.rawRows, this.currentEntries);
    },



    methods: {
        editModal(id, name, slug, meta_description){
            this.isModalOpen = true;
            this.categoryId = id;
            this.updateForm.category = name;
            this.updateForm.categorySlug = slug;
            this.updateForm.meta_description = meta_description;

        },
        deleteSwal: function (id, category) {
            Swal.fire({
                title: 'Are you sure?',
                html: `Delete <span class="text-2xl text-red-600">${category}</span> Category`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(route('admin.category.destroy', id), {
                        preserveState: false,
                        preserveScroll: true,
                    });
                    Swal.fire(
                        'Deleted!',
                        `${category} Category has been deleted`,
                        'success'
                    )
                }
            })
        },
        onSearch: _.debounce(function (e){
            if (e.target.value.length === 0){
                this.currentEntries = 10;
                this.paginateEntries();
            }else {
                this.currentEntries = this.showInfo.of;
                this.paginateEntries();
                this.rows = $.array.search(this.rawRows, e.target.value)
            }
        }, 400),
        sortRecords(column){
            this.sortDirection === 'desc' ? this.sortDirection = 'asc' : this.sortDirection === 'asc' ? this.sortDirection = 'desc' : '';
            this.rows = $.array.sortBy(this.rows, column, this.sortDirection)
        },
        paginateEntries(){
            this.rows = $.array.paginate(this.rawRows, this.currentPage, this.currentEntries);
        },
        paginateEvent(page){
            this.currentPage = page;
            this.paginateEntries();
        },


        cancelEditModal(){
            this.isModalOpen = false;
            this.updateForm.reset();
        },
        updateCategory(){
            this.updateForm.put(route('admin.category.update', this.categoryId), {
                preserveState: false,
                preserveScroll: true,
                onSuccess: () => {
                    this.isModalOpen = false,
                        this.$toast.fire({
                            icon: 'success',
                            title: 'Category updated'
                        })
                }
            });
        },
    },


    watch: {
        currentEntries: {
            handler(){
                this.allPages = $.array.pages(this.rawRows, this.currentEntries);
            }
        },
        updateForm:{
            handler(){
                let catVal = this.updateForm.category.replace(/([" "]+)/g, '-');
                this.updateForm.categorySlug = catVal.toLowerCase();
                    this.lengthSeoMeta = this.updateForm.meta_description.length;

                    if (this.updateForm.meta_description !== ''){
                        if (this.updateForm.meta_description.length < 145 || this.updateForm.meta_description.length > 156 && this.updateForm.meta_description.length !== 0){
                            this.seoMetaCheck = true;
                            if (document.getElementById('CatProgress') != null) {
                                const progressEl2 = document.getElementById('CatProgress')
                                progressEl2.style.setProperty('--progressbar-background', '#EE7C1B')
                            }
                        }else if(this.updateForm.meta_description.length === 0){
                            this.seoMetaCheck = false;
                        }else {
                            this.seoMetaCheck = false;
                            if (document.getElementById('CatProgress') != null) {
                                const progressEl3 = document.getElementById('CatProgress');
                                progressEl3.style.setProperty('--progressbar-background', '#7AD03A')
                            }
                        }
                    }
            },
            deep: true,

        },
    },
}
</script>

<style scoped>

.email:hover .sort{
    display: block!important;
}

progress{
    --progressbar-background: #EE7C1B;
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
