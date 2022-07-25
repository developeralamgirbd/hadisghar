<template>
    <div class="search md:flex md:justify-between md:items-center w-full mb-4 md:mb-0">
            <div class="mb-4 md:mb-0">
                <span class="mr-1 text-primary">Show</span>
                <select v-model="currentEntries" class="border-gray-300 py-1 px-6 focus:ring-green-400 focus:outline-none focus:border-green-400" @change="paginateEntries()">
                    <option v-for="(show, i) in showEntries" :key="show" :value="show">{{ show }}</option>
                </select>
                <Link :href="route('admin.post.index')" preserve-scroll class="ml-4 text-primary" :class="{'border border-gray-300 rounded-full py-1 px-2' : activeStatus === ''}">Total ({{ total }})</Link>
                    <button type="button" @click="status('draft')" class="ml-4 text-gray-500" :class="{'border border-gray-300 rounded-full py-1 px-2' : activeStatus === 'draft'}"> Draft ({{ draft ? draft : '0' }})</button>
                <button type="button" @click="status('pending')" class="ml-4 text-rose-500" :class="{'border border-gray-300 rounded-full py-1 px-2' : activeStatus === 'pending'}"> Pending ({{ pending ? pending : '0' }})</button>
                <button type="button" @click="status('published')" class="ml-4 text-green-500" :class="{'border border-gray-300 rounded-full py-1 px-2' : activeStatus === 'published'}"> Published ({{ published ? published : '0' }})</button>
                <Link :href="route('admin.post.trashed')" preserve-scroll class="ml-4 text-primary"><fa icon="fa-trash"/> Trash ({{ trashed ? trashed : '0' }})</Link>
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
    <div class="hidden lg:block">
        <table class="w-full bg-gray-50 rounded table-auto" id="dataTable" ref="dataTable">
            <thead class="text-left">
            <tr>
                <th v-for="(column,name, index) in columns" :key="column.id" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                    <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                        <span>{{ name }}</span>
                        <fa v-if= "sortDirection === 'asc' && name != 'Action'" icon="fa-arrow-down-a-z" class="cursor-pointer"/>
                        <fa v-if= "sortDirection === 'desc' && name != 'Action'" icon="fa-arrow-up-z-a" class="cursor-pointer"/>

                    </div>
                </th>
            </tr>
            </thead>

            <tbody>
            <tr v-if="rows.length > 0" v-for="(post, index) in rows" :key="post.id">
                <td>
                    {{ post.title ? post.title.length > 50 ? post.title.substring(0, 50) +'...' : post.title : '' }}
                </td>
                <td>
                    {{ post.category ? post.category.category_name : '' }}
                </td>
                <td>
                    {{ post.user ? post.user.name : '' }}
                </td>
                <td>
                    <span :class="post.status === 'published' ? 'text-green-600' : '' || post.status === 'pending' ? 'text-red-600' : '' || post.status === 'draft' ? 'text-gray-600' : ''">
                        {{ post.status }}
                    </span>

                </td>
                <td>
                    {{ moment(post.created_at).format('MMMM Do, YYYY')}}
                </td>
                <td>
                    <div class="">
                        <!-- Others and own post edit Button -->
                         <span v-if="permissionArr.includes('edit post') && permissionArr.includes('edit others post')">
                               <Link :href="route('admin.post.edit', post.id)" class="mr-2 bg-sky-600 text-white rounded py-1 px-2"><fa icon="fa-pen-to-square"/></Link>
                         </span>
                        <!-- Only Own post edit button -->
                        <span v-if="permissionArr.includes('edit post') && !permissionArr.includes('edit others post') && active_user_id === post.user.id">
                               <Link :href="route('admin.post.edit', post.id)" class="mr-2 bg-sky-600 text-white rounded py-1 px-2"><fa icon="fa-pen-to-square"/></Link>
                         </span>
                        <!-- Others and own post Delete Button -->
                        <span v-if="permissionArr.includes('delete post') && permissionArr.includes('delete others post')">
                            <button type="button" class="mr-2 bg-rose-500 text-white rounded py-1 px-[10px] text-[12px]" @click.prevent="deleteSwal(post.id)"><fa icon="fa-trash-can"/></button>
                        </span>
                        <!-- Only Own post Delete Button -->
                        <span v-if="permissionArr.includes('delete post') && !permissionArr.includes('delete others post') && active_user_id === post.user.id">
                                <button type="button" class="mr-2 bg-rose-500 text-white rounded py-1 px-[10px] text-[12px]" @click.prevent="deleteSwal(post.id)"><fa icon="fa-trash-can"/></button>
                        </span>
                        <!-- View post button -->
                        <span>
                           <a :href="route('admin.post.review', post.slug)" target="_blank" class="bg-sky-600 text-white rounded py-1 px-2">
                                <fa icon="fa-eye"/>
                            </a>
                        </span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
        <!----------------------
        ! Responsive Table
        ------------------------>
    <div class="block lg:hidden">
        <table class="w-full bg-gray-50 rounded table-auto " id="responsiveDataTable" ref="responsiveDataTable">
        <thead class="text-left">
        <tr>
            <th v-for="(column,name, index) in responsiveColumns" :key="column.id" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                    <span>{{ name }}</span>
                    <fa :icon="sortDirection === 'asc' && name !== 'Action'" class= "fa-solid fa-arrow-down-a-z cursor-pointer"> </fa>
                    <fa :icon="sortDirection === 'desc' && name !== 'Action'" class="fa-solid fa-arrow-up-z-a cursor-pointer"></fa>
                </div>
            </th>
        </tr>
        </thead>

        <tbody>
        <tr v-if="rows.length > 0" v-for="(post, index) in rows" :key="index">
            <td class="block py-6">
                <div class="flex justify-between">
                    {{ post.title ? post.title.length > 70 ? post.title.substring(0, 70) +'...' : post.title : '' }}
                    <button @click="showData(post.id)" type="button" class="flex justify-center items-center border border-green-400 p-2 rounded-full h-8 w-8 text-green-400 hover:bg-green-500 hover:text-white transition duration-300">
                        <fa icon="fa-arrow-down" />
                    </button>
                </div>
                <div class="mt-4 border-t border-green-400" :class="isOpen === post.id ? 'block' : 'hidden'">
                    <p class="mb-6 ">Category: {{ post.category ? post.category.category_name : '' }}</p>
                    <p class="mb-6">Author: {{ post.user ? post.user.name : '' }}</p>
                    <p class="mb-6">Author: {{ post.user ? post.user.name : '' }}</p>
                    <p class="mb-6">Status:
                        <span :class="post.status === 'published' ? 'text-green-600' : '' || post.status === 'pending' ? 'text-red-600' : '' || post.status === 'draft' ? 'text-gray-600' : ''">
                            {{ post.status }}
                        </span>
                    </p>
                    <p class="mb-6">Date: {{ moment(post.created_at).format('MMMM Do, YYYY')}}</p>
                    <p class="mb-6">Action:
                        <!-- Others and own post edit Button -->
                        <span v-if="permissionArr.includes('edit post') && permissionArr.includes('edit others post')" class="mr-4">
                               <Link :href="route('admin.post.edit', post.id)" class="mr-2 bg-sky-600 text-white rounded py-1 px-2">
                                   <fa icon="fa-pen-to-square"/>
                               </Link>
                         </span>
                        <!-- Only Own post edit button -->
                        <span v-if="permissionArr.includes('edit post') && !permissionArr.includes('edit others post') && active_user_id === post.user.id" class="mr-4">
                               <Link :href="route('admin.post.edit', post.id)" class="mr-2 bg-sky-600 text-white rounded py-1 px-2">
                                   <fa icon="fa-pen-to-square"/>
                               </Link>
                         </span>
                        <!-- Others and own post Delete Button -->
                        <span v-if="permissionArr.includes('delete post') && permissionArr.includes('delete others post')" class="mr-4">
                            <button type="button" class="mr-2 bg-rose-500 text-white rounded py-1 px-[10px] text-[12px]" @click.prevent="deleteSwal(post.id)">
                                <fa icon="fa-trash-can"/>
                            </button>
                        </span>
                        <!-- Only Own post Delete Button -->
                        <span v-if="permissionArr.includes('delete post') && !permissionArr.includes('delete others post') && active_user_id === post.user.id" class="mr-4">
                                <button type="button" class="mr-2 bg-rose-500 text-white rounded py-1 px-[10px] text-[12px]" @click.prevent="deleteSwal(post.id)">
                                     <fa icon="fa-trash-can"/>
                                </button>
                        </span>
                        <!-- View post button -->
                        <span class="mr-4">
                            <a :href="route('admin.post.review', post.slug)" target="_blank" class="bg-sky-600 text-white rounded py-1 px-2">
                                <fa icon="fa-eye"/>
                            </a>
                        </span>
                    </p>
                </div>
            </td>

        </tr>
        </tbody>
    </table>
    </div>
        <p class="text-center text-lg mt-4" v-if="isSearchNull">Data not found</p>
        <!-- Pagination-->
        <div class="md:flex md:justify-between md:items-center mt-4 mb-20">
            <div class="">Show {{ showInfo.from }} to {{ showInfo.to }} of {{ showInfo.of }} entries</div>
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
import { Link } from "@inertiajs/inertia-vue3";
import JetInput from '@/Jetstream/Input.vue';
import Moment from 'moment';
import Swal from "sweetalert2";
import * as $ from 'alga-js';
import _ from 'lodash';
export default {
    name: "PostTable",
    props: {
        posts: Object,
        trashed: Number,
        draft: Number,
        pending: Number,
        published: Number,
        total: Number
    },
    components: {
      Link, JetInput
    },
    data(){
        return {
            domainName: window.location.origin+'/post/',
            moment: Moment,
            active_user_id: null,
            permissionArr: [],
            rawRows: this.$props.posts,
            showEntries: [5, 10, 15, 25, 50, 75, 100 ],
            currentEntries: 10,
            currentPage: 1,
            allPages: 1,
            sortDirection: 'desc',
            rows: [],
            columns: {
                Title: 'title',
                Category: 'category_id',
                Author: 'user_id',
                Status: 'status',
                Date: 'created_at',
                Action: '',
            },
            responsiveColumns: {
                Title: 'title',
            },
            isSearchNull: false,
            collapseData:false,
            isOpen: '',
            field: {
                status: null
            },
            activeStatus: '',
            term: '',
            debouncedHandler: null
        }
    },

    created(){
        this.$page.props.userPermissions.forEach(item =>
            this.permissionArr.push(item.name),
        );
        this.rows = this.rawRows;
        this.rows = $.array.paginate(this.rawRows, this.currentPage, this.currentEntries);
        this.allPages = $.array.pages(this.rawRows, this.currentEntries);

        this.active_user_id = this.$page.props.active_user_id;
        let urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('status')){
            this.activeStatus = urlParams.get('status')
        };

        // this.debouncedHandler = debounce(e => {
        //     this.rows = $.array.search(this.rawRows, e.target.value);
        //
        // }, 300)


    },

    computed: {
        showInfo(){
            return $.array.pageInfo(this.rawRows, this.currentPage, this.currentEntries);
        },
        showPagination(){
            return $.array.pagination(this.allPages, this.currentPage, 3);
        }
    },

    methods: {
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

        status(status){
            this.field.status = status;
            this.rawRows = this.$props.posts;
            this.$inertia.get(this.route('admin.post.index'), this.field,{
                preserveState: false,
                replace: true,
            });
            this.activeStatus = status;
        },

        showData(id){
            this.isOpen = id;
        },
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

        deleteSwal: function (id) {
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
                    this.$inertia.delete(route('admin.post.destroy', id), {
                        preserveState: false,
                        preserveScroll: true,
                    });
                    Swal.fire(
                        'Deleted!',
                        'Your post has been deleted.',
                        'success'
                    )
                }
            })
        },
    },

    watch: {
        currentEntries: {
            handler(){
                this.allPages = $.array.pages(this.rawRows, this.currentEntries);
            }
        },


    },

}
</script>

<style>


</style>
